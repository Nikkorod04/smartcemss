<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Smalot\PdfParser\Parser as PdfParser;

class AssessmentDocumentParser
{
    protected PdfParser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
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
     * Parse PDF file and extract text
     */
    protected function parsePdf(UploadedFile $file): array
    {
        try {
            $pdf = $this->pdfParser->parseFile($file->getRealPath());
            $text = $pdf->getText();

            return [
                'success' => true,
                'text' => $text,
                'type' => 'pdf',
                'raw_text' => $text
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to parse PDF: ' . $e->getMessage()];
        }
    }

    /**
     * Parse image file - for now returns file path (would need OCR for full extraction)
     * This is a placeholder for future OCR implementation
     */
    protected function parseImage(UploadedFile $file): array
    {
        try {
            // Placeholder for OCR - would use Tesseract or similar
            // For now, we'll just extract metadata and prompt user to review
            return [
                'success' => true,
                'type' => 'image',
                'message' => 'Image uploaded. Please review and fill form fields manually or check extracted text if OCR was applied.',
                'file_path' => $file->getRealPath()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to process image: ' . $e->getMessage()];
        }
    }
}
