<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LLMFormExtractor
{
    protected $mistral;

    public function __construct(HuggingFaceMistralService $mistral)
    {
        $this->mistral = $mistral;
    }

    /**
     * Extract form data from combined OCR text using Mistral 7B LLM
     * 
     * @param string $combinedOcrText Combined text from all PDF images
     * @return array Extracted and structured field data
     */
    public function extractFormData(string $combinedOcrText): array
    {
        try {
            if (empty(trim($combinedOcrText))) {
                Log::warning('Empty OCR text provided to LLM extraction');
                return [];
            }

            Log::info('Starting LLM-based form extraction', [
                'text_length' => strlen($combinedOcrText),
            ]);

            // Build the extraction prompt
            $prompt = $this->mistral->buildExtractionPrompt($combinedOcrText);

            // Call Mistral 7B
            $extracted = $this->mistral->extractFormData($prompt);

            Log::info('LLM extraction completed', [
                'fields_extracted' => count($extracted),
                'data' => $extracted,
            ]);

            return $extracted;

        } catch (\Exception $e) {
            Log::error('LLM form extraction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Process raw LLM output into assessment database format
     * Converts LLM field names to database field names and validates data
     * 
     * @param array $llmData Raw output from LLM
     * @return array Database-ready assessment data
     */
    public function processLLMOutput(array $llmData): array
    {
        $processedData = [];

        // Map LLM field names to database field names
        $fieldMapping = [
            'respondent_first_name' => 'first_name',
            'respondent_middle_name' => 'middle_name',
            'respondent_last_name' => 'last_name',
            'respondent_age' => 'age',
            'respondent_civil_status' => 'civil_status',
            'respondent_sex' => 'sex',
            'respondent_religion' => 'religion',
            'respondent_educational_attainment' => 'educational_attainment',
            'family_adults' => 'adults',
            'family_children' => 'children',
            'has_barangay_health_programs' => 'has_barangay_health_programs',
            'barangay_service_ratings' => 'barangay_service_ratings',
            'available_for_training' => 'available_for_training',
            'security_problems' => 'security_problems',
            'general_feedback' => 'general_feedback',
            'keeps_animals' => 'keeps_animals',
        ];

        foreach ($fieldMapping as $llmField => $dbField) {
            if (isset($llmData[$llmField])) {
                $value = $llmData[$llmField];

                // Skip null values
                if ($value === null) {
                    continue;
                }

                // Validate based on field type
                if (!$this->validateFieldValue($dbField, $value)) {
                    Log::warning("Field validation failed: {$dbField}", [
                        'value' => $value,
                    ]);
                    continue;
                }

                $processedData[$dbField] = $value;
            }
        }

        // Handle any additional fields from LLM that we want to keep
        foreach ($llmData as $field => $value) {
            if (!in_array($field, array_keys($fieldMapping)) && $value !== null) {
                // Store unmapped fields with llm_ prefix
                $processedData['llm_' . $field] = $value;
            }
        }

        return $processedData;
    }

    /**
     * Validate field values based on field type
     * 
     * @param string $fieldName Database field name
     * @param mixed $value Value to validate
     * @return bool
     */
    protected function validateFieldValue(string $fieldName, $value): bool
    {
        // Null is always invalid (should have been filtered earlier)
        if ($value === null) {
            return false;
        }

        switch ($fieldName) {
            // Boolean fields should be "Yes" or "No"
            case 'has_barangay_health_programs':
            case 'available_for_training':
            case 'keeps_animals':
                return in_array($value, ['Yes', 'No']);

            // Age should be integer 18-120
            case 'age':
                return is_int($value) && $value >= 18 && $value <= 120;

            // Service ratings should be array of 1-5 values or single integer
            case 'barangay_service_ratings':
                if (is_array($value)) {
                    return count($value) === 8 && 
                           array_reduce($value, fn($carry, $rating) => 
                               $carry && is_int($rating) && $rating >= 1 && $rating <= 5, true);
                }
                return is_int($value) && $value >= 1 && $value <= 5;

            // General feedback should be 1-5 integer
            case 'general_feedback':
                return is_int($value) && $value >= 1 && $value <= 5;

            // String fields
            case 'first_name':
            case 'middle_name':
            case 'last_name':
            case 'civil_status':
            case 'sex':
            case 'religion':
            case 'educational_attainment':
            case 'security_problems':
                return is_string($value) && strlen($value) > 0;

            // Numeric fields
            case 'adults':
            case 'children':
                return is_int($value) && $value >= 0;

            default:
                // Accept string values for unknown fields
                return is_string($value) || is_int($value) || is_array($value);
        }
    }
}
