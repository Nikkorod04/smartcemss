<?php
// Quick diagnostic script for token expiration
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\ExtensionToken;

// Get the most recent token
$token = ExtensionToken::latest()->first();

if (!$token) {
    echo "No tokens found in database.\n";
    exit;
}

$now = now();

echo "Token Diagnostic\n";
echo "================\n\n";
echo "Token Created: " . $token->created_at->format('M d, Y H:i:s') . "\n";
echo "Current Time:  " . $now->format('M d, Y H:i:s') . "\n";
echo "Expires At:    " . ($token->expires_at ? $token->expires_at->format('M d, Y H:i:s') : 'Never') . "\n";
echo "Current Date:  " . $now->format('M d, Y') . "\n\n";

if ($token->expires_at) {
    $remaining = $token->expires_at->timestamp - $now->timestamp;
    $remainingHours = $remaining / 3600;
    $remainingDays = floor($remaining / 86400);
    
    echo "Time Remaining: " . $remaining . " seconds\n";
    echo "Hours Remaining: " . number_format($remainingHours, 2) . "\n";
    echo "Days Remaining: " . $remainingDays . "\n\n";
    
    echo "isPast() Result: " . ($token->expires_at->isPast() ? 'YES (EXPIRED)' : 'NO (VALID)') . "\n";
    echo "isExpired() Result: " . ($token->isExpired() ? 'YES' : 'NO') . "\n";
    echo "isValid() Result: " . ($token->isValid() ? 'YES' : 'NO') . "\n\n";
    
    echo "getExpirationStatus():\n";
    echo "  " . $token->getExpirationStatus() . "\n";
} else {
    echo "Token never expires\n";
}

?>
