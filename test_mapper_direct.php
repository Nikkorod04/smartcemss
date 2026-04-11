<?php

// Load just the mapper class without full Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Services/AssessmentFieldMapper.php';

// Simulate the OCR output from the logs
$ocrText = <<<'EOT'
--- Image 1 ---
Leyte Normal University Community Extension Services Office Community Needs Assessment Form (F-CES-001) BACONG PUUPINAS SECTION 1: Identifying Information First Name: Nikko Middle Name: Last Name: VILLAS Age: 25 Civil Status: SINGLE Sex: MALE Religion: ROMAN CATHOLIC SECTION II: Family Composition Number of Adults in the Household: 5 Number of Children in the Household: 3
EOT;

echo "============================================================\n";
echo "Testing AssessmentFieldMapper with OCR Text\n";
echo "============================================================\n\n";

echo "OCR Text Length: " . strlen($ocrText) . " bytes\n";
echo "First 200 chars:\n";
echo "  " . substr($ocrText, 0, 200) . "...\n\n";

try {
    $mapper = new \App\Services\AssessmentFieldMapper();
    
    // Test the regex pattern
    echo "Running mapUnstructuredText()...\n\n";
    
    // Use reflection to call the protected method for testing
    $reflectionMethod = new ReflectionMethod(\App\Services\AssessmentFieldMapper::class, 'mapUnstructuredText');
    $reflectionMethod->setAccessible(true);
    
    $result = $reflectionMethod->invoke($mapper, $ocrText);
    
    echo "Results:\n";
    echo "  Total Fields Mapped: " . count($result) . "\n";
    echo "  Fields Found:\n";
    
    if (empty($result)) {
        echo "    [EMPTY - No fields extracted!]\n";
    } else {
        foreach ($result as $fieldName => $value) {
            $valueDisplay = is_array($value) ? implode(', ', $value) : $value;
            echo "    - $fieldName: $valueDisplay\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

?>
