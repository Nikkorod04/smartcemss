<?php

namespace App\Services;

class AssessmentFieldMapper
{
    /**
     * Map extracted data from document to form fields
     */
    public function mapDataToFields(array $extractedData, string $sourceType = 'excel'): array
    {
        $mappedFields = [];

        if ($sourceType === 'excel' || $sourceType === 'csv') {
            // For structured data (Excel/CSV), map the first row to fields
            $firstRow = $extractedData['data'][0] ?? [];
            $mappedFields = $this->mapStructuredData($firstRow);
        } elseif ($sourceType === 'pdf' || $sourceType === 'image' || $sourceType === 'images') {
            // For unstructured text (PDF/Image), extract key-value pairs
            $text = $extractedData['text'] ?? $extractedData['raw_text'] ?? '';
            $mappedFields = $this->mapUnstructuredText($text);
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
        
        // Build label-to-field mapping based on OCR text patterns
        $labelMap = [
            // SECTION I
            'first name' => 'respondent_first_name',
            'middle name' => 'respondent_middle_name',
            'last name' => 'respondent_last_name',
            'age' => 'respondent_age',
            'civil status' => 'respondent_civil_status',
            'sex' => 'respondent_sex',
            'gender' => 'respondent_sex',
            'religion' => 'respondent_religion',
            'educational attainment' => 'respondent_educational_attainment',
            
            // SECTION II
            'family adults' => 'family_adults',
            'number of adults' => 'family_adults',
            'family children' => 'family_children',
            'number of children' => 'family_children',
            
            // SECTION III
            'livelihood' => 'livelihood_options',
            'livelihood options' => 'livelihood_options',
            'interested in livelihood training' => 'interested_in_livelihood_training',
            'desired training' => 'desired_training',
            
            // SECTION IV
            'educational facilities' => 'barangay_educational_facilities',
            'currently studying' => 'household_member_currently_studying',
            'continuing studies' => 'interested_in_continuing_studies',
            'areas of educational interest' => 'areas_of_educational_interest',
            'preferred training time' => 'preferred_training_time',
            'preferred training days' => 'preferred_training_days',
            'training days' => 'preferred_training_days',
            
            // SECTION V
            'common illnesses' => 'common_illnesses',
            'illnesses' => 'common_illnesses',
            'action when sick' => 'action_when_sick',
            'medical supplies' => 'barangay_medical_supplies_available',
            'health programs' => 'has_barangay_health_programs',
            'benefits from programs' => 'benefits_from_barangay_programs',
            'programs benefited' => 'programs_benefited_from',
            'water source' => 'water_source',
            'garbage disposal' => 'garbage_disposal_method',
            'own toilet' => 'has_own_toilet',
            'toilet type' => 'toilet_type',
            'keeps animals' => 'keeps_animals',
            'animals kept' => 'animals_kept',
            
            // SECTION VI
            'house type' => 'house_type',
            'tenure status' => 'tenure_status',
            'electricity' => 'has_electricity',
            'light source' => 'light_source_without_power',
            'appliances' => 'appliances_owned',
            
            // SECTION VII
            'recreational facilities' => 'barangay_recreational_facilities',
            'use of free time' => 'use_of_free_time',
            'free time' => 'use_of_free_time',
            'member of organization' => 'member_of_organization',
            'organization types' => 'organization_types',
            'organization:' => 'organization_types',
            'meeting frequency' => 'organization_meeting_frequency',
            'usual activities' => 'organization_usual_activities',
            'household members in organization' => 'household_members_in_organization',
            'position in organization' => 'position_in_organization',
            
            // SECTION VIII
            'family problems' => 'family_problems',
            'health problems' => 'health_problems',
            'educational problems' => 'educational_problems',
            'employment problems' => 'employment_problems',
            'infrastructure problems' => 'infrastructure_problems',
            'economic problems' => 'economic_problems',
            'security problems' => 'security_problems',
            
            // SECTION IX
            'service ratings' => 'barangay_service_ratings',
            'general feedback' => 'general_feedback',
            'feedback' => 'general_feedback',
            'available for training' => 'available_for_training',
            'reason not available' => 'reason_not_available',
        ];
        
        // Normalize text for searching
        $textLower = strtolower($text);
        $lines = preg_split('/\r\n|\r|\n/', $text);
        
        // For each line, check if it starts a field label
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            $lineLower = strtolower($line);
            
            // Skip empty and section header lines
            if (empty($line) || preg_match('/^SECTION/i', $line)) {
                continue;
            }
            
            // Check if this line starts with a known field label
            foreach ($labelMap as $label => $fieldName) {
                if (stripos($lineLower, $label) === 0) {
                    // Found a field label, extract the value(s)
                    $fieldName = $labelMap[$label];
                    
                    // Get the part after the label
                    $labelLength = strlen($label);
                    $remainder = trim(substr($line, $labelLength));
                    
                    // Remove common separators
                    $remainder = trim(str_replace([':', '?', '*'], '', $remainder));
                    
                    // Collect values from this line and following lines until next label or section
                    $values = [];
                    if (!empty($remainder)) {
                        $values[] = $remainder;
                    }
                    
                    // Look at following lines for more values (until we hit another field or section)
                    $j = $i + 1;
                    while ($j < count($lines)) {
                        $nextLine = trim($lines[$j]);
                        
                        // Stop at section headers
                        if (preg_match('/^SECTION/i', $nextLine)) {
                            break;
                        }
                        
                        // Stop at next field label
                        if (preg_match('/^(' . implode('|', array_map('preg_quote', array_keys($labelMap))) . ')/i', $nextLine)) {
                            break;
                        }
                        
                        // Add non-empty lines as values
                        if (!empty($nextLine)) {
                            // Clean checkbox markers like "O", "X", "☐", etc.
                            $cleaned = trim(preg_replace('/^[O✓☐☑✗X●○\-\s]+/i', '', $nextLine));
                            if (!empty($cleaned)) {
                                $values[] = $cleaned;
                            }
                        }
                        
                        $j++;
                    }
                    
                    // Process collected values
                    if (!empty($values)) {
                        $processedValue = $this->processExtractedValue($fieldName, $values);
                        if ($processedValue !== null) {
                            $mappedFields[$fieldName] = $processedValue;
                        }
                    }
                    
                    break; // Only match first label per line
                }
            }
        }
        
        return $mappedFields;
    }
    
    /**
     * Process extracted values based on field type
     */
    protected function processExtractedValue(string $fieldName, array $values)
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
            'barangay_service_ratings',
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
            if (preg_match('/\b(yes|checked|true|selected|✓)\b/i', $text)) {
                return 'Yes';
            } elseif (preg_match('/\b(no|unchecked|false|not selected|☐)\b/i', $text)) {
                return 'No';
            }
            return null;
        }
        
        if (in_array($fieldName, $numericFields)) {
            $numValue = null;
            foreach ($values as $value) {
                if (preg_match('/\d+/', $value, $matches)) {
                    $numValue = intval($matches[0]);
                    break;
                }
            }
            return $numValue;
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
