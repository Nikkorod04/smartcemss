<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\NeedsAssessment;
use App\Models\AssessmentAnalysis;

/**
 * ChatGPT Vision Extraction Service
 * Uses OpenAI's GPT-4 Vision API to extract form fields and analyze needs assessments
 */
class ChatGPTExtractionService
{
    private const ASSESSMENT_FORM_FIELDS = [
        // SECTION I: Identifying Information
        'respondent_first_name', 'respondent_middle_name', 'respondent_last_name',
        'respondent_age', 'respondent_sex', 'respondent_civil_status', 'respondent_religion',
        'respondent_educational_attainment',
        
        // SECTION II: Family Composition
        'family_adults', 'family_children', 'total_household_members',
        
        // SECTION III: Economic
        'livelihood_options', 'monthly_income', 'interested_in_livelihood_training', 'desired_training',
        
        // SECTION IV: Educational
        'barangay_educational_facilities', 'household_member_currently_studying',
        'interested_in_continuing_studies', 'areas_of_educational_interest',
        'preferred_training_time', 'preferred_training_days',
        
        // SECTION V: Health & Sanitation
        'common_illnesses', 'action_when_sick', 'barangay_medical_supplies_available',
        'has_barangay_health_programs', 'benefits_from_barangay_programs',
        'programs_benefited_from', 'water_source', 'water_source_distance',
        'garbage_disposal_method', 'has_own_toilet', 'toilet_type',
        'keeps_animals', 'animals_kept',
        
        // SECTION VI: Housing
        'house_type', 'tenure_status', 'has_electricity', 'light_source_without_power',
        'appliances_owned',
        
        // SECTION VII: Recreation & Organizations
        'barangay_recreational_facilities', 'use_of_free_time', 'member_of_organization',
        'organization_types', 'organization_meeting_frequency', 'position_in_organization',
        
        // SECTION VIII: Problem Identification
        'family_problems', 'health_problems', 'educational_problems',
        'employment_problems', 'infrastructure_problems', 'economic_problems',
        'security_problems',
        
        // SECTION IX: Services & Feedback
        'general_feedback', 'available_for_training', 'reason_not_available',
    ];

    public const ASSESSMENT_FORM_PROMPT = <<<'PROMPT'
You are an expert form data extraction specialist analyzing a community needs assessment form.

Your task is to:
1. **Extract all form fields** with their values from the document
2. **Identify problems/concerns** mentioned in the form
3. **Generate actionable recommendations** based on the assessment

## Form Structure:
- SECTION I: Identifying Information (name, age, sex, civil status, religion, education)
- SECTION II: Family Composition (household members, adults, children)
- SECTION III: Economic (livelihood, income, interest in training)
- SECTION IV: Educational (facilities, current students, interests, training preferences)
- SECTION V: Health & Sanitation (illnesses, water source, toilet type, waste disposal)
- SECTION VI: Housing (house type, tenure, electricity, appliances)
- SECTION VII: Recreation & Organizations (facilities, membership, activities)
- SECTION VIII: Problem Identification (main issues faced by household/community)
- SECTION IX: Services & Feedback (comments, training availability)

## Required Output (JSON):

```json
{
  "extracted_fields": {
    "respondent_first_name": "value",
    "respondent_last_name": "value",
    "respondent_age": "value",
    // ... all field values here
  },
  "problems_identified": [
    {
      "category": "health|economic|educational|housing|livelihood|infrastructure|other",
      "problem": "Description of the problem",
      "severity": "high|medium|low",
      "affects": "This household or whole community"
    }
  ],
  "recommendations": [
    {
      "category": "health|economic|educational|housing|livelihood|infrastructure|capacity_building",
      "recommendation": "Specific action to address the problem",
      "priority": "immediate|short_term|long_term",
      "implementation": "Which organization/office should implement this"
    }
  ],
  "summary": "Brief 2-3 sentence summary of the household/community situation",
  "confidence_score": 85
}
```

## Instructions:
- Extract values exactly as they appear in the form
- For missing fields, use null
- For checkboxes/multiple selections, use arrays
- Identify ACTUAL problems mentioned, not assumptions
- Provide realistic, actionable recommendations
- Only output valid JSON, nothing else
PROMPT;

