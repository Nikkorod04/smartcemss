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

        try {
            // Initialize DocumentProcessorServiceClient with explicit credentials
            $this->client = new DocumentProcessorServiceClient([
                'credentials' => $credentialsPath,
            ]);
            
            $this->processorName = "projects/{$projectId}/locations/us/processors/{$processorId}";
            
            Log::info('Google Document AI Service initialized', [
                'processor' => $this->processorName,
                'credentials_path' => $credentialsPath,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Document AI Service', [
                'error' => $e->getMessage(),
                'credentials_path' => $credentialsPath,
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
     * Complete mapping of all 74 fields from the assessment form
     * 
     * @param string $documentAIFieldName Field name from Document AI
     * @return string|null Normalized field name or null if unmapped
     */
    protected function normalizeFieldName(string $documentAIFieldName): ?string
    {
        // Complete field mapping for all assessment form fields
        $fieldMap = [
            // SECTION I: Respondent Information (8 fields)
            'first_name' => 'respondent_first_name',
            'first name' => 'respondent_first_name',
            'middle_name' => 'respondent_middle_name',
            'middle name' => 'respondent_middle_name',
            'last_name' => 'respondent_last_name',
            'last name' => 'respondent_last_name',
            'age' => 'respondent_age',
            'civil_status' => 'respondent_civil_status',
            'civil status' => 'respondent_civil_status',
            'marital_status' => 'respondent_civil_status',
            'sex' => 'respondent_sex',
            'gender' => 'respondent_sex',
            'religion' => 'respondent_religion',
            'educational_attainment' => 'respondent_educational_attainment',
            'educational attainment' => 'respondent_educational_attainment',

            // SECTION II: Family Composition (2 fields)
            'family_adults' => 'family_adults',
            'adults' => 'family_adults',
            'number_of_adults' => 'family_adults',
            'family_children' => 'family_children',
            'children' => 'family_children',
            'number_of_children' => 'family_children',

            // SECTION III: Economic Aspect (3 fields)
            'livelihood_options' => 'livelihood_options',
            'livelihood' => 'livelihood_options',
            'interested_in_livelihood_training' => 'interested_in_livelihood_training',
            'interested in livelihood training' => 'interested_in_livelihood_training',
            'livelihood training' => 'interested_in_livelihood_training',
            'desired_training' => 'desired_training',
            'desired training' => 'desired_training',

            // SECTION IV: Educational Aspect (6 fields)
            'barangay_educational_facilities' => 'barangay_educational_facilities',
            'barangay educational facilities' => 'barangay_educational_facilities',
            'educational facilities' => 'barangay_educational_facilities',
            'household_member_currently_studying' => 'household_member_currently_studying',
            'household member currently studying' => 'household_member_currently_studying',
            'interested_in_continuing_studies' => 'interested_in_continuing_studies',
            'interested in continuing studies' => 'interested_in_continuing_studies',
            'areas_of_educational_interest' => 'areas_of_educational_interest',
            'areas of educational interest' => 'areas_of_educational_interest',
            'preferred_training_time' => 'preferred_training_time',
            'preferred training time' => 'preferred_training_time',
            'preferred_training_days' => 'preferred_training_days',
            'preferred training days' => 'preferred_training_days',

            // SECTION V: Health, Sanitation & Environmental (11 fields)
            'common_illnesses' => 'common_illnesses',
            'common illnesses' => 'common_illnesses',
            'action_when_sick' => 'action_when_sick',
            'action when sick' => 'action_when_sick',
            'barangay_medical_supplies_available' => 'barangay_medical_supplies_available',
            'barangay medical supplies available' => 'barangay_medical_supplies_available',
            'has_barangay_health_programs' => 'has_barangay_health_programs',
            'barangay health programs' => 'has_barangay_health_programs',
            'has barangay health programs' => 'has_barangay_health_programs',
            'benefits_from_barangay_programs' => 'benefits_from_barangay_programs',
            'benefits from barangay programs' => 'benefits_from_barangay_programs',
            'programs_benefited_from' => 'programs_benefited_from',
            'programs benefited from' => 'programs_benefited_from',
            'water_source' => 'water_source',
            'water source' => 'water_source',
            'water_source_distance' => 'water_source_distance',
            'water source distance' => 'water_source_distance',
            'garbage_disposal_method' => 'garbage_disposal_method',
            'garbage disposal' => 'garbage_disposal_method',
            'has_own_toilet' => 'has_own_toilet',
            'own toilet' => 'has_own_toilet',
            'toilet_type' => 'toilet_type',
            'toilet type' => 'toilet_type',
            'keeps_animals' => 'keeps_animals',
            'keeps animals' => 'keeps_animals',
            'animals_kept' => 'animals_kept',
            'animals kept' => 'animals_kept',

            // SECTION VI: Housing and Basic Amenities (5 fields)
            'house_type' => 'house_type',
            'house type' => 'house_type',
            'tenure_status' => 'tenure_status',
            'tenure status' => 'tenure_status',
            'has_electricity' => 'has_electricity',
            'electricity' => 'has_electricity',
            'light_source_without_power' => 'light_source_without_power',
            'light source' => 'light_source_without_power',
            'appliances_owned' => 'appliances_owned',
            'appliances' => 'appliances_owned',

            // SECTION VII: Recreational Facilities (7 fields)
            'barangay_recreational_facilities' => 'barangay_recreational_facilities',
            'recreational facilities' => 'barangay_recreational_facilities',
            'use_of_free_time' => 'use_of_free_time',
            'use of free time' => 'use_of_free_time',
            'member_of_organization' => 'member_of_organization',
            'member of organization' => 'member_of_organization',
            'organization_types' => 'organization_types',
            'organizational type' => 'organization_types',
            'organization_meeting_frequency' => 'organization_meeting_frequency',
            'meeting frequency' => 'organization_meeting_frequency',
            'organization_usual_activities' => 'organization_usual_activities',
            'usual activities' => 'organization_usual_activities',
            'household_members_in_organization' => 'household_members_in_organization',
            'household members in organization' => 'household_members_in_organization',
            'position_in_organization' => 'position_in_organization',
            'position in organization' => 'position_in_organization',

            // SECTION VIII: Other Needs & Problems (7 fields)
            'family_problems' => 'family_problems',
            'family problems' => 'family_problems',
            'health_problems' => 'health_problems',
            'health problems' => 'health_problems',
            'educational_problems' => 'educational_problems',
            'educational problems' => 'educational_problems',
            'employment_problems' => 'employment_problems',
            'employment problems' => 'employment_problems',
            'infrastructure_problems' => 'infrastructure_problems',
            'infrastructure problems' => 'infrastructure_problems',
            'economic_problems' => 'economic_problems',
            'economic problems' => 'economic_problems',
            'security_problems' => 'security_problems',
            'security problems' => 'security_problems',

            // SECTION IX: Summary Barangay Service Ratings (4 fields)
            'barangay_service_ratings' => 'barangay_service_ratings',
            'service_ratings' => 'barangay_service_ratings',
            'general_feedback' => 'general_feedback',
            'general feedback' => 'general_feedback',
            'available_for_training' => 'available_for_training',
            'available for training' => 'available_for_training',
            'reason_not_available' => 'reason_not_available',
            'reason not available' => 'reason_not_available',
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
