<?php

namespace App\Services;

use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Http\UploadedFile;

class OcrService
{
    protected ImageAnnotatorClient $client;

    public function __construct()
    {
        // Initialize Google Vision API client using service account credentials
        // Check for custom filename in .env, otherwise use default
        $credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
        $credentialsPath = storage_path('app/' . $credentialsFile);
        
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Google Cloud credentials file not found at ' . $credentialsPath . 
                                '. Set GOOGLE_CREDENTIALS_FILE in .env or place credentials at storage/app/' . $credentialsFile);
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
        $this->client = new ImageAnnotatorClient();
    }

    /**
     * Extract text from image using Google Cloud Vision API
     */
    public function extractFromImage(UploadedFile $file): array
    {
        try {
            $imageData = file_get_contents($file->getRealPath());
            $image = (new Image())->setContent($imageData);

            // Perform text detection
            $response = $this->client->documentTextDetection($image);
            $document = $response->getFullTextAnnotation();

            if (!$document) {
                return [
                    'success' => false,
                    'error' => 'No text detected in image'
                ];
            }

            $text = $document->getText();

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($response),
                'type' => 'image'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'OCR failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Extract text from PDF using Google Cloud Vision API
     * Note: For best results with scanned PDFs, use this instead of PDF parser
     */
    public function extractFromPdf(UploadedFile $file): array
    {
        try {
            $pdfData = file_get_contents($file->getRealPath());
            
            // For large PDFs, use batch request with PDF MIME type
            $image = (new Image())->setContent($pdfData);
            $gcsSourceUri = null; // Could use GCS URI for large files

            // Use document text detection for better results with forms
            $response = $this->client->documentTextDetection($image);
            $document = $response->getFullTextAnnotation();

            if (!$document) {
                return [
                    'success' => false,
                    'error' => 'No text detected in PDF'
                ];
            }

            $text = $document->getText();

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($response),
                'type' => 'pdf'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'PDF OCR failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate average confidence score from OCR results
     */
    protected function calculateConfidence($response): float
    {
        $annotations = $response->getTextAnnotations();
        
        if (empty($annotations)) {
            return 0;
        }

        $totalConfidence = 0;
        $count = 0;

        // Skip first annotation (full text)
        for ($i = 1; $i < count($annotations); $i++) {
            $annotation = $annotations[$i];
            if ($annotation->getConfidence() > 0) {
                $totalConfidence += $annotation->getConfidence();
                $count++;
            }
        }

        return $count > 0 ? round(($totalConfidence / $count) * 100, 2) : 0;
    }

    /**
     * Cleanup - close the client connection
     */
    public function __destruct()
    {
        if (isset($this->client)) {
            $this->client->close();
        }
    }
}
