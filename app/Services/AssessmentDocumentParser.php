<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Smalot\PdfParser\Parser as PdfParser;

class AssessmentDocumentParser
{
    protected PdfParser $pdfParser;
    protected ?OcrService $ocrService = null;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
        
        // Initialize OCR service only if Google credentials are available
        try {
            // Check for custom credentials filename from env, or use default
            $credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
            $credentialsPath = storage_path('app' . DIRECTORY_SEPARATOR . $credentialsFile);
            
            \Log::info('OCR Setup Check', [
                'credentials_file' => $credentialsFile,
                'full_path' => $credentialsPath,
                'exists' => file_exists($credentialsPath)
            ]);
            
            if (file_exists($credentialsPath)) {
                $this->ocrService = new OcrService();
                \Log::info('OcrService initialized successfully');
            } else {
                \Log::warning('Google credentials file not found at: ' . $credentialsPath);
            }
        } catch (\Exception $e) {
            // OCR service not available, will use fallback methods
            \Log::warning('OCR service initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Parse uploaded file based on type and extract text/data
     */
    public function parse(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'xlsx', 'xls' => $this->parseExcel($file),
            'csv' => $this->parseCsv($file),
            'docx' => $this->parseDocx($file),
            'pdf' => $this->parsePdf($file),
            'jpg', 'jpeg', 'png' => $this->parseImage($file),
            default => ['error' => 'Unsupported file type'],
        };
    }

    /**
     * Parse file from file path (used for converted PDFs from ImageToPdfService)
     */
    public function parseFromPath(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'error' => 'File not found: ' . $filePath];
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => $this->parsePdfFromPath($filePath),
            'xlsx', 'xls' => $this->parseExcelFromPath($filePath),
            'csv' => $this->parseCsvFromPath($filePath),
            'docx' => $this->parseDocxFromPath($filePath),
            default => ['error' => 'Unsupported file type: ' . $extension],
        };
    }

    /**
     * Parse Excel file and extract data
     */
    protected function parseExcel(UploadedFile $file): array
    {
        try {
            $data = [];
            $rows = Excel::toArray(null, $file);
            
            if (!empty($rows[0])) {
                $headers = [];
                $firstRow = true;

                foreach ($rows[0] as $row) {
                    if ($firstRow) {
                        $headers = array_filter($row);
                        $firstRow = false;
                        continue;
                    }

                    $rowData = [];
                    foreach ($row as $index => $value) {
                        if (isset($headers[$index]) && !empty($value)) {
                            $rowData[strtolower(str_replace(' ', '_', $headers[$index]))] = $value;
                        }
                    }

                    if (!empty($rowData)) {
                        $data[] = $rowData;
                    }
                }
            }

            return ['success' => true, 'data' => $data, 'type' => 'excel'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse Excel: ' . $e->getMessage()];
        }
    }

    /**
     * Parse CSV file and extract data
     */
    protected function parseCsv(UploadedFile $file): array
    {
        try {
            $data = [];
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $rowData = [];
                foreach ($row as $index => $value) {
                    if (isset($headers[$index]) && !empty($value)) {
                        $rowData[strtolower(str_replace(' ', '_', $headers[$index]))] = $value;
                    }
                }
                if (!empty($rowData)) {
                    $data[] = $rowData;
                }
            }
            fclose($handle);

            return ['success' => true, 'data' => $data, 'type' => 'csv'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse CSV: ' . $e->getMessage()];
        }
    }

    /**
     * Parse PDF file - try OCR first, then fallback to text extraction
     */
    protected function parsePdf(UploadedFile $file): array
    {
        // Try OCR first if available
        if ($this->ocrService) {
            try {
                $ocrResult = $this->ocrService->extractFromPdf($file);
                if ($ocrResult['success']) {
                    return [
                        'success' => true,
                        'text' => $ocrResult['text'],
                        'type' => 'pdf',
                        'raw_text' => $ocrResult['text'],
                        'ocr_method' => 'google_vision',
                        'confidence' => $ocrResult['confidence'] ?? 0
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('OCR processing failed for PDF: ' . $e->getMessage());
            }
        }

        // Fallback: use PDF parser for text-based PDFs
        try {
            $pdf = $this->pdfParser->parseFile($file->getRealPath());
            $text = $pdf->getText();

            return [
                'success' => true,
                'text' => $text,
                'type' => 'pdf',
                'raw_text' => $text,
                'ocr_method' => 'pdf_parser_fallback'
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse PDF: ' . $e->getMessage()];
        }
    }

    /**
     * Parse image file using Google Cloud Vision OCR
     */
    protected function parseImage(UploadedFile $file): array
    {
        // If OCR service is not available, try to re-initialize it
        if (!$this->ocrService) {
            try {
                $credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
                $credentialsPath = storage_path('app' . DIRECTORY_SEPARATOR . $credentialsFile);
                
                if (file_exists($credentialsPath)) {
                    $this->ocrService = new OcrService();
                    \Log::info('OcrService re-initialized for image parsing');
                } else {
                    \Log::error('Credentials file not found for image parsing', [
                        'path' => $credentialsPath,
                        'env_var' => $credentialsFile
                    ]);
                    return [
                        'success' => false,
                        'error' => 'OCR service not configured. Please ensure Google Cloud Vision credentials are placed in storage/app/' . $credentialsFile
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Failed to initialize OcrService for image', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage()
                ]);
                return [
                    'success' => false,
                    'error' => 'Failed to initialize OCR service: ' . $e->getMessage()
                ];
            }
        }

        try {
            $ocrResult = $this->ocrService->extractFromImage($file);
            
            if (!$ocrResult['success']) {
                return $ocrResult;
            }

            // Sanitize extracted text
            $sanitizedText = $this->sanitizeUtf8($ocrResult['text']);

            return [
                'success' => true,
                'text' => $sanitizedText,
                'type' => 'image',
                'ocr_method' => 'google_vision',
                'confidence' => $ocrResult['confidence'] ?? 0,
                'raw_text' => $sanitizedText
            ];
        } catch (\Exception $e) {
            \Log::error('Image parsing failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => 'Failed to process image: ' . $e->getMessage()];
        }
    }

    /**
     * Parse DOCX file and extract text
     */
    protected function parseDocx(UploadedFile $file): array
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getRealPath());
            $text = '';
            
            // Extract text from all sections
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\Paragraph) {
                        foreach ($element->getElements() as $childElement) {
                            if ($childElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                $text .= $childElement->getText();
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
            
            // Clean up the text
            $text = trim($text);
            
            if (empty($text)) {
                return ['success' => false, 'error' => 'No text content found in DOCX file'];
            }
            
            return [
                'success' => true,
                'data' => [['content' => $text]],
                'text' => $text,
                'type' => 'docx',
                'raw_text' => $text
            ];
        } catch (\Exception $e) {
            \Log::error('DOCX parsing failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => 'Failed to parse DOCX: ' . $e->getMessage()];
        }
    }

    /**
     * Parse DOCX from file path
     */
    protected function parseDocxFromPath(string $filePath): array
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
            $text = '';
            
            // Extract text from all sections
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\Paragraph) {
                        foreach ($element->getElements() as $childElement) {
                            if ($childElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                $text .= $childElement->getText();
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
            
            // Clean up the text
            $text = trim($text);
            
            if (empty($text)) {
                return ['success' => false, 'error' => 'No text content found in DOCX file'];
            }
            
            return [
                'success' => true,
                'data' => [['content' => $text]],
                'text' => $text,
                'type' => 'docx',
                'raw_text' => $text
            ];
        } catch (\Exception $e) {
            \Log::error('DOCX parsing from path failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => 'Failed to parse DOCX: ' . $e->getMessage()];
        }
    }

    /**
     * Parse PDF from file path using OCR with fallback
     * Used for converted PDFs from ImageToPdfService
     */
    protected function parsePdfFromPath(string $filePath): array
    {
        \Log::info('Parsing PDF from path', [
            'file_path' => $filePath,
            'ocr_available' => $this->ocrService !== null
        ]);
        
        // Try OCR first if available
        if ($this->ocrService) {
            try {
                \Log::info('Attempting OCR extraction from PDF');
                
                // Create an UploadedFile-like object
                $uploadedFile = new \Illuminate\Http\UploadedFile($filePath, 'converted.pdf', 'application/pdf', null, true);
                
                $ocrResult = $this->ocrService->extractFromPdf($uploadedFile);
                
                \Log::info('OCR extraction result', [
                    'success' => $ocrResult['success'] ?? false,
                    'has_text' => isset($ocrResult['text']) && !empty($ocrResult['text']),
                    'method' => $ocrResult['ocr_method'] ?? null
                ]);
                
                if ($ocrResult && $ocrResult['success']) {
                    return [
                        'success' => true,
                        'text' => $ocrResult['text'],
                        'type' => 'pdf',
                        'raw_text' => $ocrResult['text'],
                        'ocr_method' => 'google_vision',
                        'confidence' => $ocrResult['confidence'] ?? 0
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('OCR processing failed for converted PDF: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'code' => $e->getCode()
                ]);
            }
        } else {
            \Log::info('OCR service is NOT available, using fallback PDF parser');
        }

        // Fallback: use PDF parser for text-based PDFs
        try {
            \Log::info('Using PDF parser fallback');
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();

            return [
                'success' => true,
                'text' => $text,
                'type' => 'pdf',
                'raw_text' => $text,
                'ocr_method' => 'pdf_parser_fallback'
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse PDF: ' . $e->getMessage()];
        }
    }

    /**
     * Parse CSV from file path
     */
    protected function parseCsvFromPath(string $filePath): array
    {
        try {
            $data = [];
            $handle = fopen($filePath, 'r');
            $headers = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $rowData = [];
                foreach ($row as $index => $value) {
                    if (isset($headers[$index]) && !empty($value)) {
                        $rowData[strtolower(str_replace(' ', '_', $headers[$index]))] = $value;
                    }
                }
                if (!empty($rowData)) {
                    $data[] = $rowData;
                }
            }
            fclose($handle);

            return ['success' => true, 'data' => $data, 'type' => 'csv'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse CSV: ' . $e->getMessage()];
        }
    }

    /**
     * Parse Excel from file path
     */
    protected function parseExcelFromPath(string $filePath): array
    {
        try {
            $data = [];
            $rows = Excel::toArray(null, $filePath);
            
            if (!empty($rows[0])) {
                $headers = [];
                $firstRow = true;

                foreach ($rows[0] as $row) {
                    if ($firstRow) {
                        $headers = array_filter($row);
                        $firstRow = false;
                        continue;
                    }

                    $rowData = [];
                    foreach ($row as $index => $value) {
                        if (isset($headers[$index]) && !empty($value)) {
                            $rowData[strtolower(str_replace(' ', '_', $headers[$index]))] = $value;
                        }
                    }

                    if (!empty($rowData)) {
                        $data[] = $rowData;
                    }
                }
            }

            return ['success' => true, 'data' => $data, 'type' => 'excel'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse Excel: ' . $e->getMessage()];
        }
    }

    /**
     * Calculate confidence from Vision API response
     */
    protected function calculateConfidenceFromResponse($response): float
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
     * Sanitize text to ensure valid UTF-8 encoding
     */
    protected function sanitizeUtf8(string $text): string
    {
        // Use iconv to remove invalid UTF-8 sequences
        $sanitized = iconv('UTF-8', 'UTF-8//IGNORE', $text);
        
        if ($sanitized === false) {
            // Fallback: remove control characters
            $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
        }
        
        // Clean up excessive whitespace
        $sanitized = preg_replace('/\s+/', ' ', $sanitized);
        
        return trim($sanitized);
    }
}