    /**
     * Process a needs assessment file using ChatGPT vision API
     */
    public function processAssessmentFile(NeedsAssessment $assessment): AssessmentAnalysis
    {
        try {
            Log::info('ChatGPTExtractionService: Starting assessment analysis', [
                'assessment_id' => $assessment->id,
                'file_path' => $assessment->file_path,
            ]);

            // Convert file to base64
            $fileContent = Storage::disk('local')->get($assessment->file_path);
            $base64Content = base64_encode($fileContent);
            
            // Determine media type
            $extension = pathinfo($assessment->file_path, PATHINFO_EXTENSION);
            $mediaType = $this->getMediaType($extension);

            Log::info('ChatGPTExtractionService: File prepared', [
                'file_size' => strlen($fileContent),
                'media_type' => $mediaType,
            ]);

            // Call ChatGPT vision API
            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'max_tokens' => 4096,
                'temperature' => config('services.openai.temperature', 0.7),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => self::ASSESSMENT_FORM_PROMPT,
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:' . $mediaType . ';base64,' . $base64Content,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            $responseText = $response->choices[0]->message->content;
            
            Log::info('ChatGPTExtractionService: ✓ Response received', [
                'response_length' => strlen($responseText),
                'tokens_used' => $response->usage->totalTokens,
            ]);

            // Parse JSON response
            $analysisData = json_decode($responseText, true);
            
            if (!$analysisData) {
                throw new \Exception('Invalid JSON response from ChatGPT: ' . substr($responseText, 0, 200));
            }

            // Create assessment analysis record
            $analysis = AssessmentAnalysis::create([
                'needs_assessment_id' => $assessment->id,
                'raw_extracted_data' => $responseText,
                'extracted_fields' => json_encode($analysisData['extracted_fields'] ?? []),
                'problems_identified' => json_encode($analysisData['problems_identified'] ?? []),
                'recommendations' => json_encode($analysisData['recommendations'] ?? []),
                'summary' => $analysisData['summary'] ?? null,
                'confidence_score' => $analysisData['confidence_score'] ?? null,
                'status' => 'completed',
                'metadata' => json_encode([
                    'model' => config('services.openai.model'),
                    'tokens_used' => $response->usage->totalTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                    'prompt_tokens' => $response->usage->promptTokens,
                    'processing_time_ms' => microtime(true) * 1000,
                ]),
            ]);

            Log::info('ChatGPTExtractionService: ✓ Analysis completed', [
                'assessment_id' => $assessment->id,
                'fields_extracted' => count($analysisData['extracted_fields'] ?? []),
                'problems_found' => count($analysisData['problems_identified'] ?? []),
                'recommendations' => count($analysisData['recommendations'] ?? []),
            ]);

            return $analysis;
        } catch (\Exception $e) {
            Log::error('ChatGPTExtractionService: ✗ Processing failed', [
                'assessment_id' => $assessment->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Create failed analysis record
            $analysis = AssessmentAnalysis::create([
                'needs_assessment_id' => $assessment->id,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => json_encode([
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                ]),
            ]);

            throw $e;
        }
    }

    /**
     * Re-analyze an existing assessment
     */
    public function reanalyzeAssessment(AssessmentAnalysis $analysis): AssessmentAnalysis
    {
        $assessment = $analysis->needsAssessment;
        return $this->processAssessmentFile($assessment);
    }

    /**
     * Get extracted fields formatted for display
     */
    public function getExtractedFields(AssessmentAnalysis $analysis): array
    {
        return json_decode($analysis->extracted_fields ?? '{}', true);
    }

    /**
     * Get identified problems from analysis
     */
    public function getProblems(AssessmentAnalysis $analysis): array
    {
        return json_decode($analysis->problems_identified ?? '[]', true);
    }

    /**
     * Get recommendations from analysis
     */
    public function getRecommendations(AssessmentAnalysis $analysis): array
    {
        return json_decode($analysis->recommendations ?? '[]', true);
    }

    /**
     * Get problems grouped by category
     */
    public function getProblemsGroupedByCategory(AssessmentAnalysis $analysis): array
    {
        $problems = $this->getProblems($analysis);
        $grouped = [];
        
        foreach ($problems as $problem) {
            $category = $problem['category'] ?? 'other';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $problem;
        }
        
        return $grouped;
    }

    /**
     * Get recommendations grouped by category
     */
    public function getRecommendationsGroupedByCategory(AssessmentAnalysis $analysis): array
    {
        $recommendations = $this->getRecommendations($analysis);
        $grouped = [];
        
        foreach ($recommendations as $rec) {
            $category = $rec['category'] ?? 'other';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $rec;
        }
        
        return $grouped;
    }

    /**
     * Determine media type from file extension
     */
    private function getMediaType(string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }
}
