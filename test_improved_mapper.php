<?php

// Simulate the exact OCR output from the logs - the problematic one
$ocrText = <<<'EOT'
--- Image 1 --- LEYTE NORMAL COMMUNITY EXTENS UNIVERS OFFICE A SERVICES Leyte Normal University Community Extension Services Office Community Needs Assessment Form (F-CES-001) BAGONG PILIPINAS First Name: Nikko SECTION I: Identifying Information Middle Name: Last Name: VILLAS Age: 25 Civil Status: SINGLE Sex: MALE Religion: ROMAN CATHOLIC SECTION II: Family Composition Number of Adults in the Household: 6 SECTION III: Economic Aspect Livelihood Options: FARMING Interested in Livelihood Training: IF YES Desired Training: BUSINESS MANAGEMENT SECTION IV: Educational Aspect Barangay Educational Facilities: PUBLIC SCHOOL Household Member Currently Studying: YES Interested in Continuing Studies: YES Areas of Educational Interest: COMPUTER SKILLS Preferred Training Time: WEEKENDS Preferred Training Days: SATURDAY SUNDAY SECTION V: Health Sanitation Environmental Common Illnesses: FEVER Action When Sick: CONSULT DOCTOR Has Own Toilet: YES Toilet Type: WATER CLOSET Garbage Disposal Method: PROPER DISPOSAL SECTION VI: Housing Housing Type: CONCRETE HOUSE Tenure Status: OWNED Has Electricity: YES
EOT;

echo "============================================================\n";
echo "Testing Improved Field Mapper with Actual OCR Text\n";
echo "============================================================\n\n";

echo "OCR Text Length: " . strlen($ocrText) . " bytes\n";
echo "First 300 chars:\n";
echo "  " . substr($ocrText, 0, 300) . "...\n\n";

try {
    $mapper = new \App\Services\AssessmentFieldMapper();
    
    // Use reflection to call the protected method for testing
    $reflectionMethod = new ReflectionMethod(\App\Services\AssessmentFieldMapper::class, 'mapUnstructuredText');
    $reflectionMethod->setAccessible(true);
    
    $result = $reflectionMethod->invoke($mapper, $ocrText);
    
    echo "Results:\n";
    echo "  Total Fields Mapped: " . count($result) . "\n\n";
    
    if (empty($result)) {
        echo "  [EMPTY - No fields extracted!]\n";
    } else {
        echo "  Fields Found:\n";
        foreach ($result as $fieldName => $value) {
            $valueDisplay = is_array($value) ? '[' . implode(', ', $value) . ']' : $value;
            echo "    - $fieldName: $valueDisplay\n";
        }
    }
    
    echo "\n\nValidation Checks:\n";
    echo "  ✓ First Name extracted: " . (isset($result['respondent_first_name']) && !empty($result['respondent_first_name']) ? "YES - " . $result['respondent_first_name'] : "NO") . "\n";
    echo "  ✓ Last Name extracted: " . (isset($result['respondent_last_name']) && !empty($result['respondent_last_name']) ? "YES - " . $result['respondent_last_name'] : "NO") . "\n";
    echo "  ✓ Garbage Disposal extracted: " . (isset($result['garbage_disposal_method']) && !empty($result['garbage_disposal_method']) ? "YES - " . $result['garbage_disposal_method'] : "NO") . "\n";
    echo "  ✓ Preferred Training Time extracted: " . (isset($result['preferred_training_time']) && !empty($result['preferred_training_time']) ? "YES - " . $result['preferred_training_time'] : "NO") . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

?>
