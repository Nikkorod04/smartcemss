<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class AssessmentTemplateController extends Controller
{
    /**
     * Download CSV template
     */
    public function downloadCsvTemplate()
    {
        $filename = 'Assessment_Form_Template_' . now()->format('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: public');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for proper character encoding
        fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // ===== IMPORTANT: Headers MUST be on first line for CSV parsing =====

        // Header row with field names (MUST BE FIRST LINE) - ALL FIELDS
        $headers = [
            // SECTION I - Respondent Information
            'Respondent First Name *',
            'Respondent Middle Name',
            'Respondent Last Name *',
            'Respondent Age *',
            'Respondent Sex *',
            'Civil Status *',
            'Religion',
            'Educational Attainment',

            // SECTION II - Family Composition
            'Number of Family Adults *',
            'Number of Children *',

            // SECTION III - Economic
            'Livelihood Options',
            'Interested in Livelihood Training? *',
            'Desired Training Areas',

            // SECTION IV - Educational
            'Barangay Educational Facilities',
            'Household Member Currently Studying? *',
            'Interested in Continuing Studies? *',
            'Areas of Educational Interest',
            'Preferred Training Time',
            'Preferred Training Days',

            // SECTION V - Health, Sanitation, Environmental
            'Common Illnesses',
            'Action When Sick',
            'Barangay Medical Supplies Available',
            'Has Barangay Health Programs? *',
            'Benefits from Barangay Programs?',
            'Programs Benefited From',
            'Water Source',
            'Water Source Distance',
            'Garbage Disposal Method',
            'Has Own Toilet? *',
            'Toilet Type',
            'Keeps Animals?',
            'Animals Kept',

            // SECTION VI - Housing and Basic Amenities
            'House Type',
            'Tenure Status',
            'Has Electricity? *',
            'Light Source Without Power',
            'Appliances Owned',

            // SECTION VII - Recreational Facilities
            'Barangay Recreational Facilities',
            'Use of Free Time',
            'Member of Organization? *',
            'Organization Types',
            'Organization Meeting Frequency',
            'Organization Usual Activities',
            'Household Members in Organization',
            'Position in Organization',

            // SECTION VIII - Problems/Concerns
            'Family Problems',
            'Health Problems',
            'Educational Problems',
            'Employment Problems',
            'Infrastructure Problems',
            'Economic Problems',
            'Security Problems',

            // SECTION IX - Summary
            'Barangay Service Ratings',
            'General Feedback',
            'Available for Training? *',
            'Reason Not Available',
        ];

        fputcsv($output, $headers, ',', '"', "\\");

        // Sample data row (row 2)
        $sampleData = [
            'Juan', 'Dela', 'Cruz', '45', 'Male', 'Married', 'Roman Catholic', 'High School',
            '3', '2',
            'Farming; Trading', 'Yes', 'Livestock raising; Organic farming',
            'Public Elementary; Public High School', 'Yes', 'Yes', 'Technology; Agriculture', 'Morning 8-12', 'Monday; Wednesday; Friday',
            'Flu; Cough; Fever', 'Buy medicine from pharmacy', 'Bandages; Paracetamol', 'Yes', 'Yes', 'Free Vaccine', 'NAWASA', 'Just Outside', 'Compost pit', 'Yes', 'Water sealed', 'Yes', 'Duck',
            'Wood', 'Own house/land', 'Yes', 'Incandescent bulbs', 'Television; Fan; Refrigerator',
            'Basketball Court', 'Radio drama', 'Yes', 'Religious', 'Monthly', 'Bible Study', 'Self', 'Secretary',
            'Poor family relationship', 'Sickly children', 'Lack of equipment', 'Lack of employment', 'No electric posts', 'No capital', 'No police assigned',
            '3', 'Better healthcare access; More livelihood programs', 'Yes', '',
        ];

        fputcsv($output, $sampleData, ',', '"', "\\");

        // Validation instructions row (row 3)
        $validation = [
            'Max 100 chars', 'Max 100 chars', 'Max 100 chars', 'Age 0-150', 'M/F/Other', 'Single/Married/Widow/Div/Sep', 'Max 50 chars', 'Separate with ;',
            '1-100', '0-100',
            'Separate with ;', 'Yes/No', 'Separate with ;',
            'Separate with ;', 'Yes/No', 'Yes/No', 'Separate with ;', 'Time slot', 'Separate with ;',
            'Separate with ;', 'Separate with ;', 'Separate with ;', 'Yes/No', 'Yes/No', 'Separate with ;', 'Separate with ;', 'Distance value', 'Separate with ;', 'Yes/No', 'Separate with ;', 'Yes/No', 'Separate with ;',
            'Separate with ;', 'Owner/Renter/etc', 'Yes/No', 'Separate with ;', 'Separate with ;',
            'Separate with ;', 'Separate with ;', 'Yes/No', 'Separate with ;', 'Frequency', 'Separate with ;', 'Number', 'Job title/position',
            'Separate with ;', 'Separate with ;', 'Separate with ;', 'Separate with ;', 'Separate with ;', 'Separate with ;', 'Separate with ;',
            'Separate with ;', 'Separate with ;', 'Yes/No', 'Text (optional)',
        ];

        fputcsv($output, $validation, ',', '"', "\\");

        // Add 10 empty rows for data entry
        for ($i = 0; $i < 10; $i++) {
            fputcsv($output, array_fill(0, count($headers), ''), ',', '"', "\\");
        }

        fclose($output);
        exit;
    }

    /**
     * Download DOCX template
     */
    public function downloadDocxTemplate()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set document margins
        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginRight' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
        ]);

        // Title
        $section->addText(
            'COMMUNITY NEEDS ASSESSMENT FORM (F-CES-001)',
            ['bold' => true, 'size' => 14, 'color' => '003366'],
            ['alignment' => 'center']
        );

        $section->addText(
            'Leyte Normal University Community Extension Services Office',
            ['size' => 10, 'italic' => true],
            ['alignment' => 'center']
        );

        $section->addParagraph();

        // Sections with fields
        $this->addFormSection($section, 'SECTION I: IDENTIFYING INFORMATION', [
            'Respondent First Name *',
            'Respondent Middle Name',
            'Respondent Last Name *',
            'Respondent Age *',
            'Respondent Sex *',
            'Civil Status *',
            'Religion',
            'Educational Attainment',
        ]);

        $this->addFormSection($section, 'SECTION II: FAMILY COMPOSITION', [
            'Number of Family Adults *',
            'Number of Children *',
        ]);

        $this->addFormSection($section, 'SECTION III: ECONOMIC ASPECT', [
            'Livelihood Options',
            'Interested in Livelihood Training? *',
            'Desired Training Areas',
        ]);

        $this->addFormSection($section, 'SECTION IV: EDUCATIONAL ASPECT', [
            'Barangay Educational Facilities',
            'Household Member Currently Studying? *',
            'Interested in Continuing Studies? *',
            'Areas of Educational Interest',
            'Preferred Training Time',
            'Preferred Training Days',
        ]);

        $this->addFormSection($section, 'SECTION V: HEALTH, SANITATION & ENVIRONMENTAL', [
            'Common Illnesses',
            'Action When Sick',
            'Barangay Medical Supplies Available',
            'Has Barangay Health Programs? *',
            'Benefits from Barangay Programs?',
            'Programs Benefited From',
            'Water Source',
            'Water Source Distance',
            'Garbage Disposal Method',
            'Has Own Toilet? *',
            'Toilet Type',
            'Keeps Animals?',
            'Animals Kept',
        ]);

        $this->addFormSection($section, 'SECTION VI: HOUSING & BASIC AMENITIES', [
            'House Type',
            'Tenure Status',
            'Has Electricity? *',
            'Light Source Without Power',
            'Appliances Owned',
        ]);

        $this->addFormSection($section, 'SECTION VII: RECREATIONAL FACILITIES & ORGANIZATIONS', [
            'Barangay Recreational Facilities',
            'Use of Free Time',
            'Member of Organization? *',
            'Organization Types',
            'Organization Meeting Frequency',
            'Organization Usual Activities',
            'Household Members in Organization',
            'Position in Organization',
        ]);

        $this->addFormSection($section, 'SECTION VIII: PROBLEMS/CONCERNS', [
            'Family Problems',
            'Health Problems',
            'Educational Problems',
            'Employment Problems',
            'Infrastructure Problems',
            'Economic Problems',
            'Security Problems',
        ]);

        $this->addFormSection($section, 'SECTION IX: SUMMARY', [
            'Barangay Service Ratings',
            'General Feedback',
            'Available for Training? *',
            'Reason Not Available',
        ]);

        // Sample data section
        $section->addPageBreak();
        $section->addText(
            'SAMPLE DATA',
            ['bold' => true, 'size' => 12, 'color' => '003366']
        );

        $section->addText(
            'Field Name: Value Examples',
            ['size' => 9, 'italic' => true]
        );

        $sampleText = "Respondent First Name: Juan
