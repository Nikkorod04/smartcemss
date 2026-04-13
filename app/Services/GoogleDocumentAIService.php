<?php

namespace App\Services;

use Google\Cloud\DocumentAI\V1\Document;
use Google\Cloud\DocumentAI\V1\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class GoogleDocumentAIService
{
    protected DocumentProcessorServiceClient $client;
    protected string $processorName;

    public function __construct()
    {
        $credentialsFile = config('services.google_docai.credentials_file');
        $projectId = config('services.google_docai.project_id');
        $processorId = config('services.google_docai.processor_id');

        if (!$credentialsFile || !$projectId || !$processorId) {
            throw new \Exception('Google Document AI configuration missing. Check config/services.php');
        }

        $credentialsPath = storage_path('app' . DIRECTORY_SEPARATOR . $credentialsFile);
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Google Document AI credentials file not found at {$credentialsPath}");
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);

        try {
            $this->client = new DocumentProcessorServiceClient();
            $this->processorName = "projects/{$projectId}/locations/us/processors/{$processorId}";
            
            Log::info('Google Document AI Service initialized', [
                'processor' => $this->processorName,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Document AI Service', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Process a PDF or image file using Document AI Form Parser
     * 
     * @param string $filePath Path to the file to process
     * @return array Extracted form data
     */
    public function processDocument(string $filePath): array
    {
        try {
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'error' => "File not found: {$filePath}",
                ];
            }

            Log::info('Processing document with Document AI', [
                'file' => $filePath,
                'size' => filesize($filePath),
            ]);

            $fileContent = file_get_contents($filePath);
            $mimeType = $this->detectMimeType($filePath);

            $rawDocument = new RawDocument([
                'content' => $fileContent,
                'mime_type' => $mimeType,
            ]);

            $request = new ProcessRequest([
                'name' => $this->processorName,
                'raw_document' => $rawDocument,
            ]);

            $response = $this->client->processDocument($request);
            $document = $response->getDocument();

            Log::info('Document processed successfully', [
                'page_count' => $document->getPages()->count(),
                'entity_count' => count($document->getEntities()),
            ]);

            return [
                'success' => true,
                'document' => $document,
                'extracted_data' => $this->extractFormData($document),
            ];

        } catch (\Exception $e) {
            Log::error('Document AI processing failed', [
                'error' => $e->getMessage(),
                'file' => $filePath,
            ]);

            return [
                'success' => false,
                'error' => 'Document processing failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract structured form data from Document AI response
     * 
     * @param Document $document The Document returned from Document AI
     * @return array Extracted field data
     */
    protected function extractFormData(Document $document): array
    {
        $extractedData = [];

        // Document AI Form Parser returns entities (form fields) with normalized keys
        foreach ($document->getEntities() as $entity) {
            $fieldName = $this->normalizeFieldName($entity->getType());
            $fieldValue = $this->extractEntityValue($entity);

            if ($fieldName && $fieldValue !== null) {
                $extractedData[$fieldName] = $fieldValue;

                Log::debug('Extracted field', [
                    'field' => $fieldName,
                    'value' => is_array($fieldValue) ? count($fieldValue) . ' items' : substr((string)$fieldValue, 0, 50),
                ]);
            }
        }

        Log::info('Form data extraction complete', [
            'fields_extracted' => count($extractedData),
        ]);

        return $extractedData;
    }

    /**
     * Extract value from a Document AI entity
     * Handles both simple string values and nested structures
     * 
     * @param Entity $entity The entity from Document AI
     * @return mixed The extracted value
     */
    protected function extractEntityValue($entity)
    {
        // Get the text content
        $text = $entity->getMentionText();

        if (empty($text)) {
            // Try to get value from normalized text
            $text = $entity->getTextAnchor()?->getContent() ?? null;
        }

        if (empty($text)) {
            // Try children entities (for complex fields)
            $children = $entity->getProperties();
            if (count($children) > 0) {
                $values = [];
                foreach ($children as $child) {
                    $childValue = $this->extractEntityValue($child);
                    if ($childValue !== null) {
                        $values[] = $childValue;
                    }
                }
                return count($values) > 0 ? $values : null;
            }
            return null;
        }

        $text = trim($text);

        // Normalize common form values
        if (in_array(strtolower($text), ['yes', 'true', 'checked', 'x', '✓'])) {
            return 'Yes';
        }
        if (in_array(strtolower($text), ['no', 'false', 'unchecked', '☐', ''])) {
            return 'No';
        }

        // Try to parse as integer
        if (is_numeric($text)) {
            $num = intval($text);
            // If it looks like a rating (1-5), return as integer
            if ($num >= 1 && $num <= 5) {
                return $num;
            }
        }

        return $text;
    }

    /**
     * Normalize Document AI field types to our database field names
     * Document AI returns field names like "first_name", "age", etc.
     * We need to map them to our database field names
     * 
     * @param string $documentAIFieldName Field name from Document AI
     * @return string|null Normalized field name or null if unmapped
     */
    protected function normalizeFieldName(string $documentAIFieldName): ?string
    {
        $fieldMap = [
            // Respondent Information
            'first_name' => 'respondent_first_name',
            'middle_name' => 'respondent_middle_name',
            'last_name' => 'respondent_last_name',
            'age' => 'respondent_age',
            'civil_status' => 'respondent_civil_status',
            'marital_status' => 'respondent_civil_status',
            'sex' => 'respondent_sex',
            'gender' => 'respondent_sex',
            'religion' => 'respondent_religion',
            'educational_attainment' => 'respondent_educational_attainment',

            // Family Composition
            'family_adults' => 'family_adults',
            'adults' => 'family_adults',
            'family_children' => 'family_children',
            'children' => 'family_children',

            // Health & Sanitation
            'has_barangay_health_programs' => 'has_barangay_health_programs',
            'barangay_health_programs' => 'has_barangay_health_programs',

            // Service Ratings (critical)
            'service_ratings' => 'barangay_service_ratings',
            'barangay_service_ratings' => 'barangay_service_ratings',
            'law_enforcement' => 'barangay_service_ratings',
            'fire_protection' => 'barangay_service_ratings',
            'bns_service' => 'barangay_service_ratings',
            'street_lighting' => 'barangay_service_ratings',
            'water_system' => 'barangay_service_ratings',
            'sanitation' => 'barangay_service_ratings',
            'health_service' => 'barangay_service_ratings',
            'education_service' => 'barangay_service_ratings',

            // Other fields
            'security_problems' => 'security_problems',
            'general_feedback' => 'general_feedback',
            'keeps_animals' => 'keeps_animals',
            'available_for_training' => 'available_for_training',
        ];

        $normalized = strtolower(trim($documentAIFieldName));

        return $fieldMap[$normalized] ?? null;
    }

    /**
     * Detect MIME type from file extension
     * 
     * @param string $filePath
     * @return string
     */
    protected function detectMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'tiff' => 'image/tiff',
            default => 'application/octet-stream',
        };
    }
}
