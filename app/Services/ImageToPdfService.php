<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageToPdfService
{
    const MAX_IMAGES = 4;
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];
    
    /**
     * Convert multiple images to a single PDF
     * 
     * @param array $uploadedFiles Array of UploadedFile objects
     * @return array ['success' => bool, 'path' => string, 'error' => string]
     */
    public function convertImagesToPdf(array $uploadedFiles): array
    {
        // Validate count
        if (count($uploadedFiles) > self::MAX_IMAGES) {
            return [
                'success' => false,
                'error' => 'Maximum ' . self::MAX_IMAGES . ' images allowed'
            ];
        }

        if (empty($uploadedFiles)) {
            return [
                'success' => false,
                'error' => 'No images provided'
            ];
        }

        // Validate file types
        foreach ($uploadedFiles as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
                return [
                    'success' => false,
                    'error' => 'Only JPG and PNG files are supported'
                ];
            }
        }

        try {
            // Create PDF with A4 size (210x297mm)
            $pdf = new \FPDF('P', 'mm', 'A4');
            $pageWidth = 210;
            $pageHeight = 297;
            $margin = 5;

            // Add each image as a page
            foreach ($uploadedFiles as $index => $file) {
                $imagePath = $file->getRealPath();
                
                // Get image dimensions
                $imageInfo = getimagesize($imagePath);
                if (!$imageInfo) {
                    return [
                        'success' => false,
                        'error' => 'Failed to read image ' . ($index + 1)
                    ];
                }

                $imageWidth = $imageInfo[0];
                $imageHeight = $imageInfo[1];
                $aspectRatio = $imageWidth / $imageHeight;

                // Add new page
                $pdf->AddPage();

                // Calculate dimensions to fit A4 while preserving aspect ratio
                $maxWidth = $pageWidth - (2 * $margin);
                $maxHeight = $pageHeight - (2 * $margin);

                $width = $maxWidth;
                $height = $width / $aspectRatio;

                if ($height > $maxHeight) {
                    $height = $maxHeight;
                    $width = $height * $aspectRatio;
                }

                // Center the image on the page
                $x = ($pageWidth - $width) / 2;
                $y = $margin;

                // Add image with high quality (quality parameter doesn't affect FPDF directly,
                // but preserving original image quality is key)
                $pdf->Image($imagePath, $x, $y, $width, $height);
            }

            // Save PDF to storage
            $fileName = 'assessment_' . uniqid() . '.pdf';
            $savePath = storage_path('app/assessments/' . $fileName);

            // Create directory if it doesn't exist
            if (!is_dir(dirname($savePath))) {
                mkdir(dirname($savePath), 0755, true);
            }

            // Output to file
            $pdf->Output('F', $savePath);

            return [
                'success' => true,
                'path' => $savePath,
                'fileName' => $fileName
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to create PDF: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Convert single image to PDF
     */
    public function imageFileToPdf(UploadedFile $file): array
    {
        return $this->convertImagesToPdf([$file]);
    }
}
