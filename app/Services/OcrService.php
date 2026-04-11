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
            \Log::info('OcrService.extractFromImage called', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);
            
            $imageData = file_get_contents($file->getRealPath());
            $image = (new Image())->setContent($imageData);

            // Perform text detection
            $response = $this->client->documentTextDetection($image);
            $document = $response->getFullTextAnnotation();

            if (!$document) {
                \Log::warning('No text detected in image');
                return [
                    'success' => false,
                    'error' => 'No text detected in image'
                ];
            }

            $text = $document->getText();
            \Log::info('Text extracted from image', ['text_length' => strlen($text)]);

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($response),
                'type' => 'image'
            ];
        } catch (\Exception $e) {
            \Log::error('Image OCR failed', [
                'message' => $e->getMessage(),
                'exception' => get_class($e)
            ]);
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
            \Log::info('OcrService.extractFromPdf called', [
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $file->getRealPath(),
                'file_size' => $file->getSize()
            ]);
            
            $pdfData = file_get_contents($file->getRealPath());
            \Log::info('PDF data read', ['size' => strlen($pdfData)]);
            
            // For large PDFs, use batch request with PDF MIME type
            $image = (new Image())->setContent($pdfData);
            \Log::info('Image object created');
            
            $gcsSourceUri = null; // Could use GCS URI for large files

            // Use document text detection for better results with forms
            \Log::info('Calling Google Vision API for document text detection');
            $response = $this->client->documentTextDetection($image);
            \Log::info('Google Vision API response received');
            
            $document = $response->getFullTextAnnotation();
            \Log::info('Full text annotation extracted', ['document_null' => $document === null]);

            if (!$document) {
                \Log::warning('No text detected in PDF');
                return [
                    'success' => false,
                    'error' => 'No text detected in PDF'
                ];
            }

            $text = $document->getText();
            \Log::info('Text extracted from PDF', ['text_length' => strlen($text)]);

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($response),
                'type' => 'pdf'
            ];
        } catch (\Exception $e) {
            \Log::error('PDF OCR failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
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
