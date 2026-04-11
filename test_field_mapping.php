<?php

$text = <<<'EOT'
Leyte Normal University Community Extension Services Office Community Needs Assessment Form (F-CES-001) BACONG PUUPINAS SECTION 1: Identifying Information First Name: Nikko Middle Name: Last Name: VILLAS Age: 25 Civil Status: SINGLE Sex: MALE Religion: ROMAN CATHOLIC SECTION II: Family Composition Number of Adults in the Household: 5 Number of Children in the Household: 3 SECTION III: Economic Aspect Livelihood Options: Farming Interested in Livelihood Training: Yes Desired Training: Business Management SECTION IV: Educational Aspect Barangay Educational Facilities: School Health Service Education Service Infrastructure Service General Feedback Good
EOT;

echo "Testing field extraction with OCR text:\n";
echo "Text length: " . strlen($text) . "\n";
echo "First 200 chars: " . substr($text, 0, 200) . "\n\n";

// Test label extraction
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
];

$sortedLabels = array_keys($labelMap);
usort($sortedLabels, function($a, $b) {
    return strlen($b) - strlen($a);
});

echo "Labels sorted by length (longest first):\n";
foreach ($sortedLabels as $label) {
    echo "  - '$label' (" . strlen($label) . " chars)\n";
}
echo "\n";

$escapedLabels = array_map(function($label) {
    return preg_quote($label, '/');
}, $sortedLabels);

$labelPattern = implode('|', $escapedLabels);
$pattern = '/(' . $labelPattern . ')\s*:\s*([^:\n]*?)(?=(?:' . $labelPattern . ')\s*:|$)/ims';

echo "Regex pattern length: " . strlen($pattern) . " chars\n";
echo "Pattern snippet: " . substr($pattern, 0, 100) . "...\n\n";

// Try to match
if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
    echo "MATCHES FOUND: " . count($matches) . "\n\n";
    
    foreach ($matches as $i => $match) {
        $label = trim($match[1]);
        $value = trim($match[2]);
        echo "Match " . ($i + 1) . ":\n";
        echo "  Label: '$label'\n";
        echo "  Value: '$value'\n";
        echo "  Field: " . (isset($labelMap[strtolower($label)]) ? $labelMap[strtolower($label)] : 'NOT FOUND') . "\n\n";
    }
} else {
    echo "NO MATCHES FOUND!\n";
}

?>