Respondent Middle Name: Dela
Respondent Last Name: Cruz
Age: 45
Sex: Male
Civil Status: Married
Religion: Roman Catholic
Educational Attainment: High School
Family Adults: 3
Children: 2
Livelihood Options: Farming; Trading
Livelihood Training: Yes
Training Areas: Livestock raising; Organic farming
Educational Facilities: Public Elementary; Public High School
Currently Studying: Yes
Continue Studies: Yes
Education Interest: Technology; Agriculture
Training Time: Morning 8-12
Training Days: Monday; Wednesday; Friday
Common Illnesses: Flu; Cough; Fever
Action When Sick: Buy medicine from pharmacy
Medical Supplies: Bandages; Paracetamol
Health Programs: Yes
Benefits: Yes
Programs Benefited: Free Vaccine
Water Source: NAWASA
Water Distance: Just Outside
Garbage Method: Compost pit
Own Toilet: Yes
Toilet Type: Water sealed
Keeps Animals: Yes
Animals: Duck
House Type: Wood
Tenure Status: Own house/land
Electricity: Yes
Light Source: Incandescent bulbs
Appliances: Television; Fan; Refrigerator
Recreational Facilities: Basketball Court
Free Time Use: Radio drama
Organization Member: Yes
Organization Types: Religious
Meeting Frequency: Monthly
Activities: Bible Study
Members in Org: Self
Position: Secretary
Family Problems: Poor family relationship
Health Problems: Sickly children
Education Problems: Lack of equipment
Employment Problems: Lack of employment
Infrastructure Problems: No electric posts
Economic Problems: No capital
Security Problems: No police assigned
Service Ratings: 3
General Feedback: Better healthcare access; More livelihood programs
Available for Training: Yes
Reason Not Available: ";

        $section->addText($sampleText, ['size' => 8, 'color' => '333333']);

        // Validation guide section
        $section->addPageBreak();
        $section->addText(
            'FIELD VALIDATION GUIDE',
            ['bold' => true, 'size' => 12, 'color' => '003366']
        );

        $validationText = "• Max 100 chars: Respondent First/Middle/Last Name
