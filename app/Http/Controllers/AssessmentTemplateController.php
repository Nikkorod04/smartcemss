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
            'Flu; Cough; Fever', 'Buy medicine from pharmacy', 'Bandages; Paracetamol', 'Yes', 'Yes', 'Health center visit; Medicine access', 'Artesian well; Spring water', '50 meters', 'Burning in pit', 'Yes', 'Concrete septic', 'Yes', 'Goats; Chickens; Pigs',
            'Concrete with wood frame', 'Owner', 'Yes', 'Incandescent bulbs', 'Television; Fan; Refrigerator',
            'Basketball court; Barangay hall', 'Playing sports; Reading', 'Yes', 'Civic organization; Religious group', 'Monthly', 'Community development projects', '4', 'Secretary',
            'Poverty; Lack of jobs', 'Limited health access', 'High school fees', 'Limited job opportunities', 'Poor road conditions', 'Insufficient income', 'Petty theft',
            'Good; Excellent', 'Better healthcare access; More livelihood programs', 'Yes', '',
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
}
