<?php

namespace App\Livewire;

use App\Models\NeedsAssessment;
use App\Models\Community;
use Livewire\Component;

class AssessmentSummaryCharts extends Component
{
    public $community = null;
    public $quarter = null;
    public $year = 2025;
    public $assessments = [];
    public $summary = [];

    public function mount($communityId = null, $quarter = null)
    {
        $this->community = $communityId ? Community::find($communityId) : Community::where('name', 'Anibong')->first();
        $this->quarter = $quarter;
        $this->year = 2025;
        
        $this->loadData();
    }

    public function loadData()
    {
        $query = NeedsAssessment::query();
        
        if ($this->community) {
            $query->where('community_id', $this->community->id);
        }
        
        if ($this->quarter) {
            $query->where('quarter', $this->quarter);
        }
        
        if ($this->year) {
            $query->where('year', $this->year);
        }

        $this->assessments = $query->get();
        $this->summary = $this->generateSummary();
    }

    private function generateSummary()
    {
        if ($this->assessments->isEmpty()) {
            return [];
        }

        return [
            'demographics' => $this->getDemographics(),
            'education' => $this->getEducationData(),
            'housing' => $this->getHousingData(),
            'health' => $this->getHealthData(),
            'training' => $this->getTrainingData(),
            'problems' => $this->getProblemsData(),
            'organization' => $this->getOrganizationData(),
            'serviceRatings' => $this->getServiceRatings(),
        ];
    }

    private function getDemographics()
    {
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0,
        ];

        foreach ($this->assessments as $a) {
            $age = $a->respondent_age;
            if ($age <= 25) $ageGroups['18-25']++;
            elseif ($age <= 35) $ageGroups['26-35']++;
            elseif ($age <= 45) $ageGroups['36-45']++;
            elseif ($age <= 55) $ageGroups['46-55']++;
            elseif ($age <= 65) $ageGroups['56-65']++;
            else $ageGroups['65+']++;
        }

        return [
            'ageGroups' => $ageGroups,
            'avgAge' => round($this->assessments->avg('respondent_age'), 1),
        ];
    }

    private function getEducationData()
    {
        $education = [];
        foreach ($this->assessments as $a) {
            if ($a->respondent_educational_attainment) {
                $levels = json_decode($a->respondent_educational_attainment, true) ?? [];
                foreach ($levels as $level) {
                    $education[$level] = ($education[$level] ?? 0) + 1;
                }
            }
        }
        return $education;
    }

    private function getHousingData()
    {
        $houseTypes = [];
        $electricity = [
            'Yes' => $this->assessments->where('has_electricity', 'Yes')->count(),
            'No' => $this->assessments->where('has_electricity', 'No')->count(),
        ];
        $toilet = [
            'Yes' => $this->assessments->where('has_own_toilet', 'Yes')->count(),
            'No' => $this->assessments->where('has_own_toilet', 'No')->count(),
        ];

        foreach ($this->assessments as $a) {
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

    private function getHealthData()
    {
        $illnesses = [];
        $healthProblems = [];

        foreach ($this->assessments as $a) {
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

    private function getTrainingData()
    {
        return [
            'interested' => $this->assessments->where('interested_in_livelihood_training', 'Yes')->count(),
            'notInterested' => $this->assessments->where('interested_in_livelihood_training', 'No')->count(),
            'availableForTraining' => $this->assessments->where('available_for_training', 'Yes')->count(),
            'notAvailable' => $this->assessments->where('available_for_training', 'No')->count(),
        ];
    }

    private function getProblemsData()
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
            foreach ($this->assessments as $a) {
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

    private function getOrganizationData()
    {
        return [
            'Member' => $this->assessments->where('member_of_organization', 'Yes')->count(),
            'NonMember' => $this->assessments->where('member_of_organization', 'No')->count(),
        ];
    }

    private function getServiceRatings()
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
            foreach ($this->assessments as $a) {
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

    public function render()
    {
        return view('livewire.assessment-summary-charts');
    }
}
