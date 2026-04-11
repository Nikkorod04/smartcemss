<?php

// Use ACTUAL OCR text from the logs
$ocrText = <<<'EOT'
--- Image 1 --- LEYTE NORMAL COMMUNITY EXTENS UNIVERS OFFICE A SERVICES Leyte Normal University Community Extension Services Office Community Needs Assessment Form (F-CES-001) BAGONG PILIPINAS First Name: Nikko SECTION I: Identifying Information Middle Name: Last Name: VILLAS Age: 25 Civil Status: SINGLE Sex: MALE Religion: ROMAN CATHOLIC SECTION II: Family Composition Number of Adults in the Household: 6 SECTION III: Economic Aspect Livelihood Options: FARMING Interested in Livelihood Training: IF YES Desired Training: BUSINESS MANAGEMENT SECTION IV: Educational Aspect Barangay Educational Facilities: PUBLIC SCHOOL Household Member Currently Studying: YES Interested in Continuing Studies: YES Areas of Educational Interest: COMPUTER SKILLS Preferred Training Time: WEEKENDS Preferred Training Days: SATURDAY SUNDAY SECTION V: Health Sanitation Environmental Common Illnesses: FEVER Action When Sick: CONSULT DOCTOR Has Own Toilet: YES Toilet Type: WATER CLOSET Garbage Disposal: PROPER DISPOSAL
EOT;

echo "Testing Improved Regex Pattern with ACTUAL OCR Text\n";
echo "====================================================\n\n";

// Simulate section header removal
$text = preg_replace('/SECTION\s+[IVX]+\s*:\s*/i', ' | SECTIONBREAK | ', $ocrText);

$labels_to_test = [
    'first name',
    'garbage disposal',
    'preferred training time',
];

$allLabels = [
    'first name', 'middle name', 'last name', 'age', 'civil status', 'sex', 'religion',
    'number of adults', 'number of children', 'livelihood options', 'interested in livelihood training',
    'desired training', 'barangay educational facilities', 'preferred training time',
    'garbage disposal', 'has own toilet', 'common illnesses', 'action when sick'
];

usort($allLabels, function($a, $b) {
    return strlen($b) - strlen($a);
});

echo "Testing extraction of problematic fields:\n\n";

foreach ($labels_to_test as $testLabel) {
    $escapedLabel = preg_quote($testLabel, '/');
    $nextLabelsPattern = implode('|', array_map(function($l) {
        return preg_quote($l, '/');
    }, $allLabels));
    
    $pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
    
    if (preg_match($pattern, $text, $match)) {
        $value = trim($match[1]);
        $value = str_replace(['| SECTIONBREAK |', '?', '*', '☐', '☑', '○', '●'], '', $value);
        $value = trim(preg_replace('/^(if yes|if no|if applicable)[,\s]+/i', '', $value));
        $value = trim($value);
        echo "✓ '$testLabel': '$value'\n";
    } else {
        echo "✗ '$testLabel': NOT FOUND\n";
    }
}

echo "\nAll extracted fields:\n";

// Extract all fields
$extracted = [];
foreach ($allLabels as $label) {
    $escapedLabel = preg_quote($label, '/');
    $nextLabelsPattern = implode('|', array_map(function($l) {
        return preg_quote($l, '/');
    }, $allLabels));
    
    $pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
    
    if (preg_match($pattern, $text, $match)) {
        $value = trim($match[1]);
        $value = str_replace(['| SECTIONBREAK |', '?', '*', '☐', '☑', '○', '●'], '', $value);
        $value = trim(preg_replace('/^(if yes|if no|if applicable)[,\s]+/i', '', $value));
        $value = trim($value);
        
        if (!empty($value)) {
            $extracted[$label] = $value;
        }
    }
}

echo "  Total fields found: " . count($extracted) . "\n";
foreach ($extracted as $label => $value) {
    echo "  - $label: $value\n";
}

?>
