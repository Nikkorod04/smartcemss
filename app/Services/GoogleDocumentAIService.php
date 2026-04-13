<?php

namespace App\Services;

use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
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
     * Process a file using Document AI - with Prediction endpoint fallback
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

            Log::info('GoogleDocumentAIService: Sending to Document AI API (SDK method)');
            
            try {
                $response = $this->client->processDocument($request);
                $document = $response->getDocument();

                Log::info('GoogleDocumentAIService: ✓ Document processed successfully (SDK)', [
                    'pages' => $document->getPages()->count(),
                    'entities' => count($document->getEntities()),
                ]);

                $extractedData = $this->extractFormData($document);
                
                // If SDK extraction returned no data, try Prediction endpoint
                if (empty($extractedData)) {
                    Log::info('GoogleDocumentAIService: SDK returned no data, trying Prediction endpoint');
                    return $this->processDocumentViaPredictionEndpoint($fileContent, $mimeType);
                }

                return [
                    'success' => true,
                    'document' => $document,
                    'extracted_data' => $extractedData,
                    'method' => 'SDK',
                ];
            } catch (\Exception $sdkError) {
                Log::warning('GoogleDocumentAIService: SDK method failed, falling back to Prediction endpoint', [
                    'error' => $sdkError->getMessage(),
                ]);
                
                // Fall back to Prediction endpoint
                return $this->processDocumentViaPredictionEndpoint($fileContent, $mimeType);
            }
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
     * Process document using REST Prediction endpoint
     * More reliable for text extraction from OCR
     */
    private function processDocumentViaPredictionEndpoint(string $fileContent, string $mimeType): array
    {
        try {
            // Load credentials to get access token
            $credentialsPath = $this->resolveCredentialsPath(config('services.google_docai.credentials_file'));
            $credentials = json_decode(file_get_contents($credentialsPath), true);
            
            if (!$credentials) {
                throw new \Exception('Invalid credentials file');
            }

            // Get access token
            $accessToken = $this->getAccessToken($credentials, $credentialsPath);
            
            if (!$accessToken) {
                throw new \Exception('Could not obtain access token');
            }

            // Prepare request
            $encodedContent = base64_encode($fileContent);
            
            $requestBody = [
                'rawDocument' => [
                    'content' => $encodedContent,
                    'mimeType' => $mimeType,
                ],
            ];

            $endpoint = "https://us-documentai.googleapis.com/v1/{$this->processorName}:process";
            
            Log::info('GoogleDocumentAIService: Sending to Prediction endpoint', [
                'endpoint' => $endpoint,
                'content_length' => strlen($fileContent),
                'mime_type' => $mimeType,
            ]);

            // Make HTTP request
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $endpoint,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS => json_encode($requestBody),
                CURLOPT_FOLLOWLOCATION => true,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception("cURL error: {$curlError}");
            }

            if ($httpCode !== 200) {
                $errorData = json_decode($response, true);
                throw new \Exception("HTTP {$httpCode}: " . ($errorData['error']['message'] ?? 'Unknown error'));
            }

            $responseData = json_decode($response, true);
            
            Log::info('GoogleDocumentAIService: ✓ Document processed via Prediction endpoint', [
                'pages' => count($responseData['document']['pages'] ?? []),
                'has_text' => isset($responseData['document']['text']),
            ]);

            // Extract text from JSON response
            $extractedData = $this->extractTextFromPredictionResponse($responseData);

            return [
                'success' => true,
                'extracted_data' => $extractedData,
                'method' => 'Prediction_Endpoint',
                'raw_response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('GoogleDocumentAIService: Prediction endpoint failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'error' => 'Prediction endpoint failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract text from Prediction endpoint JSON response
     */
    private function extractTextFromPredictionResponse(array $response): array
    {
        $allText = '';

        try {
            // Extract document-level text
            if (isset($response['document']['text'])) {
                $allText = $response['document']['text'];
                Log::info('Extracted text from document.text field', [
                    'length' => strlen($allText),
                ]);
            }

            // Also try pages if main text is empty
            if (empty($allText) && isset($response['document']['pages'])) {
                foreach ($response['document']['pages'] as $page) {
                    if (isset($page['layout']['textAnchor']['content'])) {
                        $allText .= $page['layout']['textAnchor']['content'] . "\n";
                    }
                }
                Log::info('Extracted text from pages', [
                    'length' => strlen($allText),
                ]);
            }

            Log::info('Prediction response text extraction', [
                'total_length' => strlen($allText),
                'has_content' => !empty($allText),
                'preview' => substr($allText, 0, 200),
            ]);

            // Apply regex patterns to extracted text
            if (!empty($allText)) {
                return $this->extractFieldsFromText($allText);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Error parsing Prediction response', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Get access token from service account credentials
     */
    private function getAccessToken(array $credentials, string $credentialsPath): ?string
    {
        try {
            // Service account credentials typically include client_email and private_key
            if (!isset($credentials['private_key']) || !isset($credentials['client_email'])) {
                Log::error('Missing private_key or client_email in credentials');
                return null;
            }

            // Using JWT to get access token (simplified)
            // In production, consider using google/auth library
            $now = time();
            $payload = [
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/cloud-platform',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            // Create JWT
            $jwt = $this->createJWT($payload, $credentials['private_key']);

            // Exchange JWT for access token
            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]),
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            
            if (isset($data['access_token'])) {
                Log::debug('Successfully obtained access token');
                return $data['access_token'];
            } else {
                Log::error('Failed to get access token', ['response' => $data]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error getting access token', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create JWT for service account authentication
     */
    private function createJWT(array $payload, string $privateKey): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $payload = json_encode($payload);

        $headerEncoded = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $payloadEncoded = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        $signature = '';

        openssl_sign("{$headerEncoded}.{$payloadEncoded}", $signature, $privateKey, 'sha256WithRSAEncryption');
        $signatureEncoded = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    }

    /**
     * Extract form fields from document using OCR text extraction
     * Extracts all text and applies regex patterns (no entity-based extraction)
     */
    private function extractFormData($document): array
    {
        $formFields = [];

        try {
            // Extract all text from the document using comprehensive approach
            $documentText = $this->extractDocumentText($document);
            
            Log::info('GoogleDocumentAIService: Document text extraction', [
                'total_length' => strlen($documentText),
                'has_content' => !empty($documentText),
                'preview' => substr($documentText, 0, 200),
            ]);
            
            // If we got text, apply regex patterns
            if (!empty($documentText)) {
                $formFields = $this->extractFieldsFromText($documentText);
                
                Log::info('GoogleDocumentAIService: Regex extraction complete', [
                    'fields_extracted' => count($formFields),
                    'field_names' => array_keys($formFields),
                ]);
            } else {
                Log::warning('GoogleDocumentAIService: No text extracted from document');
            }
        } catch (\Exception $e) {
            Log::error('GoogleDocumentAIService: Error extracting form data', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return $formFields;
    }

    /**
     * Extract fields from raw text using regex patterns
     * Covers all 70+ assessment form fields across 9 sections
     */
    private function extractFieldsFromText(string $text): array
    {
        $fields = [];
        
        // Comprehensive regex patterns for assessment form fields
        $patterns = [
            // SECTION I: Identifying Information
            'respondent_first_name' => [
                '/first\s*name\s*[:\-]?\s*([A-Za-z\s]+)(?=[,\n]|$)/im',
                '/respondent[\'s]?\s*first\s*name\s*[:\-]?\s*([A-Za-z\s]+)/im',
            ],
            'respondent_middle_name' => [
                '/middle\s*name\s*[:\-]?\s*([A-Za-z\s]+)(?=[,\n]|$)/im',
                '/respondent[\'s]?\s*middle\s*name\s*[:\-]?\s*([A-Za-z\s]+)/im',
            ],
            'respondent_last_name' => [
                '/last\s*name\s*[:\-]?\s*([A-Za-z\s]+)(?=[,\n]|$)/im',
                '/respondent[\'s]?\s*last\s*name\s*[:\-]?\s*([A-Za-z\s]+)/im',
            ],
            'respondent_age' => [
                '/age\s*[:\-]?\s*(\d+)\s*(?:years|yo|yrs)?/im',
                '/respondent[\'s]?\s*age\s*[:\-]?\s*(\d+)/im',
            ],
            'respondent_civil_status' => [
                '/civil\s*status\s*[:\-]?\s*(single|married|divorced|widowed|separated)[\s\n]?/im',
                '/marital\s*status\s*[:\-]?\s*(single|married|divorced|widowed|separated)[\s\n]?/im',
            ],
            'respondent_sex' => [
                '/sex\s*[:\-]?\s*(male|female|m|f)[\s\n]/im',
                '/gender\s*[:\-]?\s*(male|female|m|f)[\s\n]/im',
            ],
            'respondent_religion' => [
                '/religion\s*[:\-]?\s*([A-Za-z\s]+)(?=[,\n]|$)/im',
                '/respondent[\'s]?\s*religion\s*[:\-]?\s*([A-Za-z\s]+)/im',
            ],
            'respondent_educational_attainment' => [
                '/(elementary|high\s*school|college|university|vocational)[\s]?/im',
            ],
            
            // SECTION II: Family Composition
            'family_adults' => [
                '/(?:number\s*of\s*)?adults?\s*(?:in\s*(?:the\s*)?household)?\s*[:\-]?\s*(\d+)/im',
                '/household.*adults?\s*[:\-]?\s*(\d+)/im',
            ],
            'family_children' => [
                '/(?:number\s*of\s*)?children?\s*(?:in\s*(?:the\s*)?household)?\s*[:\-]?\s*(\d+)/im',
                '/household.*children?\s*[:\-]?\s*(\d+)/im',
            ],
            
            // SECTION III: Economic
            'livelihood_options' => [
                '/(farming|trading|fishing|manufacturing|service|construction|livestock)[\s]?/im',
            ],
            'interested_in_livelihood_training' => [
                '/interested\s*(?:in\s*)?livelihood\s*(?:training)?\s*[:\-]?\s*(yes|no)/im',
            ],
            'desired_training' => [
                '/(livestock\s*raising|organic\s*farming|handicraft|tourism|technology)[\s]?/im',
            ],
            
            // SECTION IV: Educational
            'barangay_educational_facilities' => [
                '/(public\s*elementary|public\s*high\s*school|private\s*school)[\s]?/im',
            ],
            'household_member_currently_studying' => [
                '/household\s*member\s*(?:currently\s*)?studying\s*[:\-]?\s*(yes|no)/im',
            ],
            'interested_in_continuing_studies' => [
                '/interested\s*(?:in\s*)?continuing\s*studies\s*[:\-]?\s*(yes|no)/im',
            ],
            'areas_of_educational_interest' => [
                '/(technology|agriculture|health|business|arts)[\s]?/im',
            ],
            'preferred_training_time' => [
                '/preferred\s*(?:training\s*)?time\s*[:\-]?\s*(morning|afternoon|evening)[\s]?/im',
            ],
            'preferred_training_days' => [
                '/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)[\s]?/im',
            ],
            
            // SECTION V: Health & Sanitation
            'common_illnesses' => [
                '/(flu|cough|fever|diarrhea|hypertension|diabetes|malaria)[\s]?/im',
            ],
            'action_when_sick' => [
                '/(?:action\s*)?when\s*sick\s*[:\-]?\s*([^,\n]+)/im',
                '/(buy\s*medicine|visit\s*doctor|herbal\s*remedy|rest\s*at\s*home)[\s]?/im',
            ],
            'barangay_medical_supplies_available' => [
                '/(bandages|paracetamol|antibiotics|syringes)[\s]?/im',
            ],
            'has_barangay_health_programs' => [
                '/barangay\s*(?:has\s*)?health\s*programs?\s*[:\-]?\s*(yes|no)/im',
            ],
            'benefits_from_barangay_programs' => [
                '/benefits?\s*(?:from\s*)?barangay\s*programs?\s*[:\-]?\s*(yes|no)/im',
            ],
            'programs_benefited_from' => [
                '/(health\s*center|medicine\s*access|vaccination|family\s*planning)[\s]?/im',
            ],
            'water_source' => [
                '/(artesian\s*well|spring\s*water|rainwater|deep\s*well|tap\s*water)[\s]?/im',
            ],
            'water_source_distance' => [
                '/(?:water\s*)?distance\s*[:\-]?\s*(just\s*outside|near|far|\d+\s*meters?)/im',
            ],
            'garbage_disposal_method' => [
                '/(burning|burying|composting|designated\s*pit|dump)[\s]?/im',
            ],
            'has_own_toilet' => [
                '/(?:has\s*)?(?:own\s*)?toilet\s*[:\-]?\s*(yes|no)/im',
            ],
            'toilet_type' => [
                '/(concrete\s*septic|water\s*sealed|pit|flush|portable)[\s]?/im',
            ],
            'keeps_animals' => [
                '/(?:keeps?\s*)?animals?\s*[:\-]?\s*(yes|no)/im',
            ],
            'animals_kept' => [
                '/(goats?|chickens?|pigs?|cows?|ducks?|rabbits?)[\s]?/im',
            ],
            
            // SECTION VI: Housing
            'house_type' => [
                '/(concrete|wood|bamboo|nipa|mixed)[\s]?/im',
            ],
            'tenure_status' => [
                '/(owner|renter|caretaker|shared)[\s]?/im',
            ],
            'has_electricity' => [
                '/(?:has\s*)?electricity\s*[:\-]?\s*(yes|no)/im',
            ],
            'light_source_without_power' => [
                '/(incandescent\s*bulbs?|fluorescent|kerosene|solar|candles?)[\s]?/im',
            ],
            'appliances_owned' => [
                '/(television|refrigerator|fan|radio|washing\s*machine)[\s]?/im',
            ],
            
            // SECTION VII: Recreation & Organizations
            'barangay_recreational_facilities' => [
                '/(plaza|sports\s*ground|basketball\s*court|swimming\s*pool|community\s*center)[\s]?/im',
            ],
            'use_of_free_time' => [
                '/(watching\s*tv|sports|visiting|reading|games)[\s]?/im',
            ],
            'member_of_organization' => [
                '/(?:member\s*)?(?:of\s*)?(?:any\s*)?organization\s*[:\-]?\s*(yes|no)/im',
            ],
            'organization_types' => [
                '/(cooperative|church|youth|women|farmers)[\s]?/im',
            ],
            'organization_meeting_frequency' => [
                '/(?:meeting\s*)?frequency\s*[:\-]?\s*(weekly|monthly|yearly)[\s]?/im',
            ],
            'position_in_organization' => [
                '/(?:position\s*(?:in\s*)?(?:the\s*)?organization)\s*[:\-]?\s*([A-Za-z\s]+)(?=[,\n]|$)/im',
            ],
            
            // SECTION VIII: Problem Identification
            'family_problems' => [
                '/(conflicts|unemployment|substance\s*abuse|domestic\s*violence)[\s]?/im',
            ],
            'health_problems' => [
                '/(malnutrition|untreated\s*illnesses?|mental\s*health)[\s]?/im',
            ],
            'educational_problems' => [
                '/(high\s*dropout\s*rate|poor\s*facilities|lack\s*of\s*teachers)[\s]?/im',
            ],
            'employment_problems' => [
                '/(unemployment|underemployment|low\s*wages|lack\s*of\s*skills)[\s]?/im',
            ],
            'infrastructure_problems' => [
                '/(bad\s*roads?|lack\s*of\s*water|electricity\s*shortage)[\s]?/im',
            ],
            'economic_problems' => [
                '/(lack\s*of\s*buyers|no\s*capital|no\s*livelihood|many\s*dependents)[\s]?/im',
            ],
            'security_problems' => [
                '/(noisy|theft|police|crime)[\s]?/im',
            ],
            
            // SECTION IX: Services & Feedback
            'general_feedback' => [
                '/(?:feedback|remarks?|comments?)\s*[:\-]?\s*([^,\n]+)/im',
            ],
            'available_for_training' => [
                '/available?\s*(?:for\s*)?(?:training)?\s*[:\-]?\s*(yes|no)/im',
            ],
            'reason_not_available' => [
                '/(?:reason|why)\s*(?:not\s*)?available\s*[:\-]?\s*([^,\n]+)/im',
            ],
        ];
        
        // Fields that can have multiple matches (arrays/checkboxes)
        $arrayFields = [
            'respondent_educational_attainment',
            'livelihood_options', 'desired_training',
            'barangay_educational_facilities', 'areas_of_educational_interest', 'preferred_training_days',
            'common_illnesses', 'action_when_sick', 'barangay_medical_supplies_available', 'programs_benefited_from',
            'water_source', 'garbage_disposal_method', 'toilet_type', 'animals_kept',
            'house_type', 'tenure_status', 'light_source_without_power', 'appliances_owned',
            'barangay_recreational_facilities', 'use_of_free_time', 'organization_types', 'household_members_in_organization',
            'family_problems', 'health_problems', 'educational_problems', 'employment_problems',
            'infrastructure_problems', 'economic_problems', 'security_problems',
            'barangay_service_ratings',
        ];
        
        // Extract fields from text
        foreach ($patterns as $fieldName => $patternList) {
            $isArrayField = in_array($fieldName, $arrayFields);
            
            foreach ($patternList as $pattern) {
                if ($isArrayField) {
                    // For array/checkbox fields, find all matches
                    if (preg_match_all($pattern, $text, $matches)) {
                        $values = [];
                        $seenValues = []; // Track seen values (case-insensitive)
                        
                        foreach ($matches[1] as $match) {
                            $value = trim($match);
                            if (!empty($value) && $value !== '0') {
                                // Normalize for deduplication (lowercase for comparison)
                                $normalizedValue = strtolower($value);
                                
                                // Skip if we've already seen this value
                                if (!isset($seenValues[$normalizedValue])) {
                                    $seenValues[$normalizedValue] = true;
                                    // Keep original case but trimmed
                                    $values[] = $value;
                                }
                            }
                        }
                        
                        if (!empty($values)) {
                            $fields[$fieldName] = $values;
                            Log::debug("Array field deduplicated", [
                                'field' => $fieldName,
                                'total_matches' => count($matches[1]),
                                'unique_values' => count($values),
                                'values' => array_slice($values, 0, 5), // Show first 5
                            ]);
                            break;
                        }
                    }
                } else {
                    // For single-value fields, get first match
                    if (preg_match($pattern, $text, $matches)) {
                        $value = trim($matches[1]);
                        if (!empty($value) && $value !== '0') {
                            $fields[$fieldName] = $value;
                            break;
                        }
                    }
                }
            }
        }
        
        Log::info('Regex-based extraction complete', [
            'fields_found' => count($fields),
            'field_names' => array_keys($fields),
        ]);
        
        return $fields;
    }

    /**
     * Extract text from a page or block - handles OCR and Form Parser structures
     */
    private function extractBlockText($block): ?string
    {
        try {
            $text = '';
            
            // Try getting full document text first (OCR often uses this)
            if (method_exists($block, 'getFullText') && $block->getFullText()) {
                return $block->getFullText();
            }
            
            // Try direct getText() method
            if (method_exists($block, 'getText') && $block->getText()) {
                return $block->getText();
            }
            
            // Try via TextAnchor and TextSegments
            if (method_exists($block, 'getTextAnchor')) {
                $anchor = $block->getTextAnchor();
                if ($anchor && method_exists($anchor, 'getTextSegments')) {
                    $segments = $anchor->getTextSegments();
                    if ($segments && count($segments) > 0) {
                        foreach ($segments as $segment) {
                            if (method_exists($segment, 'getText')) {
                                $text .= $segment->getText();
                            }
                        }
                        if (!empty($text)) {
                            return $text;
                        }
                    }
                }
            }
            
            // Try via Layout.Blocks recursion (for nested structure)
            if (method_exists($block, 'getLayout')) {
                $layout = $block->getLayout();
                if ($layout && method_exists($layout, 'getBlocks')) {
                    $nestedBlocks = $layout->getBlocks();
                    if ($nestedBlocks && count($nestedBlocks) > 0) {
                        foreach ($nestedBlocks as $nestedBlock) {
                            $blockText = $this->extractBlockText($nestedBlock);
                            if ($blockText) {
                                $text .= $blockText . ' ';
                            }
                        }
                        if (!empty($text)) {
                            return trim($text);
                        }
                    }
                }
            }
            
            return !empty($text) ? trim($text) : null;
        } catch (\Exception $e) {
            Log::debug('Error extracting block text', [
                'error' => $e->getMessage(),
                'block_class' => get_class($block),
            ]);
            return null;
        }
    }

    /**
     * Extract text from document using multiple approaches
     * This handles both OCR and Form Parser document structures
     */
    private function extractDocumentText($document): string
    {
        $allText = '';
        
        try {
            // Approach 1: Try document-level full text (works for some OCR results)
            if (method_exists($document, 'getText') && $document->getText()) {
                $allText = $document->getText();
                Log::info('Extracted text via Document.getText()', [
                    'text_length' => strlen($allText),
                ]);
                return $allText;
            }
            
            // Approach 2: Try extracting from pages
            if (method_exists($document, 'getPages') && $document->getPages()) {
                foreach ($document->getPages() as $pageIndex => $page) {
                    // Try page-level getText
                    if (method_exists($page, 'getText') && $page->getText()) {
                        $pageText = $page->getText();
                        $allText .= $pageText . "\n";
                        Log::debug('Page text via getText()', [
                            'page' => $pageIndex + 1,
                            'length' => strlen($pageText),
                        ]);
                        continue;
                    }
                    
                    // Try via blocks
                    if (method_exists($page, 'getBlocks') && $page->getBlocks()) {
                        foreach ($page->getBlocks() as $block) {
                            $blockText = $this->extractBlockText($block);
                            if ($blockText) {
                                $allText .= $blockText . "\n";
                            }
                        }
                    }
                }
            }
            
            // Approach 3: Fallback - try any available text extraction method
            if (empty($allText)) {
                // Log available methods for debugging
                $methods = get_class_methods($document);
                $textMethods = array_filter($methods, function($m) {
                    return stripos($m, 'text') !== false || stripos($m, 'get') === 0;
                });
                
                Log::warning('No text extracted, available text-related methods:', [
                    'methods' => array_slice($textMethods, 0, 10),
                    'total_methods' => count($textMethods),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error extracting document text', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
        
        return trim($allText);
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