• Age 0-150: Numeric value
• M/F/Other: Respondent Sex
• Single/Married/Widow/Divorced/Separated: Civil Status
• Max 50 chars: Religion
• Separate with semicolon (;): Multiple values (e.g., Farming; Trading)
• Yes/No: Binary responses
• Distance value: Include unit (e.g., '50 meters', 'Just Outside')
• Time slot: Training time (e.g., 'Morning 8-12')
• Separate with ;: Preferred Training Days (e.g., 'Monday; Wednesday; Friday')
• Frequency: Organization meeting frequency (e.g., 'Monthly', 'Weekly')
• Number: Count values
• Job title/position: Position in organization";

        $section->addText($validationText, ['size' => 9, 'color' => '444444']);

        // Save the document
        $filename = 'Assessment_Form_Template_' . now()->format('Y-m-d') . '.docx';
        $filepath = storage_path('app/temp/' . $filename);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $phpWord->save($filepath);

        // Download the file
        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Helper method to add form section to the document
     */
    private function addFormSection($section, $sectionTitle, $fields)
    {
        $section->addText($sectionTitle, ['bold' => true, 'size' => 11, 'color' => '003366']);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => 'e0e0e0']);
        $table->setWidth(9000); // 9000 twips = ~6.25 inches

        foreach ($fields as $field) {
            $row = $table->addRow();
            $row->addCell(2500)->addText($field, ['bold' => false, 'size' => 9]);
            $row->addCell(6500)->addText('_________________________', ['size' => 9, 'color' => 'cccccc']);
        }

        $section->addParagraph();
    }
}
