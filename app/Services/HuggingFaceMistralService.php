<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceMistralService
{
    protected $apiToken;
    protected $model;
    protected $baseUrl = 'https://api-inference.huggingface.co/models/';

    public function __construct()
    {
        $this->apiToken = config('services.huggingface.token');
        $this->model = config('services.huggingface.model');

        if (!$this->apiToken) {
            throw new \Exception('Hugging Face API token not configured');
        }
    }

    /**
     * Send a prompt to Mistral 7B and get structured JSON response
     * 
     * @param string $prompt The prompt to send
     * @return array Decoded JSON response
     */
    public function extractFormData(string $prompt): array
    {
        try {
            Log::info('Calling Mistral 7B for form extraction');

            $response = Http::withToken($this->apiToken)
                ->timeout(30)
                ->post($this->baseUrl . $this->model, [
                    'inputs' => $prompt,
                    'parameters' => [
                        'temperature' => 0.1,  // Low temperature for consistency
                        'top_p' => 0.9,
                        'max_new_tokens' => 2048,  // Allow for long JSON responses
                        'do_sample' => true,
                    ],
                ])
                ->throw()
                ->json();

            Log::info('Mistral 7B response received', ['response' => $response]);

            // Response format from Hugging Face:
            // [
            //   {
            //     "generated_text": "the full prompt + generated text"
            //   }
            // ]
            if (is_array($response) && isset($response[0]['generated_text'])) {
                $generatedText = $response[0]['generated_text'];

                // Extract JSON from the generated text
                // The LLM will output the full prompt + JSON response
                $jsonMatch = $this->extractJsonFromText($generatedText);

                if ($jsonMatch) {
                    $extracted = json_decode($jsonMatch, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        Log::info('Successfully extracted form data from LLM response');
                        return $extracted;
                    }
                }

                Log::warning('Could not parse JSON from LLM response', [
                    'generated_text' => substr($generatedText, 0, 500),
                ]);
                return [];
            }

            Log::error('Unexpected response format from Mistral', ['response' => $response]);
            return [];

        } catch (\Exception $e) {
            Log::error('Mistral 7B API call failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract JSON object from text that contains a prompt + generated response
     * 
     * @param string $text
     * @return string|null
     */
    protected function extractJsonFromText(string $text): ?string
    {
        // Look for JSON object pattern: {...}
        // Start from first { and find matching }
        $start = strpos($text, '{');

        if ($start === false) {
            return null;
        }

        $braceCount = 0;
        $inString = false;
        $escapeNext = false;

        for ($i = $start; $i < strlen($text); $i++) {
            $char = $text[$i];

            if ($escapeNext) {
                $escapeNext = false;
                continue;
            }

            if ($char === '\\') {
                $escapeNext = true;
                continue;
            }

            if ($char === '"' && !$escapeNext) {
                $inString = !$inString;
                continue;
            }

            if (!$inString) {
                if ($char === '{') {
                    $braceCount++;
                } elseif ($char === '}') {
                    $braceCount--;

                    if ($braceCount === 0) {
                        // Found the closing brace
                        return substr($text, $start, $i - $start + 1);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Build extraction prompt with field definitions
     * 
     * @param string $ocrText Combined OCR text from all PDF images
     * @param array $fieldDefinitions Field specifications
     * @return string
     */
    public function buildExtractionPrompt(string $ocrText, array $fieldDefinitions = []): string
    {
        // Default field definitions if none provided
        if (empty($fieldDefinitions)) {
            $fieldDefinitions = $this->getDefaultFieldDefinitions();
        }

        $fieldsJson = json_encode($fieldDefinitions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
You are a form data extraction expert. Your task is to extract structured data from OCR text of a community needs assessment form.

IMPORTANT INSTRUCTIONS:
1. Extract ONLY the fields specified below
2. Remove any image markers like "--- Image X ---" or "BAGONG PILI"
3. For Yes/No fields, respond with exactly "Yes" or "No"
4. For rating fields, extract as integers 1-5, NOT comma-separated strings
5. For array fields (checkboxes), return as JSON array
6. For empty fields or fields not found, return null
7. For service ratings with 8 values like "3, 3, 3, 4, 2, 3, 5, 3", parse as array [3,3,3,4,2,3,5,3]
8. Respond ONLY with valid JSON, no additional text

OCR TEXT FROM FORM:
---
{$ocrText}
---

FIELD DEFINITIONS (extract these fields):
{$fieldsJson}

RESPONSE FORMAT:
Return ONLY a valid JSON object matching the field definitions above. Example for your fields:
{
  "respondent_first_name": "John",
  "respondent_age": 35,
  "has_barangay_health_programs": "Yes",
  "barangay_service_ratings": [3, 3, 3, 4, 2, 3, 5, 3],
  "security_problems": null,
  "keeps_animals": "No",
  "general_feedback": 3
}

Extract and respond:
PROMPT;
    }

    /**
     * Default field definitions for the needs assessment form
     * 
     * @return array
     */
    protected function getDefaultFieldDefinitions(): array
    {
        return [
            'respondent_first_name' => [
                'type' => 'string',
                'description' => 'First name of respondent',
            ],
            'respondent_middle_name' => [
                'type' => 'string',
                'description' => 'Middle name of respondent',
            ],
            'respondent_last_name' => [
                'type' => 'string',
                'description' => 'Last name of respondent',
            ],
            'respondent_age' => [
                'type' => 'integer',
                'description' => 'Age of respondent (18-120)',
            ],
            'respondent_civil_status' => [
                'type' => 'string',
                'enum' => ['Single', 'Married', 'Widowed', 'Divorced'],
                'description' => 'Civil status',
            ],
            'respondent_sex' => [
                'type' => 'string',
                'enum' => ['Male', 'Female'],
                'description' => 'Gender',
            ],
            'has_barangay_health_programs' => [
                'type' => 'boolean_string',
                'enum' => ['Yes', 'No'],
                'description' => 'Has barangay health programs',
            ],
            'barangay_service_ratings' => [
                'type' => 'array',
                'items' => 'integer',
                'description' => 'Array of 8 service ratings (1-5 scale) in order: law enforcement, fire protection, BNS service, street lighting, water system, sanitation, health service, education service',
            ],
            'available_for_training' => [
                'type' => 'boolean_string',
                'enum' => ['Yes', 'No'],
                'description' => 'Available for training',
            ],
            'security_problems' => [
                'type' => 'string',
                'description' => 'Security problems (remove image markers)',
            ],
            'general_feedback' => [
                'type' => 'integer',
                'description' => 'General feedback rating',
            ],
            'keeps_animals' => [
                'type' => 'boolean_string',
                'enum' => ['Yes', 'No'],
                'description' => 'Keeps animals',
            ],
        ];
    }
}
