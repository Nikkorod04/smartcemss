<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\AssessmentSummary;
use App\Services\CommunityAnalysisService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AssessmentSummaryController extends Controller
{
    protected $analysisService;

    public function __construct(CommunityAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    /**
     * Display the assessment summary for a community
     */
    public function show(Community $community, Request $request): View
    {
        // Get quarter and year from request, or use current
        $quarter = $request->query('quarter');
        $year = $request->query('year');
        
        if (!$quarter || !$year) {
            $month = now()->month;
            $quarter = match(true) {
                $month <= 3 => 'Q1',
                $month <= 6 => 'Q2',
                $month <= 9 => 'Q3',
                default => 'Q4'
            };
            $year = now()->year;
        }

        // Get or create the assessment summary for this quarter/year
        $summary = $community->assessmentSummaries()
            ->where('quarter', $quarter)
            ->where('year', $year)
            ->first();
        
        if (!$summary) {
            $summary = AssessmentSummary::calculateForCommunity($community, $quarter, $year);
        }

        $formatted = $summary->getFormattedSummary();

        // Get raw assessments for charts
        $assessments = $community->needsAssessments()
            ->where('quarter', $quarter)
            ->where('year', $year)
            ->get();

        // Generate chart data
        $chartData = $this->generateChartData($assessments);

        // Get all available quarters/years for this community
        $availablePeriods = $community->needsAssessments()
            ->selectRaw('DISTINCT quarter, year')
            ->orderByDesc('year')
            ->orderByDesc('quarter')
            ->get();

        return view('assessments.summary', [
            'community' => $community,
            'summary' => $summary,
            'formatted' => $formatted,
            'quarter' => $quarter,
            'year' => $year,
            'availablePeriods' => $availablePeriods,
            'assessments' => $assessments,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Regenerate AI analysis for a community's assessment summary
     */
    public function regenerateAnalysis(Community $community, Request $request): RedirectResponse
    {
        $quarter = $request->input('quarter');
        $year = $request->input('year');

        if (!$quarter || !$year) {
            return redirect()->back()->with('error', 'Quarter and year are required.');
        }

        try {
            $this->analysisService->generateAnalysis($community, $quarter, (int)$year);

            return redirect()->back()->with('success', 'AI analysis has been regenerated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate AI analysis: ' . $e->getMessage());
        }
    }

    private function generateChartData($assessments)
    {
        if ($assessments->isEmpty()) {
            return [];
        }

        return [
            'demographics' => $this->getDemographics($assessments),
            'education' => $this->getEducationData($assessments),
            'housing' => $this->getHousingData($assessments),
            'health' => $this->getHealthData($assessments),
            'training' => $this->getTrainingData($assessments),
            'problems' => $this->getProblemsData($assessments),
            'organization' => $this->getOrganizationData($assessments),
            'serviceRatings' => $this->getServiceRatings($assessments),
        ];
    }

    private function getDemographics($assessments)
    {
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0,
        ];

        foreach ($assessments as $a) {
            $age = $a->respondent_age;
            if ($age <= 25) $ageGroups['18-25']++;
            elseif ($age <= 35) $ageGroups['26-35']++;
            elseif ($age <= 45) $ageGroups['36-45']++;
            elseif ($age <= 55) $ageGroups['46-55']++;
            elseif ($age <= 65) $ageGroups['56-65']++;
            else $ageGroups['65+']++;
        }

        return $ageGroups;
    }

    private function getEducationData($assessments)
    {
        $education = [];
        foreach ($assessments as $a) {
            if ($a->respondent_educational_attainment) {
                $levels = json_decode($a->respondent_educational_attainment, true) ?? [];
                foreach ($levels as $level) {
                    $education[$level] = ($education[$level] ?? 0) + 1;
                }
            }
        }
        return $education;
    }

    private function getHousingData($assessments)
    {
        $houseTypes = [];
        $electricity = [
            'Yes' => $assessments->where('has_electricity', 'Yes')->count(),
            'No' => $assessments->where('has_electricity', 'No')->count(),
        ];
        $toilet = [
            'Yes' => $assessments->where('has_own_toilet', 'Yes')->count(),
            'No' => $assessments->where('has_own_toilet', 'No')->count(),
        ];

        foreach ($assessments as $a) {
            if ($a->house_type) {
                $types = json_decode($a->house_type, true) ?? [];
                foreach ($types as $type) {
                    $houseTypes[$type] = ($houseTypes[$type] ?? 0) + 1;
                }
            }
        }

        return [
            'houseTypes' => $houseTypes,
            'electricity' => $electricity,
            'hasToilet' => $toilet,
        ];
    }

    private function getHealthData($assessments)
    {
        $illnesses = [];
        $healthProblems = [];

        foreach ($assessments as $a) {
            if ($a->common_illnesses) {
                $items = json_decode($a->common_illnesses, true) ?? [];
                foreach ($items as $item) {
                    $illnesses[$item] = ($illnesses[$item] ?? 0) + 1;
                }
            }
            if ($a->health_problems) {
                $items = json_decode($a->health_problems, true) ?? [];
                foreach ($items as $item) {
                    $healthProblems[$item] = ($healthProblems[$item] ?? 0) + 1;
                }
            }
        }

        arsort($illnesses);
        arsort($healthProblems);

        return [
            'commonIllnesses' => array_slice($illnesses, 0, 5),
            'healthProblems' => array_slice($healthProblems, 0, 5),
        ];
    }

    private function getTrainingData($assessments)
    {
        return [
            'interested' => $assessments->where('interested_in_livelihood_training', 'Yes')->count(),
            'notInterested' => $assessments->where('interested_in_livelihood_training', 'No')->count(),
            'availableForTraining' => $assessments->where('available_for_training', 'Yes')->count(),
            'notAvailable' => $assessments->where('available_for_training', 'No')->count(),
        ];
    }

    private function getProblemsData($assessments)
    {
        $problemTypes = [
            'family_problems' => 'Family',
            'health_problems' => 'Health',
            'educational_problems' => 'Educational',
            'employment_problems' => 'Employment',
            'infrastructure_problems' => 'Infrastructure',
            'economic_problems' => 'Economic',
            'security_problems' => 'Security',
        ];

        $problemCounts = [];

        foreach ($problemTypes as $field => $label) {
            $count = 0;
            foreach ($assessments as $a) {
                if ($a->$field) {
                    $items = json_decode($a->$field, true) ?? [];
                    if (!empty($items)) {
                        $count++;
                    }
                }
            }
            $problemCounts[$label] = $count;
        }

        return $problemCounts;
    }

    private function getOrganizationData($assessments)
    {
        return [
            'Member' => $assessments->where('member_of_organization', 'Yes')->count(),
            'NonMember' => $assessments->where('member_of_organization', 'No')->count(),
        ];
    }

    private function getServiceRatings($assessments)
    {
        $services = [
            'Law Enforcement',
            'Fire Protection',
            'Health Service',
            'Education Service',
            'Infrastructure Service',
        ];

        $avgRatings = [];

        foreach ($services as $service) {
            $ratings = [];
            foreach ($assessments as $a) {
                if ($a->barangay_service_ratings) {
                    $allRatings = json_decode($a->barangay_service_ratings, true) ?? [];
                    if (isset($allRatings[$service])) {
                        $ratings[] = $allRatings[$service];
                    }
                }
            }
            $avgRatings[$service] = !empty($ratings) ? round(array_sum($ratings) / count($ratings), 1) : 0;
        }

        return $avgRatings;
    }
}

