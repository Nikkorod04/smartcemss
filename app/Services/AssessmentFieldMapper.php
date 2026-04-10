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
        } elseif ($sourceType === 'pdf' || $sourceType === 'image') {
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

        // Split text into lines
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $text = strtolower($text);

        // Extract respondent name patterns
        if (preg_match('/(?:name|fullname|respondent).*?:\s*([A-Za-z\s]+)/i', $text, $matches)) {
            $name = trim($matches[1]);
            $nameParts = explode(' ', $name);
            if (count($nameParts) >= 1) {
                $mappedFields['respondent_first_name'] = $nameParts[0] ?? null;
                if (count($nameParts) >= 3) {
                    $mappedFields['respondent_middle_name'] = $nameParts[1];
                    $mappedFields['respondent_last_name'] = $nameParts[2];
                } elseif (count($nameParts) >= 2) {
                    $mappedFields['respondent_last_name'] = $nameParts[1];
                }
            }
        }

        // Extract age
        if (preg_match('/(?:age).*?:\s*(\d+)/i', $text, $matches)) {
            $mappedFields['respondent_age'] = intval($matches[1]);
        }

        // Extract sex/gender
        if (preg_match('/(?:sex|gender).*?:\s*(male|female|other)/i', $text, $matches)) {
            $mappedFields['respondent_sex'] = ucfirst($matches[1]);
        }

        // Extract civil status
        if (preg_match('/(?:civil|marital|status).*?:\s*(single|married|widowed|divorced|separated)/i', $text, $matches)) {
            $mappedFields['respondent_civil_status'] = ucfirst($matches[1]);
        }

        // Extract religion
        if (preg_match('/(?:religion|faith).*?:\s*([A-Za-z\s]+?)(?:\n|$)/i', $text, $matches)) {
            $mappedFields['respondent_religion'] = trim($matches[1]);
        }

        // Extract family info
        if (preg_match('/(?:adult|male.*?\d+|female.*?adult).*?:\s*(\d+)/i', $text, $matches)) {
            $mappedFields['family_adults'] = intval($matches[1]);
        }

        if (preg_match('/(?:children|child).*?:\s*(\d+)/i', $text, $matches)) {
            $mappedFields['family_children'] = intval($matches[1]);
        }

        // Extract quarter
        if (preg_match('/(?:quarter|q[14]).*?:\s*(q[1-4]|[1-4])/i', $text, $matches)) {
            $mappedFields['quarter'] = strtoupper('Q' . substr($matches[1], -1));
        }

        // Extract year
        if (preg_match('/(?:year).*?:\s*(20\d{2})/i', $text, $matches)) {
            $mappedFields['year'] = intval($matches[1]);
        }

        return $mappedFields;
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
