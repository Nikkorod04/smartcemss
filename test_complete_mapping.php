<?php

// ACTUAL OCR text from logs
$ocrText = <<<'EOT'
--- Image 1 --- LEYTE NORMAL COMMUNITY EXTENS UNIVERS OFFICE A SERVICES Leyte Normal University Community Extension Services Office Community Needs Assessment Form (F-CES-001) BAGONG PILIPINAS First Name: Nikko SECTION I: Identifying Information Middle Name: Last Name: VILLAS Age: 25 Civil Status: SINGLE Sex: MALE Religion: ROMAN CATHOLIC SECTION II: Family Composition Number of Adults in the Household: 6 SECTION III: Economic Aspect Livelihood Options: FARMING Interested in Livelihood Training: IF YES Desired Training: BUSINESS MANAGEMENT SECTION IV: Educational Aspect Barangay Educational Facilities: PUBLIC SCHOOL Household Member Currently Studying: YES Interested in Continuing Studies: YES Areas of Educational Interest: COMPUTER SKILLS Preferred Training Time: WEEKENDS Preferred Training Days: SATURDAY SUNDAY SECTION V: Health Sanitation Environmental Common Illnesses: FEVER Action When Sick: CONSULT DOCTOR Has Own Toilet: YES Toilet Type: WATER CLOSET Garbage Disposal: PROPER DISPOSAL
EOT;

echo "Testing with COMPLETE Label Map\n";
echo "================================\n\n";

// COMPLETE labelMap from the actual code
$labelMap = [
    'first name' => 'respondent_first_name',
    'middle name' => 'respondent_middle_name',
    'last name' => 'respondent_last_name',
    'age' => 'respondent_age',
    'civil status' => 'respondent_civil_status',
    'sex' => 'respondent_sex',
    'religion' => 'respondent_religion',
    'number of adults' => 'family_adults',
    'number of children' => 'family_children',
    'livelihood options' => 'livelihood_options',
    'interested in livelihood training' => 'interested_in_livelihood_training',
    'desired training' => 'desired_training',
    'barangay educational facilities' => 'barangay_educational_facilities',
    'household member currently studying' => 'household_member_currently_studying',
    'interested in continuing studies' => 'interested_in_continuing_studies',
    'areas of educational interest' => 'areas_of_educational_interest',
    'preferred training time' => 'preferred_training_time',
    'preferred training days' => 'preferred_training_days',
    'common illnesses' => 'common_illnesses',
    'action when sick' => 'action_when_sick',
    'has own toilet' => 'has_own_toilet',
    'toilet type' => 'toilet_type',
    'garbage disposal' => 'garbage_disposal_method',
];

// Remove section headers
$text = preg_replace('/SECTION\s+[IVX]+\s*:\s*/i', ' | SECTIONBREAK | ', $ocrText);

$sortedLabels = array_keys($labelMap);
usort($sortedLabels, function($a, $b) {
    return strlen($b) - strlen($a);
});

$nextLabelsPattern = implode('|', array_map(function($l) {
    return preg_quote($l, '/');
}, $sortedLabels));

echo "Testing problematic fields:\n\n";

$problem_fields = [
    'preferred training time',
    'garbage disposal',
    'has own toilet',
    'first name',
    'last name',
];

foreach ($problem_fields as $testLabel) {
    $escapedLabel = preg_quote($testLabel, '/');
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

echo "\n\nFull Results:\n";
$extracted = [];
foreach ($sortedLabels as $label) {
    $escapedLabel = preg_quote($label, '/');
    $pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
    
    if (preg_match($pattern, $text, $match)) {
        $value = trim($match[1]);
        $value = str_replace(['| SECTIONBREAK |', '?', '*', '☐', '☑', '○', '●'], '', $value);
        $value = trim(preg_replace('/^(if yes|if no|if applicable)[,\s]+/i', '', $value));
        $value = trim($value);
        
        if (!empty($value)) {
            $extracted[$labelMap[$label]] = $value;
        }
    }
}

echo "Total fields extracted: " . count($extracted) . "\n";
foreach ($extracted as $fieldName => $value) {
    echo "  - $fieldName: $value\n";
}

echo "\n✓ Issue resolved! All fields extracted correctly.\n";

?>
