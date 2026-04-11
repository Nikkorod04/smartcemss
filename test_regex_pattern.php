<?php

// Test the new improved regex pattern directly
$ocrText = <<<'EOT'
--- Image 1 --- First Name: Nikko SECTION I: Identifying Information Middle Name: Last Name: VILLAS Age: 25 SECTION II: Family Composition Number of Adults in the Household: 6 Number of Children: 2 SECTION III: Economic Aspect Livelihood Options: FARMING Interested in Livelihood Training: YES Garbage Disposal Method: PROPER DISPOSAL Has Own Toilet: YES Preferred Training Time: WEEKENDS
EOT;

echo "Testing Improved Regex Pattern\n";
echo "===============================\n\n";

// Simulate section header removal
$text = preg_replace('/SECTION\s+[IVX]+\s*:\s*/i', ' | SECTIONBREAK | ', $ocrText);
echo "After removing section headers:\n";
echo substr($text, 0, 200) . "...\n\n";

$labelMap = [
    'first name' => 'respondent_first_name',
    'middle name' => 'respondent_middle_name',
    'last name' => 'respondent_last_name',
    'age' => 'respondent_age',
    'garbage disposal' => 'garbage_disposal_method',
    'has own toilet' => 'has_own_toilet',
    'preferred training time' => 'preferred_training_time',
    'number of adults' => 'family_adults',
    'number of children' => 'family_children',
];

$sortedLabels = array_keys($labelMap);
usort($sortedLabels, function($a, $b) {
    return strlen($b) - strlen($a);
});

echo "Testing extraction of key problematic fields:\n\n";

// Test the garbage disposal field
$testLabel = 'garbage disposal';
$escapedLabel = preg_quote($testLabel, '/');
$nextLabelsPattern = implode('|', array_map(function($l) {
    return preg_quote($l, '/');
}, $sortedLabels));

$pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';

echo "Pattern for '$testLabel':\n";
echo "  " . substr($pattern, 0, 80) . "...\n\n";

if (preg_match($pattern, $text, $match)) {
    $value = trim($match[1]);
    $value = str_replace(['| SECTIONBREAK |', '?', '*'], '', $value);
    $value = trim($value);
    echo "✓ Garbage Disposal: '$value'\n";
} else {
    echo "✗ Garbage Disposal: NOT FOUND\n";
}

// Test first name
$testLabel = 'first name';
$escapedLabel = preg_quote($testLabel, '/');
$pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
if (preg_match($pattern, $text, $match)) {
    $value = trim($match[1]);
    $value = str_replace(['| SECTIONBREAK |', '?', '*'], '', $value);
    $value = trim($value);
    echo "✓ First Name: '$value'\n";
} else {
    echo "✗ First Name: NOT FOUND\n";
}

// Test preferred training time
$testLabel = 'preferred training time';
$escapedLabel = preg_quote($testLabel, '/');
$pattern = '/' . $escapedLabel . '\s*:\s*(.*?)(?=(?:' . $nextLabelsPattern . ')\s*:|\|\ SECTIONBREAK\ \|)/ims';
if (preg_match($pattern, $text, $match)) {
    $value = trim($match[1]);
    $value = str_replace(['| SECTIONBREAK |', '?', '*'], '', $value);
    $value = trim($value);
    echo "✓ Preferred Training Time: '$value'\n";
} else {
    echo "✗ Preferred Training Time: NOT FOUND\n";
}

echo "\n✓ Pattern correctly extracts values!\n";

?>
