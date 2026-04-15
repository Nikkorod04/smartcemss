<?php

namespace App\Services;

use App\Models\Community;
use App\Models\AssessmentSummary;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class CommunityAnalysisService
{
    /**
     * Generate AI analysis for a community's assessment data
     * This creates a summary of findings and proposes tailored interventions
     */
    public function generateAnalysis(Community $community, string $quarter, int $year): ?AssessmentSummary
    {
        try {
            Log::info('CommunityAnalysisService: Starting AI analysis generation', [
                'community_id' => $community->id,
                'community_name' => $community->name,
                'quarter' => $quarter,
                'year' => $year,
            ]);

            // Get or create summary
            $summary = AssessmentSummary::where('community_id', $community->id)
                ->where('quarter', $quarter)
                ->where('year', $year)
                ->first();

            if (!$summary) {
                Log::warning('CommunityAnalysisService: Summary not found, creating from assessments', [
                    'community_id' => $community->id,
                ]);
                $summary = AssessmentSummary::calculateForCommunity($community, $quarter, $year);
            }

            // Build formatted data for the prompt
            $formattedData = $this->formatCommunityData($community, $summary, $quarter, $year);

            // Create the prompt
            $prompt = $this->buildAnalysisPrompt($community, $formattedData, $quarter, $year);

            // Call ChatGPT API
            Log::info('CommunityAnalysisService: Calling ChatGPT API');

            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'temperature' => 0.7,
                'max_tokens' => 3000,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert community development analyst. Analyze community assessment data and provide insightful, actionable recommendations tailored to the specific context and data provided.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            $analysisText = $response->choices[0]->message->content;

            Log::info('CommunityAnalysisService: ChatGPT response received', [
                'response_length' => strlen($analysisText),
            ]);

            // Parse interventions from the response
            $interventions = $this->parseInterventions($analysisText);

            // Parse sections for card display
            $sections = $this->parseAnalysisSections($analysisText);

            // Store in database
            $summary->ai_analysis = $analysisText;
            $summary->ai_interventions = $interventions;
            $summary->ai_analysis_sections = $sections;
            $summary->ai_analysis_generated_at = now();
            $summary->save();

            Log::info('CommunityAnalysisService: Analysis saved to database', [
                'summary_id' => $summary->id,
                'interventions_count' => count($interventions),
            ]);

            return $summary;

        } catch (\Exception $e) {
            Log::error('CommunityAnalysisService: Error generating analysis', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            return null;
        }
    }

    /**
     * Format community assessment data into readable format for AI
     */
    private function formatCommunityData(Community $community, AssessmentSummary $summary, string $quarter, int $year): array
    {
        $assessments = $community->needsAssessments()
            ->where('quarter', $quarter)
            ->where('year', $year)
            ->get();

        return [
            'community_name' => $community->name,
            'municipality' => $community->municipality,
            'province' => $community->province,
            'quarter' => $quarter,
            'year' => $year,
            'total_responses' => $summary->total_responses,
            'demographics' => [
                'gender' => $summary->gender_distribution,
                'religion' => $summary->religion_distribution,
                'education' => $summary->education_distribution,
                'civil_status' => $summary->civil_status_distribution,
                'age_groups' => $this->calculateAgeGroups($assessments),
            ],
            'health_status' => [
                'common_illnesses' => $summary->health_problems,
                'water_sources' => $summary->water_sources,
                'toilet_access' => $this->calculateToiletAccess($assessments),
                'electricity' => round($summary->electricity_access_percentage, 0) . '%',
            ],
            'housing' => [
                'house_types' => $summary->house_types,
                'tenure_status' => $this->calculateTenureStatus($assessments),
            ],
            'education' => [
                'interests' => $summary->educational_interests,
                'studying_percentage' => $this->calculateStudyingPercentage($assessments) . '%',
            ],
            'livelihood' => [
                'options' => $summary->livelihood_interests,
                'training_interest' => $this->calculateTrainingInterest($assessments) . '%',
            ],
            'problems_identified' => [
                'health' => $summary->health_problems,
                'family' => $summary->family_problems,
                'employment' => $summary->employment_problems,
                'infrastructure' => $summary->infrastructure_problems,
                'economic' => $summary->economic_problems,
                'security' => $summary->security_problems,
            ],
            'service_satisfaction' => round($summary->avg_service_satisfaction ?? 0, 1) . '/5',
        ];
    }

    /**
     * Build the prompt for ChatGPT analysis
     */
    private function buildAnalysisPrompt(Community $community, array $data, string $quarter, int $year): string
    {
        $problemsJson = json_encode($data['problems_identified'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $demographicsJson = json_encode($data['demographics'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
You are analyzing community assessment data for {$data['community_name']}, {$data['municipality']}, {$data['province']}.

PERIOD: {$quarter} {$year}
TOTAL RESPONSES: {$data['total_responses']} community members surveyed

=== DEMOGRAPHICS ===
{$demographicsJson}

=== HEALTH & SANITATION ===
Common Illnesses: {$this->formatArray($data['health_status']['common_illnesses'])}
Water Sources: {$this->formatArray($data['health_status']['water_sources'])}
Toilet Access: {$data['health_status']['toilet_access']}
Electricity Access: {$data['health_status']['electricity']}

=== HOUSING & BASIC AMENITIES ===
House Types: {$this->formatArray($data['housing']['house_types'])}
Land Tenure Status: {$data['housing']['tenure_status']}

=== EDUCATION ===
Educational Interests: {$this->formatArray($data['education']['interests'])}
Household Members Currently Studying: {$data['education']['studying_percentage']}

=== LIVELIHOOD & ECONOMIC ===
Livelihood Options: {$this->formatArray($data['livelihood']['options'])}
Interest in Training: {$data['livelihood']['training_interest']}

=== IDENTIFIED PROBLEMS & NEEDS ===
{$problemsJson}

=== SERVICE SATISFACTION ===
Average Rating: {$data['service_satisfaction']} out of 5

=== YOUR ANALYSIS TASK ===
Based on this data, provide a comprehensive community analysis and recommendations. Structure your response as follows:

1. **EXECUTIVE SUMMARY** (2-3 sentences): Key findings about this community's current state and primary challenges.

2. **TOP 3-5 PRIORITY ISSUES** (with severity levels: CRITICAL, HIGH, MEDIUM, LOW):
   - Issue name
   - Why it's important
   - Current impact on the community
   - Data evidence from the survey

3. **ROOT CAUSES ANALYSIS**:
   - What underlying factors are causing these issues?
   - Connection to infrastructure, access, education, or economic factors

4. **COMMUNITY STRENGTHS & ASSETS**:
   - What is the community doing well?
   - What existing resources can be leveraged?

5. **PROPOSED INTERVENTIONS** (realistic, actionable, tailored to this community):
   List 4-6 specific programs/interventions with:
   - Intervention name
   - Target beneficiaries (e.g., "40 farmers", "150 school-age children")
   - Expected outcomes
   - Timeline (3 months, 6 months, 1 year)
   - Resources needed
   - Success metrics
   - How it addresses the priority issues

6. **IMPLEMENTATION ROADMAP**:
   - Phase 1 (Months 1-3): Quick wins and foundation
   - Phase 2 (Months 4-6): Main programs
   - Phase 3 (Months 7-12): Scaling and sustainability

7. **EXPECTED Community IMPACT**:
   - What will change for residents in 6-12 months if interventions are implemented?
   - Quantifiable targets where possible

Remember to:
- Base all recommendations on the actual survey data provided
- Be specific to this community's context and challenges
- Propose realistic interventions that don't require massive external resources
- Consider local capacities and existing infrastructure
- Focus on sustainable, community-driven solutions
PROMPT;
    }

    /**
     * Parse interventions from ChatGPT response
     */
    private function parseInterventions(string $response): array
    {
        // Extract the "PROPOSED INTERVENTIONS" section
        $interventions = [];

        // Look for numbered items or bullet points in the interventions section
        preg_match('/PROPOSED INTERVENTIONS.*?(?=^[0-9]+\.|^##|IMPLEMENTATION ROADMAP|$)/ims', $response, $matches);

        if (!empty($matches[0])) {
            $section = $matches[0];
            // Split by lines to extract individual interventions
            $lines = explode("\n", $section);

            $current_intervention = [];
            foreach ($lines as $line) {
                $line = trim($line);
                
                // Look for numbered or bulleted items
                if (preg_match('/^[\d\-\*]+\.\s+(.+)$/i', $line, $match)) {
                    // Save previous intervention if exists
                    if (!empty($current_intervention['title'])) {
                        $interventions[] = $current_intervention;
                    }
                    // Start new intervention
                    $current_intervention = [
                        'title' => $match[1],
                        'details' => [],
                    ];
                } elseif (!empty($line) && !empty($current_intervention['title'])) {
                    $current_intervention['details'][] = $line;
                }
            }

            // Add last intervention
            if (!empty($current_intervention['title'])) {
                $interventions[] = $current_intervention;
            }
        }

        // If parsing didn't work well, return structured summary
        if (empty($interventions)) {
            $interventions[] = [
                'title' => 'See full analysis below',
                'details' => [substr($response, 0, 500) . '...'],
            ];
        }

        return $interventions;
    }

    /**
     * Helper: Format array data for display
     */
    private function formatArray($data): string
    {
        if (empty($data)) {
            return 'No data';
        }

        if (is_array($data)) {
            if (is_assoc($data)) {
                $items = [];
                foreach ($data as $key => $value) {
                    $items[] = "$key: $value";
                }
                return implode(', ', $items);
            } else {
                return implode(', ', $data);
            }
        }

        return (string)$data;
    }

    /**
     * Helper: Calculate age groups from assessments
     */
    private function calculateAgeGroups($assessments): array
    {
        $groups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0,
        ];

        foreach ($assessments as $a) {
            $age = $a->respondent_age;
            if ($age && $age <= 25) $groups['18-25']++;
            elseif ($age && $age <= 35) $groups['26-35']++;
            elseif ($age && $age <= 45) $groups['36-45']++;
            elseif ($age && $age <= 55) $groups['46-55']++;
            elseif ($age && $age <= 65) $groups['56-65']++;
            elseif ($age) $groups['65+']++;
        }

        return $groups;
    }

    private function calculateToiletAccess($assessments): string
    {
        $withToilet = $assessments->where('has_own_toilet', 'Yes')->count();
        $total = $assessments->count();
        $percentage = $total > 0 ? round(($withToilet / $total) * 100) : 0;
        return "$percentage% have own toilet";
    }

    private function calculateTenureStatus($assessments): string
    {
        $owned = 0;
        $rented = 0;
        foreach ($assessments as $a) {
            if ($a->tenure_status) {
                $statuses = is_array($a->tenure_status) ? $a->tenure_status : json_decode($a->tenure_status, true) ?? [];
                if (in_array('Owned', $statuses)) $owned++;
                if (in_array('Rented', $statuses)) $rented++;
            }
        }
        $total = $assessments->count();
        return "Owned: " . ($total > 0 ? round(($owned / $total) * 100) : 0) . "%, Rented: " . ($total > 0 ? round(($rented / $total) * 100) : 0) . "%";
    }

    private function calculateStudyingPercentage($assessments): int
    {
        $studying = $assessments->where('household_member_currently_studying', 'Yes')->count();
        $total = $assessments->count();
        return $total > 0 ? round(($studying / $total) * 100) : 0;
    }

    private function calculateTrainingInterest($assessments): int
    {
        $interested = $assessments->where('interested_in_livelihood_training', 'Yes')->count();
        $total = $assessments->count();
        return $total > 0 ? round(($interested / $total) * 100) : 0;
    }

    /**
     * Parse analysis into 7 distinct sections for card display
     */
    private function parseAnalysisSections(string $response): array
    {
        $sections = [];

        // Split by section headers (numbered 1-7)
        // This captures both the section number and the content after it
        $sectionSplit = preg_split('/\n\s*(?:#+\s*)?(\d)[\.\)]\s+/i', $response, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        // First part might be preamble or whitespace, find where section 1 actually starts
        $startIndex = 1; // Default: section 1 is at index 1
        
        // If there's a part 0 and it starts with section 1, we're good
        // Otherwise section 1 content is at index 2
        if (isset($sectionSplit[0]) && preg_match('/^\s*1[\.\)]/i', $sectionSplit[0])) {
            $startIndex = 0;
        }

        // Map section numbers to keys
        $sectionMap = [
            '1' => 'executive_summary',
            '2' => 'priority_issues',
            '3' => 'root_causes',
            '4' => 'strengths_assets',
            '5' => 'proposed_interventions',
            '6' => 'implementation_roadmap',
            '7' => 'community_impact',
        ];

        // Process sections from the split array
        for ($i = $startIndex; $i < count($sectionSplit); $i += 2) {
            // $sectionSplit[$i] = section number
            // $sectionSplit[$i+1] = section content
            
            if ($i + 1 >= count($sectionSplit)) {
                break;
            }

            $sectionNum = trim($sectionSplit[$i]);
            $sectionContent = trim($sectionSplit[$i + 1] ?? '');

            if (empty($sectionNum) || empty($sectionContent) || $sectionNum > 7) {
                continue;
            }

            $sectionKey = $sectionMap[$sectionNum] ?? null;
            
            if ($sectionKey) {
                // Remove the section title from content (it might be duplicated)
                $sectionContent = preg_replace('/^[A-Z\s\d\.&]+\n/i', '', $sectionContent, 1);
                $sectionContent = trim($sectionContent);

                $sections[$sectionKey] = [
                    'title' => $this->getTitleForSection($sectionKey),
                    'content' => $sectionContent,
                ];
            }
        }

        // If we couldn't parse sections, return array with full analysis
        if (empty($sections)) {
            $sections['full_analysis'] = [
                'title' => 'Community Analysis',
                'content' => $response,
            ];
        }

        return $sections;
    }

    /**
     * Map section number to section key
     */
    private function mapSectionNumber(string $num): string
    {
        $map = [
            '1' => 'executive_summary',
            '2' => 'priority_issues',
            '3' => 'root_causes',
            '4' => 'strengths_assets',
            '5' => 'proposed_interventions',
            '6' => 'implementation_roadmap',
            '7' => 'community_impact',
        ];

        return $map[$num] ?? 'unknown_section_' . $num;
    }

    /**
     * Get human-readable title for section key
     */
    private function getTitleForSection(string $key): string
    {
        $titles = [
            'executive_summary' => '1. Executive Summary',
            'priority_issues' => '2. Top Priority Issues',
            'root_causes' => '3. Root Causes Analysis',
            'strengths_assets' => '4. Community Strengths & Assets',
            'proposed_interventions' => '5. Proposed Interventions',
            'implementation_roadmap' => '6. Implementation Roadmap',
            'community_impact' => '7. Expected Community Impact',
            'full_analysis' => 'Community Analysis',
        ];

        return $titles[$key] ?? ucwords(str_replace('_', ' ', $key));
    }
}

/**
 * Check if array is associative
 */
function is_assoc($arr): bool
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}
