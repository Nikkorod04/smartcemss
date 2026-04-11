<?php
require 'vendor/autoload.php';

// Load .env
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
$path = storage_path('app/' . $credentialsFile);

echo "Credentials File (env): " . $credentialsFile . PHP_EOL;
echo "Expected Path: " . $path . PHP_EOL;
echo "File Exists: " . (file_exists($path) ? "YES ✓" : "NO ✗") . PHP_EOL;

if (file_exists($path)) {
    echo "File Size: " . filesize($path) . " bytes" . PHP_EOL;
    
    // Try to initialize OcrService
    try {
        $ocr = new App\Services\OcrService();
        echo "OcrService: Initialized ✓" . PHP_EOL;
    } catch (\Exception $e) {
        echo "OcrService Error: " . $e->getMessage() . PHP_EOL;
    }
}
