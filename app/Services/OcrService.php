<?php

namespace App\Services;

use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Illuminate\Http\UploadedFile;

class OcrService
{
    protected ImageAnnotatorClient $client;

    public function __construct()
    {
        // Initialize Google Vision API client using service account credentials
        // Check for custom filename in .env, otherwise use default
        $credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
        $credentialsPath = storage_path('app' . DIRECTORY_SEPARATOR . $credentialsFile);
        
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

            // Create annotation request with DOCUMENT_TEXT_DETECTION feature
            $feature = new Feature([
                'type' => Type::DOCUMENT_TEXT_DETECTION,
            ]);

            $annotateImageRequest = new AnnotateImageRequest([
                'image' => $image,
                'features' => [$feature],
            ]);

            $batchRequest = new BatchAnnotateImagesRequest([
                'requests' => [$annotateImageRequest],
            ]);

            // Call the API
            $response = $this->client->batchAnnotateImages($batchRequest);
            $results = $response->getResponses();

            if (empty($results) || !$results[0]) {
                \Log::warning('No response from Vision API for image');
                return [
                    'success' => false,
                    'error' => 'No text detected in image'
                ];
            }

            $annotation = $results[0];
            $fullTextAnnotation = $annotation->getFullTextAnnotation();

            if (!$fullTextAnnotation) {
                \Log::warning('No text detected in image');
                return [
                    'success' => false,
                    'error' => 'No text detected in image'
                ];
            }

            $text = $fullTextAnnotation->getText();
            \Log::info('Text extracted from image', ['text_length' => strlen($text)]);

            // Sanitize UTF-8 characters to avoid JSON encoding errors
            $text = $this->sanitizeUtf8($text);
            \Log::info('Text sanitized', ['text_length' => strlen($text)]);

            // Calculate confidence from pages
            $confidence = $this->calculateConfidenceFromAnnotation($annotation);

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $confidence,
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
            $fileSize = strlen($pdfData);
            \Log::info('PDF data read', ['size' => $fileSize]);
            
            // Google Vision has a max request size of 20MB
            if ($fileSize > 20971520) {
                \Log::warning('PDF too large for Google Vision API', ['size' => $fileSize]);
                return [
                    'success' => false,
                    'error' => 'PDF is too large (>20MB) for Google Vision API'
                ];
            }
            
            // For large PDFs, use content instead of URI
            $image = (new Image())->setContent($pdfData);
            \Log::info('Image object created', ['content_size' => strlen($pdfData)]);

            // Create annotation request with DOCUMENT_TEXT_DETECTION feature
            $feature = new Feature([
                'type' => Type::DOCUMENT_TEXT_DETECTION,
            ]);

            $annotateImageRequest = new AnnotateImageRequest([
                'image' => $image,
                'features' => [$feature],
            ]);

            $batchRequest = new BatchAnnotateImagesRequest([
                'requests' => [$annotateImageRequest],
            ]);

            // Call the API
            \Log::info('Calling Google Vision API for document text detection');
            $response = $this->client->batchAnnotateImages($batchRequest);
            \Log::info('Google Vision API response received');
            
            $results = $response->getResponses();
            \Log::info('Batch response info', [
                'results_count' => count($results),
                'first_result_exists' => isset($results[0]),
                'response_class' => get_class($response)
            ]);
            
            if (empty($results)) {
                \Log::warning('No responses from Vision API');
                return [
                    'success' => false,
                    'error' => 'No response from Vision API'
                ];
            }

            if (!isset($results[0])) {
                \Log::warning('First result not set');
                return [
                    'success' => false,
                    'error' => 'Invalid response from Vision API'
                ];
            }

            $annotation = $results[0];
            \Log::info('Annotation object type: ' . get_class($annotation));
            
            // Check for errors in the response
            if ($annotation->hasError && $annotation->hasError()) {
                $error = $annotation->getError();
                \Log::error('Vision API returned error', [
                    'code' => $error->getCode(),
                    'message' => $error->getMessage()
                ]);
                return [
                    'success' => false,
                    'error' => 'Vision API error: ' . $error->getMessage()
                ];
            }
            
            \Log::info('Vision API annotation received', [
                'has_full_text' => method_exists($annotation, 'hasFullTextAnnotation') ? $annotation->hasFullTextAnnotation() : 'unknown',
                'pages_count' => count($annotation->getPages())
            ]);
            
            $fullTextAnnotation = $annotation->getFullTextAnnotation();
            \Log::info('Full text annotation extracted', [
                'document_null' => $fullTextAnnotation === null,
                'has_fulltext_method' => method_exists($annotation, 'getFullTextAnnotation')
            ]);

            if (!$fullTextAnnotation) {
                \Log::warning('No fullTextAnnotation in PDF - trying to get page text');
                // Fallback: try to get text from pages
                $pages = $annotation->getPages();
                \Log::info('Attempting page extraction', ['pages_count' => count($pages)]);
                
                $allText = '';
                foreach ($pages as $pageIndex => $page) {
                    \Log::info('Extracting from page ' . $pageIndex);
                    $pageText = $this->extractTextFromPage($page);
                    \Log::info('Page ' . $pageIndex . ' extracted', ['length' => strlen($pageText)]);
                    $allText .= $pageText;
                }
                
                if (empty($allText)) {
                    \Log::warning('No text found in any page');
                    return [
                        'success' => false,
                        'error' => 'No text detected in PDF'
                    ];
                }
                
                \Log::info('Extracted text from pages', ['text_length' => strlen($allText)]);
                return [
                    'success' => true,
                    'text' => $allText,
                    'confidence' => $this->calculateConfidenceFromPages($annotation->getPages()),
                    'type' => 'pdf'
                ];
            }

            $text = $fullTextAnnotation->getText();
            \Log::info('Text extracted from PDF', ['text_length' => strlen($text)]);

            // Sanitize UTF-8 characters to avoid JSON encoding errors
            $text = $this->sanitizeUtf8($text);
            \Log::info('Text sanitized', ['text_length' => strlen($text)]);

            // Calculate confidence from pages
            $confidence = $this->calculateConfidenceFromAnnotation($annotation);

            return [
                'success' => true,
                'text' => $text,
                'confidence' => $confidence,
                'type' => 'pdf'
            ];
        } catch (\Exception $e) {
            \Log::error('PDF OCR failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);
            return [
                'success' => false,
                'error' => 'PDF OCR failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate confidence from annotation
     */
    protected function calculateConfidenceFromAnnotation($annotation): float
    {
        $fullTextAnnotation = $annotation->getFullTextAnnotation();
        
        if (!$fullTextAnnotation) {
            return 0;
        }

        $pages = $fullTextAnnotation->getPages();
        
        if (empty($pages)) {
            return 0;
        }

        $totalConfidence = 0;
        $count = 0;

        // Get confidence from each page
        foreach ($pages as $page) {
            $confidence = $page->getConfidence();
            if ($confidence > 0) {
                $totalConfidence += $confidence;
                $count++;
            }
        }

        return $count > 0 ? round(($totalConfidence / $count) * 100, 2) : 0;
    }

    /**
     * Extract text from individual pages
     */
    protected function extractTextFromPage($page): string
    {
        $text = '';
        $paragraphs = $page->getParagraphs();
        
        foreach ($paragraphs as $paragraph) {
            $words = $paragraph->getWords();
            foreach ($words as $word) {
                $symbols = $word->getSymbols();
                foreach ($symbols as $symbol) {
                    $text .= $symbol->getText();
                }
                $text .= ' ';
            }
            $text .= "\n";
        }
        
        return $text;
    }

    /**
     * Calculate confidence from pages
     */
    protected function calculateConfidenceFromPages($pages): float
    {
        if (empty($pages)) {
            return 0;
        }

        $totalConfidence = 0;
        $count = 0;

        foreach ($pages as $page) {
            $confidence = $page->getConfidence();
            if ($confidence > 0) {
                $totalConfidence += $confidence;
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

    /**
     * Sanitize text to ensure valid UTF-8 encoding
     * Removes or replaces invalid UTF-8 sequences to prevent JSON encoding errors
     */
    protected function sanitizeUtf8(string $text): string
    {
        // First, try to handle common invalid UTF-8 sequences
        // iconv can convert invalid sequences to valid ones
        $sanitized = iconv('UTF-8', 'UTF-8//IGNORE', $text);
        
        if ($sanitized === false) {
            // If iconv fails, remove non-valid UTF-8 characters
            $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $text);
            $sanitized = preg_replace('/\xc3[^\x80-\xbf]/', '', $sanitized); // Remove invalid UTF-8 sequences
        }
        
        // Also clean up common OCR artifacts
        // Replace multiple spaces with single space
        $sanitized = preg_replace('/\s+/', ' ', $sanitized);
        
        // Remove any remaining control characters except newlines and tabs
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $sanitized);
        
        return trim($sanitized);
    }
}
