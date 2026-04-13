<?php

namespace App\Services;

use Google\Cloud\DocumentAI\V1\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Support\Facades\Log;

/**
 * Google Document AI Form Parser Service
 * Initializes and uses Google Document AI to extract data from PDFs and images
 */
class GoogleDocumentAIService
{
    private DocumentProcessorServiceClient $client;
    private string $processorName;
    private string $projectId;

    public function __construct()
    {
        Log::info('GoogleDocumentAIService: Constructor called');
        
        // Get configuration values
        $this->projectId = config('services.google_docai.project_id');
        $processorId = config('services.google_docai.processor_id');
        $credentialsFile = config('services.google_docai.credentials_file');

        Log::info('GoogleDocumentAIService: Configuration loaded', [
            'project_id' => $this->projectId ?? 'NULL',
            'processor_id' => $processorId ? 'SET' : 'NULL',
            'credentials_file' => $credentialsFile ?? 'NULL',
        ]);

        // Validate all required config values
        if (!$this->projectId) {
            $error = 'Missing GOOGLE_CLOUD_PROJECT_ID. Add to .env file.';
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        if (!$processorId) {
            $error = 'Missing GOOGLE_DOCAI_PROCESSOR_ID. Add to .env file.';
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        if (!$credentialsFile) {
            $error = 'Missing GOOGLE_CREDENTIALS_FILE. Add to .env file.';
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        // Resolve credentials file path (handles Windows paths correctly)
        $credentialsPath = $this->resolveCredentialsPath($credentialsFile);

        Log::info('GoogleDocumentAIService: Resolved credentials path', [
            'original' => $credentialsFile,
            'resolved' => $credentialsPath,
            'exists' => file_exists($credentialsPath) ? 'YES' : 'NO',
        ]);

        // Check file exists
        if (!file_exists($credentialsPath)) {
            $error = "Credentials file not found: {$credentialsPath}. " .
                "Place google-credentials.json in storage/app/";
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        // Check file is readable
        if (!is_readable($credentialsPath)) {
            $error = "Credentials file not readable: {$credentialsPath}. Check permissions.";
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        // Validate JSON structure
        $credentials = json_decode(file_get_contents($credentialsPath), true);
        if (!$credentials) {
            $error = "Credentials file is invalid JSON: {$credentialsPath}";
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        if (!isset($credentials['type']) || $credentials['type'] !== 'service_account') {
            $error = "Credentials must be a service account JSON from Google Cloud Console";
            Log::error('GoogleDocumentAIService: ' . $error);
            throw new \Exception($error);
        }

        // Initialize the client
        try {
            Log::info('GoogleDocumentAIService: Initializing DocumentProcessorServiceClient', [
                'service_account' => $credentials['client_email'] ?? 'unknown',
                'credentials_path' => $credentialsPath,
            ]);

            $this->client = new DocumentProcessorServiceClient([
                'credentials' => $credentialsPath,
            ]);

            $this->processorName = $processorId;

            Log::info('GoogleDocumentAIService: ✓ SUCCESSFULLY INITIALIZED', [
                'processor_name' => $this->processorName,
                'project_id' => $this->projectId,
            ]);
        } catch (\Exception $e) {
            $error = 'Failed to initialize DocumentProcessorServiceClient: ' . $e->getMessage();
            Log::error('GoogleDocumentAIService: ✗ INITIALIZATION FAILED', [
                'error' => $error,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw new \Exception($error);
        }
    }

    /**
     * Resolve credentials file path - handles Windows + Linux
     */
    private function resolveCredentialsPath(string $credentialsFile): string
    {
        // If absolute path, use it
        if (preg_match('#^[A-Z]:[/\\\\]|^/#i', $credentialsFile)) {
            return $credentialsFile;
        }

        // Try storage/app/ first (recommended)
        $storagePath = storage_path('app' . DIRECTORY_SEPARATOR . $credentialsFile);
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        // Try project root
        $rootPath = base_path($credentialsFile);
        if (file_exists($rootPath)) {
            return $rootPath;
        }

        // Return storage path as default (user should place it there)
        return $storagePath;
    }

    /**
     * Process a file using Document AI Form Parser
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

            Log::info('GoogleDocumentAIService: Processing document', [
                'file_path' => $filePath,
                'file_size' => filesize($filePath) . ' bytes',
            ]);

            $fileContent = file_get_contents($filePath);
            if (!$fileContent) {
                return [
                    'success' => false,
                    'error' => "Could not read file: {$filePath}",
                ];
            }

            $mimeType = $this->detectMimeType($filePath);

            $rawDocument = new RawDocument([
                'content' => $fileContent,
                'mime_type' => $mimeType,
            ]);

            $request = new ProcessRequest([
                'name' => $this->processorName,
                'raw_document' => $rawDocument,
            ]);

            Log::info('GoogleDocumentAIService: Sending to Document AI API');
            
            $response = $this->client->processDocument($request);
            $document = $response->getDocument();

            Log::info('GoogleDocumentAIService: ✓ Document processed successfully', [
                'pages' => $document->getPages()->count(),
                'entities' => count($document->getEntities()),
            ]);

            return [
                'success' => true,
                'document' => $document,
                'extracted_data' => $this->extractFormData($document),
            ];
        } catch (\Exception $e) {
            Log::error('GoogleDocumentAIService: ✗ Processing failed', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            return [
                'success' => false,
                'error' => 'Document processing failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract form fields from document
     */
    private function extractFormData($document): array
    {
        $formFields = [];

        try {
            foreach ($document->getEntities() as $entity) {
                $fieldName = $this->normalizeFieldName($entity->getDisplayName());
                $fieldValue = $this->extractEntityValue($entity);

                if ($fieldName && $fieldValue !== null) {
                    $formFields[$fieldName] = $fieldValue;
                }
            }
        } catch (\Exception $e) {
            Log::warning('GoogleDocumentAIService: Error extracting form data', [
                'error' => $e->getMessage(),
            ]);
        }

        return $formFields;
    }

    /**
     * Extract value from entity
     */
    private function extractEntityValue($entity)
    {
        if ($entity->getMentionText()) {
            return trim($entity->getMentionText());
        }

        if (method_exists($entity, 'getProperties') && $entity->getProperties()) {
            $values = [];
            foreach ($entity->getProperties() as $prop) {
                if ($prop->getMentionText()) {
                    $values[] = trim($prop->getMentionText());
                }
            }
            return !empty($values) ? implode(', ', $values) : null;
        }

        return null;
    }

    /**
     * Normalize Document AI field names to database field names
     */
    private function normalizeFieldName(string $documentAIFieldName): ?string
    {
        $fieldMap = [
            'first_name' => 'respondent_first_name',
            'first name' => 'respondent_first_name',
            'middle_name' => 'respondent_middle_name',
            'middle name' => 'respondent_middle_name',
            'last_name' => 'respondent_last_name',
            'last name' => 'respondent_last_name',
            'age' => 'respondent_age',
            'civil_status' => 'respondent_civil_status',
            'civil status' => 'respondent_civil_status',
            'sex' => 'respondent_sex',
            'gender' => 'respondent_sex',
            'religion' => 'respondent_religion',
            'educational_attainment' => 'respondent_educational_attainment',
            'educational attainment' => 'respondent_educational_attainment',
        ];

        $normalized = strtolower(trim($documentAIFieldName));
        return $fieldMap[$normalized] ?? null;
    }

    /**
     * Detect MIME type
     */
    private function detectMimeType(string $filePath): string
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'tiff', 'tif' => 'image/tiff',
            default => 'application/octet-stream',
        };
    }
}
