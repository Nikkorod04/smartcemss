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
            $credentialsPath = storage_path('app/' . $credentialsFile);
            
            if (file_exists($credentialsPath)) {
                $this->ocrService = new OcrService();
            } else {
                \Log::info('Google credentials file not found at: ' . $credentialsPath);
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
        // If OCR service is not available, return error
        if (!$this->ocrService) {
            return [
                'success' => false,
                'error' => 'OCR service not configured. Please configure Google Cloud Vision credentials in storage/app/google-credentials.json'
            ];
        }

        try {
            $ocrResult = $this->ocrService->extractFromImage($file);
            
            if (!$ocrResult['success']) {
                return $ocrResult;
            }

            return [
                'success' => true,
                'text' => $ocrResult['text'],
                'type' => 'image',
                'ocr_method' => 'google_vision',
                'confidence' => $ocrResult['confidence'] ?? 0,
                'raw_text' => $ocrResult['text']
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to process image: ' . $e->getMessage()];
        }
    }

    /**
     * Parse PDF from file path using OCR with fallback
     * Used for converted PDFs from ImageToPdfService
     */
    protected function parsePdfFromPath(string $filePath): array
    {
        // Try OCR first if available
        if ($this->ocrService) {
            try {
                // Create a temporary uploaded file wrapper from the PDF path
                $pdfData = file_get_contents($filePath);
                $tempFile = tmpfile();
                fwrite($tempFile, $pdfData);
                rewind($tempFile);
                
                // Create an UploadedFile-like object
                $stream = $tempFile;
                $size = strlen($pdfData);
                
                // Use OcrService directly with PDF data
                $image = new \Google\Cloud\Vision\V1\Image();
                $image->setContent($pdfData);
                
                // Call the OcrService's extraction method
                $ocrResult = $this->ocrService->extractFromPdf(
                    new \Illuminate\Http\UploadedFile($filePath, 'converted.pdf', 'application/pdf', null, true)
                );
                
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
                \Log::warning('OCR processing failed for converted PDF: ' . $e->getMessage());
            }
        }

        // Fallback: use PDF parser for text-based PDFs
        try {
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
}

