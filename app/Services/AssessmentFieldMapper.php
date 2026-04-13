<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AssessmentFieldMapper
{
    protected $llmExtractor;
    protected $documentAIService;

    public function __construct(LLMFormExtractor $llmExtractor = null, GoogleDocumentAIService $documentAIService = null)
    {
        $this->llmExtractor = $llmExtractor;
        $this->documentAIService = $documentAIService;
    }

    /**
     * Map extracted data from document to form fields
     */
    public function mapDataToFields(array $extractedData, string $sourceType = 'excel'): array
    {
        $mappedFields = [];
        
        Log::info('mapDataToFields called', [
            'sourceType' => $sourceType,
            'extractedDataKeys' => array_keys($extractedData),
            'textLength' => strlen($extractedData['text'] ?? ''),
            'useDocumentAI' => config('app.use_document_ai'),
        ]);

        if ($sourceType === 'excel' || $sourceType === 'csv') {
            // For structured data (Excel/CSV), map the first row to fields
            $firstRow = $extractedData['data'][0] ?? [];
            $mappedFields = $this->mapStructuredData($firstRow);
        } elseif ($sourceType === 'pdf' || $sourceType === 'image' || $sourceType === 'images') {
            // For unstructured text (PDF/Image), try extractors in order of preference
            
            // Try 1: Google Document AI (if enabled and service available)
            if (config('app.use_document_ai') && $this->documentAIService) {
                Log::info('Attempting Document AI extraction');
                try {
                    // Document AI expects file path, not raw text
                    // For this to work, we need the file path from the extraction process
                    if (isset($extractedData['file_path'])) {
                        $docAiResult = $this->documentAIService->processDocument($extractedData['file_path']);
                        if ($docAiResult['success']) {
                            Log::info('Document AI extraction succeeded');
                            $mappedFields = $docAiResult['extracted_data'] ?? [];
                        } else {
                            Log::warning('Document AI extraction failed', [
                                'error' => $docAiResult['error'] ?? 'unknown',
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Document AI extraction error', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Try 2: LLM extraction (fallback if Document AI not available or failed)
            if (empty($mappedFields) && config('app.use_llm_extraction') && $this->llmExtractor) {
                Log::info('Falling back to LLM extraction');
                try {
                    $text = $extractedData['text'] ?? $extractedData['raw_text'] ?? '';
                    $llmData = $this->llmExtractor->extractFormData($text);
                    $mappedFields = $this->llmExtractor->processLLMOutput($llmData);
                } catch (\Exception $e) {
                    Log::error('LLM extraction failed', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Try 3: Regex extraction (final fallback)
            if (empty($mappedFields)) {
                Log::info('Using regex-based extraction (fallback)');
                $text = $extractedData['text'] ?? $extractedData['raw_text'] ?? '';
                $mappedFields = $this->mapUnstructuredText($text);
            }
        }

        return $mappedFields;
    }

    /**
     * Map structured data (Excel/CSV rows) to form fields
     */
    protected function mapStructuredData(array $row): array
    {
        $fieldMap = [
            // SECTION I: Respondent Information
            'first_name' => ['respondent_first_name'],
            'firstname' => ['respondent_first_name'],
            'fname' => ['respondent_first_name'],
            'respondent_first_name' => ['respondent_first_name'],
            'middle_name' => ['respondent_middle_name'],
            'middlename' => ['respondent_middle_name'],
            'mname' => ['respondent_middle_name'],
            'respondent_middle_name' => ['respondent_middle_name'],
            'last_name' => ['respondent_last_name'],
            'lastname' => ['respondent_last_name'],
            'lname' => ['respondent_last_name'],
            'respondent_last_name' => ['respondent_last_name'],
            'age' => ['respondent_age'],
            'respondent_age' => ['respondent_age'],
            'civil_status' => ['respondent_civil_status'],
            'civil status' => ['respondent_civil_status'],
            'marital_status' => ['respondent_civil_status'],
            'respondent_civil_status' => ['respondent_civil_status'],
            'sex' => ['respondent_sex'],
            'gender' => ['respondent_sex'],
            'respondent_sex' => ['respondent_sex'],
            'religion' => ['respondent_religion'],
            'respondent_religion' => ['respondent_religion'],
            'educational_attainment' => ['respondent_educational_attainment'],
            'education' => ['respondent_educational_attainment'],
            'respondent_educational_attainment' => ['respondent_educational_attainment'],

            // SECTION II: Family Composition
            'family_adults' => ['family_adults'],
            'adults' => ['family_adults'],
            'number_of_family_adults' => ['family_adults'],
            'family_children' => ['family_children'],
            'children' => ['family_children'],
            'number_of_children' => ['family_children'],

            // SECTION III: Economic
            'livelihood_options' => ['livelihood_options'],
            'livelihood' => ['livelihood_options'],
            'interested_in_livelihood_training' => ['interested_in_livelihood_training'],
            'training_interested' => ['interested_in_livelihood_training'],
            'livelihood_training' => ['interested_in_livelihood_training'],
            'desired_training' => ['desired_training'],
            'desired_training_areas' => ['desired_training'],

            // SECTION IV: Educational
            'barangay_educational_facilities' => ['barangay_educational_facilities'],
            'educational_facilities' => ['barangay_educational_facilities'],
            'household_member_currently_studying' => ['household_member_currently_studying'],
            'currently_studying' => ['household_member_currently_studying'],
            'interested_in_continuing_studies' => ['interested_in_continuing_studies'],
            'continuing_studies' => ['interested_in_continuing_studies'],
            'areas_of_educational_interest' => ['areas_of_educational_interest'],
            'educational_interest' => ['areas_of_educational_interest'],
            'preferred_training_time' => ['preferred_training_time'],
            'training_time' => ['preferred_training_time'],
            'preferred_training_days' => ['preferred_training_days'],
            'training_days' => ['preferred_training_days'],

            // SECTION V: Health, Sanitation, Environmental
            'common_illnesses' => ['common_illnesses'],
            'illnesses' => ['common_illnesses'],
            'action_when_sick' => ['action_when_sick'],
            'action_sick' => ['action_when_sick'],
            'barangay_medical_supplies_available' => ['barangay_medical_supplies_available'],
            'medical_supplies' => ['barangay_medical_supplies_available'],
            'has_barangay_health_programs' => ['has_barangay_health_programs'],
            'health_programs' => ['has_barangay_health_programs'],
            'benefits_from_barangay_programs' => ['benefits_from_barangay_programs'],
            'barangay_benefits' => ['benefits_from_barangay_programs'],
            'programs_benefited_from' => ['programs_benefited_from'],
            'water_source' => ['water_source'],
            'water_source_distance' => ['water_source_distance'],
            'garbage_disposal_method' => ['garbage_disposal_method'],
            'garbage_disposal' => ['garbage_disposal_method'],
            'has_own_toilet' => ['has_own_toilet'],
            'toilet' => ['has_own_toilet'],
            'toilet_type' => ['toilet_type'],
            'keeps_animals' => ['keeps_animals'],
            'animals' => ['keeps_animals'],
            'animals_kept' => ['animals_kept'],

            // SECTION VI: Housing and Basic Amenities
            'house_type' => ['house_type'],
            'tenure_status' => ['tenure_status'],
            'has_electricity' => ['has_electricity'],
            'electricity' => ['has_electricity'],
            'light_source_without_power' => ['light_source_without_power'],
            'light_source' => ['light_source_without_power'],
            'appliances_owned' => ['appliances_owned'],
            'appliances' => ['appliances_owned'],

            // SECTION VII: Recreational Facilities
            'barangay_recreational_facilities' => ['barangay_recreational_facilities'],
            'recreational_facilities' => ['barangay_recreational_facilities'],
            'use_of_free_time' => ['use_of_free_time'],
            'free_time' => ['use_of_free_time'],
            'member_of_organization' => ['member_of_organization'],
            'organization_member' => ['member_of_organization'],
            'organization_types' => ['organization_types'],
            'organization_meeting_frequency' => ['organization_meeting_frequency'],
            'meeting_frequency' => ['organization_meeting_frequency'],
            'organization_usual_activities' => ['organization_usual_activities'],
            'usual_activities' => ['organization_usual_activities'],
            'household_members_in_organization' => ['household_members_in_organization'],
            'position_in_organization' => ['position_in_organization'],
            'position' => ['position_in_organization'],

            // SECTION VIII: Problems/Concerns
            'family_problems' => ['family_problems'],
            'health_problems' => ['health_problems'],
            'educational_problems' => ['educational_problems'],
            'employment_problems' => ['employment_problems'],
            'infrastructure_problems' => ['infrastructure_problems'],
            'economic_problems' => ['economic_problems'],
            'security_problems' => ['security_problems'],

            // SECTION IX: Summary
            'barangay_service_ratings' => ['barangay_service_ratings'],
            'service_ratings' => ['barangay_service_ratings'],
            'general_feedback' => ['general_feedback'],
            'feedback' => ['general_feedback'],
            'available_for_training' => ['available_for_training'],
            'available_training' => ['available_for_training'],
            'reason_not_available' => ['reason_not_available'],

            // Community
            'community_id' => ['community_id'],
            'community' => ['community_id'],

            // Quarter & Year
            'quarter' => ['quarter'],
            'year' => ['year'],
        ];

        $mappedFields = [];

        foreach ($row as $key => $value) {
            // Normalize: remove special characters, convert to lowercase, replace spaces with underscores
            $normalizedKey = strtolower(preg_replace('/[^a-z0-9_\s-]/i', '', $key));
            $normalizedKey = str_replace([' ', '-'], '_', $normalizedKey);
            $normalizedKey = trim($normalizedKey, '_');

            // Exact match
            if (isset($fieldMap[$normalizedKey])) {
                foreach ($fieldMap[$normalizedKey] as $fieldName) {
                    $mappedFields[$fieldName] = $this->castValue($value, $fieldName);
                }
            }
            // Partial match
            else {
                foreach (array_keys($fieldMap) as $mapKey) {
                    if (stripos($normalizedKey, $mapKey) !== false || stripos($mapKey, $normalizedKey) !== false) {
                        foreach ($fieldMap[$mapKey] as $fieldName) {
                            $mappedFields[$fieldName] = $this->castValue($value, $fieldName);
                        }
                        break;
                    }
                }
            }
        }

        return $mappedFields;
    }

    /**
     * Map unstructured text (PDF/Image OCR) to form fields
     * Uses pattern matching to extract values
     */
    protected function mapUnstructuredText(string $text): array
    {
        $mappedFields = [];
        
        // Empty text check
        if (empty(trim($text))) {
            \Log::warning('mapUnstructuredText received empty text');
            return [];
        }

        // First, clean up the text by removing or marking section headers
        // Replace "SECTION I:" "SECTION II:" etc with markers so they don't interfere
        $text = preg_replace('/SECTION\s+[IVX]+\s*:\s*/i', ' | SECTIONBREAK | ', $text);
        
        // Exact field labels from the PDF form (case-insensitive matching)
        // Updated to match single-line format with spaces around colons
        $labelMap = [
            // SECTION I: Identifying Information
            'first name' => 'respondent_first_name',
            'middle name' => 'respondent_middle_name',
            'last name' => 'respondent_last_name',
            'age' => 'respondent_age',
            'civil status' => 'respondent_civil_status',
            'sex' => 'respondent_sex',
            'religion' => 'respondent_religion',
            'educational attainment' => 'respondent_educational_attainment',
            
            // SECTION II: Family Composition
            'number of adults' => 'family_adults',
            'number of children' => 'family_children',
            'adults in the household' => 'family_adults',
            'children in the household' => 'family_children',
            
            // SECTION III: Economic Aspect
            'livelihood options' => 'livelihood_options',
            'interested in livelihood training' => 'interested_in_livelihood_training',
            'desired training' => 'desired_training',
            
            // SECTION IV: Educational Aspect
            'barangay educational facilities' => 'barangay_educational_facilities',
            'household member currently studying' => 'household_member_currently_studying',
            'interested in continuing studies' => 'interested_in_continuing_studies',
            'areas of educational interest' => 'areas_of_educational_interest',
            'preferred training time' => 'preferred_training_time',
            'preferred training days' => 'preferred_training_days',
            
            // SECTION V: Health, Sanitation & Environmental
            'common illnesses' => 'common_illnesses',
            'action when sick' => 'action_when_sick',
            'barangay medical supplies available' => 'barangay_medical_supplies_available',
            'has barangay health programs' => 'has_barangay_health_programs',
            'benefits from barangay programs' => 'benefits_from_barangay_programs',
            'programs benefited from' => 'programs_benefited_from',
            'water source' => 'water_source',
            'water source distance' => 'water_source_distance',
            'garbage disposal' => 'garbage_disposal_method',
            'has own toilet' => 'has_own_toilet',
            'toilet type' => 'toilet_type',
            'keeps animals' => 'keeps_animals',
            'animals kept' => 'animals_kept',
            
            // SECTION VI: Housing and Basic Amenities
            'house type' => 'house_type',
            'tenure status' => 'tenure_status',
            'has electricity' => 'has_electricity',
            'appliances owned' => 'appliances_owned',
            
            // SECTION VII: Recreational Facilities
            'barangay recreational facilities' => 'barangay_recreational_facilities',
            'use of free time' => 'use_of_free_time',
            'member of organization' => 'member_of_organization',
            'organizational type' => 'organization_types',
            'meeting frequency' => 'organization_meeting_frequency',
            'position in organization' => 'position_in_organization',
            'usual activities' => 'organization_usual_activities',
            'household members in organization' => 'household_members_in_organization',
            
            // SECTION VIII: Other Needs & Problems
            'family problems' => 'family_problems',
            'health problems' => 'health_problems',
            'educational problems' => 'educational_problems',
            'employment problems' => 'employment_problems',
            'infrastructure problems' => 'infrastructure_problems',
            'economic problems' => 'economic_problems',
            'security problems' => 'security_problems',
            
            // SECTION IX: Summary Barangay Service Ratings
            'law enforcement' => 'barangay_service_ratings',
            'fire protection' => 'barangay_service_ratings',
            'bns service' => 'barangay_service_ratings',
            'street lighting' => 'barangay_service_ratings',
            'water system' => 'barangay_service_ratings',
            'sanitation' => 'barangay_service_ratings',
            'health service' => 'barangay_service_ratings',
            'education service' => 'barangay_service_ratings',
            'infrastructure service' => 'barangay_service_ratings',
            'general feedback' => 'general_feedback',
            'available for training' => 'available_for_training',
        ];
        
        // Create a special map with labels sorted by length (longest first)
        $sortedLabels = array_keys($labelMap);
        usort($sortedLabels, function($a, $b) {
            return strlen($b) - strlen($a); // Longest first
        });
        
        // For each label, find ALL occurrences and extract values
        foreach ($sortedLabels as $label) {
            $fieldName = $labelMap[$label];
            
            // Skip if we've already assigned this field (avoid duplicates)
            if ($fieldName === 'barangay_service_ratings') {
                // These can have multiple values, handled separately
            } else {
                if (isset($mappedFields[$fieldName])) {
                    continue; // Already extracted this field
                }
            }
            
            // Build a case-insensitive search pattern for this specific label
            // Pattern: label followed by optional spaces, colon, then capture value until next known label or break
            $escapedLabel = preg_quote($label, '/');
            
            // More flexible pattern for single-line format:
            // Allows optional whitespace: "label" [optional spaces] ":" [optional spaces] value
            $nextLabelsPattern = implode('|', array_map(function($l) {
                return preg_quote($l, '/');
            }, $sortedLabels));
            
            // Updated pattern to handle single-line formats with spaces around colons
            $pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
            
            if (preg_match($pattern, $text, $matches)) {
                $value = trim($matches[1]);
                
                // Comprehensive cleanup of OCR artifacts and form markers
                // Remove image markers like "BAGONG PILIPINAS --- Image 2 ---"
                $value = preg_replace('/BAGONG\s+PILIPINAS\s*---\s*Image\s+\d+\s*---/ims', '', $value);
                
                // Remove form header repetitions
                $value = preg_replace('/Leyte\s+Normal\s+University.*?Community\s+Needs\s+Assessment.*?Form\s*\(.*?\)/ims', '', $value);
                
                // Remove section breaks and markers
                $value = str_replace(['| SECTIONBREAK |', '?', '*', '☐', '☑', '○', '●'], '', $value);
                
                // Remove common OCR noise: extra spaces, newlines, multiple spaces
                $value = preg_replace('/\s+/', ' ', $value);
                
                // Remove common prefixes that are UI elements, not data
                $value = preg_replace('/^(if yes|if no|if applicable)[,\s]+/i', '', $value);
                
                // Clean up trailing form info
                $value = preg_replace('/EXTENSION\s+Leyte.*$/ims', '', $value);
                
                // Trim and final cleanup
                $value = trim($value);
                
                if (!empty($value)) {
                    // Correct common OCR typos
                    $value = $this->correctOCRTypos($value, $fieldName);
                    
                    // Process the value based on field type
                    $processedValue = $this->processExtractedValue($fieldName, [$value], 0);
                    
                    if ($processedValue !== null) {
                        if ($fieldName === 'barangay_service_ratings') {
                            // Handle service ratings (can have multiple)
                            if (!isset($mappedFields[$fieldName])) {
                                $mappedFields[$fieldName] = [];
                            }
                            $mappedFields[$fieldName][$label] = $processedValue;
                        } else {
                            $mappedFields[$fieldName] = $processedValue;
                        }
                    }
                }
            }
        }
        
        return $mappedFields;
    }
    
    /**
     * Correct common OCR typos and errors
     */
    protected function correctOCRTypos(string $value, string $fieldName): string
    {
        // Common OCR typos mapping
        $corrections = [
            // Civil status corrections
            '/\bSIPGLE\b/i' => 'Single',
            '/\bSINGLE\b/i' => 'Single',
            '/\bMARRIED\b/i' => 'Married',
            '/\bWIDOWED\b/i' => 'Widowed',
            '/\bDIVORCED\b/i' => 'Divorced',
            
            // Yes/No corrections
            '/\bYES\b/i' => 'Yes',
            '/\bNO\b/i' => 'No',
            '/\b[Y1]\b/' => 'Yes',  // 1 might be misread as Y
            '/\b0\b/' => 'No',
            
            // Common field corrections
            '/\bFARMING\b/i' => 'Farming',
            '/\bFISHING\b/i' => 'Fishing',
        ];
        
        // For civil status fields, ensure proper capitalization
        if ($fieldName === 'respondent_civil_status') {
            foreach ($corrections as $pattern => $replacement) {
                $value = preg_replace($pattern, $replacement, $value);
            }
        }
        
        // For yes/no fields, normalize to standard format
        if (in_array($fieldName, ['has_barangay_health_programs', 'keeps_animals', 'available_for_training', 'interested_in_livelihood_training'])) {
            foreach ($corrections as $pattern => $replacement) {
                if (in_array($replacement, ['Yes', 'No'])) {
                    $value = preg_replace($pattern, $replacement, $value);
                }
            }
        }
        
        // For rating fields, keep numeric values but clean them
        if (strpos($fieldName, 'rating') !== false && is_numeric($value)) {
            // Clamp to 1-5 range
            $numeric = intval($value);
            $value = max(1, min(5, $numeric));
        }
        
        return $value;
    }
    
    /**
     * Process extracted values based on field type
     */
    protected function processExtractedValue(string $fieldName, array $values, int $labelLength = 0)
    {
        // Array fields (checkboxes, multi-select)
        $arrayFields = [
            'respondent_educational_attainment',
            'livelihood_options',
            'desired_training',
            'barangay_educational_facilities',
            'areas_of_educational_interest',
            'preferred_training_days',
            'common_illnesses',
            'action_when_sick',
            'barangay_medical_supplies_available',
            'programs_benefited_from',
            'water_source',
            'garbage_disposal_method',
            'toilet_type',
            'animals_kept',
            'house_type',
            'tenure_status',
            'light_source_without_power',
            'appliances_owned',
            'barangay_recreational_facilities',
            'use_of_free_time',
            'organization_types',
            'household_members_in_organization',
            'family_problems',
            'health_problems',
            'educational_problems',
            'employment_problems',
            'infrastructure_problems',
            'economic_problems',
            'security_problems',
        ];
        
        // Boolean fields (Yes/No)
        $booleanFields = [
            'household_member_currently_studying',
            'interested_in_continuing_studies',
            'has_barangay_health_programs',
            'has_own_toilet',
            'keeps_animals',
            'has_electricity',
            'member_of_organization',
            'available_for_training',
            'interested_in_livelihood_training',
            'benefits_from_barangay_programs',
        ];
        
        // Numeric fields
        $numericFields = [
            'respondent_age',
            'family_adults',
            'family_children',
            'year',
            'water_source_distance',
            'organization_meeting_frequency',
            'household_members_in_organization',
        ];
        
        // Handle different field types
        if (in_array($fieldName, $arrayFields)) {
            return array_filter(array_map('trim', $values));
        }
        
        if (in_array($fieldName, $booleanFields)) {
            $text = strtolower(implode(' ', $values));
            if (preg_match('/\b(yes|checked|true|selected|✓|x|yup|yesyes|yeah)\b/i', $text)) {
                return 'Yes';
            } elseif (preg_match('/\b(no|unchecked|false|not selected|☐|none|nope)\b/i', $text)) {
                return 'No';
            }
            // If text is written, treat as Yes
            if (!empty(trim(implode('', $values)))) {
                return 'Yes';
            }
            return null;
        }
        
        if ($fieldName === 'barangay_service_ratings') {
            // Handle both single rating and comma-separated ratings
            $value = implode(' ', $values);
            
            // If we have comma-separated ratings, return as array
            if (preg_match_all('/\b([1-5])\b/', $value, $matches)) {
                $ratings = array_map('intval', $matches[1]);
                // If we got multiple ratings, return them
                if (count($ratings) > 1) {
                    return $ratings;
                }
                // If single rating
                if (count($ratings) === 1) {
                    return $ratings[0];
                }
            }
            return null;
        }
        
        if (in_array($fieldName, $numericFields)) {
            $numValue = null;
            $valueText = implode(' ', $values);
            
            // Skip if field appears empty
            if (empty(trim($valueText))) {
                return null;
            }
            
            // Extract numeric value
            if (preg_match('/\d+/', $valueText, $matches)) {
                $numValue = intval($matches[0]);
                
                // Validate age is reasonable (18-120)
                if ($fieldName === 'respondent_age' && ($numValue < 18 || $numValue > 120)) {
                    return null;
                }
                
                return $numValue;
            }
            return null;
        }
        
        // Default: single string value (first non-empty)
        foreach ($values as $value) {
            $trimmed = trim($value);
            if (!empty($trimmed)) {
                return $trimmed;
            }
        }
        
        return null;
    }

    /**
     * Cast values to appropriate types
     */
    protected function castValue($value, string $fieldName)
    {
        // Array fields - all checkbox and multi-select fields
        $arrayFields = [
            'respondent_educational_attainment',
            'livelihood_options',
            'desired_training',
            'barangay_educational_facilities',
            'areas_of_educational_interest',
            'preferred_training_days',
            'common_illnesses',
            'action_when_sick',
            'barangay_medical_supplies_available',
            'programs_benefited_from',
            'water_source',
            'garbage_disposal_method',
            'toilet_type',
            'animals_kept',
            'house_type',
            'tenure_status',
            'light_source_without_power',
            'appliances_owned',
            'barangay_recreational_facilities',
            'use_of_free_time',
            'organization_types',
            'household_members_in_organization',
            'family_problems',
            'health_problems',
            'educational_problems',
            'employment_problems',
            'infrastructure_problems',
            'economic_problems',
            'security_problems',
        ];

        if (in_array($fieldName, $arrayFields)) {
            // If already an array, return as is
            if (is_array($value)) {
                return $value;
            }
            
            // If string with semicolons, split and trim each item
            if (is_string($value) && strpos($value, ';') !== false) {
                return array_filter(array_map('trim', explode(';', $value)));
            }
            
            // If single string value, return as single-item array
            return !empty($value) ? [trim($value)] : [];
        }

        // Integer fields
        if (in_array($fieldName, ['respondent_age', 'family_adults', 'family_children', 'year', 'community_id', 'household_members_in_organization'])) {
            return intval($value);
        }

        // String fields
        return strval($value);
    }
}
