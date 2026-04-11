<?php

namespace App\Livewire;

use App\Models\NeedsAssessment;
use App\Models\Community;
use App\Services\AssessmentDocumentParser;
use App\Services\AssessmentFieldMapper;
use App\Services\ImageToPdfService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssessmentForm extends Component
{
    use WithFileUploads;

    public $assessment = null;
    public $isEditing = false;
    
    // Form fields
    public $input_method = 'manual';
    public $community_id;
    public $quarter;
    public $year;
    public $assessment_files = [];  // Support multiple image uploads

    // File import properties
    public $importedData = [];
    public $importStatus = null;
    public $importMessage = '';
    public $showImportedDataReview = false;
    public $ocrMethod = null;
    public $ocrConfidence = null;

    // SECTION I - Respondent Info
    public $respondent_first_name;
    public $respondent_middle_name;
    public $respondent_last_name;
    public $respondent_age;
    public $respondent_civil_status;
    public $respondent_sex;
    public $respondent_religion;
    public $respondent_educational_attainment = [];

    // SECTION II - Family Composition
    public $family_adults;
    public $family_children;

    // SECTION III - Economic
    public $livelihood_options = [];
    public $interested_in_livelihood_training;
    public $desired_training = [];

    // SECTION IV - Educational
    public $barangay_educational_facilities = [];
    public $household_member_currently_studying;
    public $interested_in_continuing_studies;
    public $areas_of_educational_interest = [];
    public $preferred_training_time;
    public $preferred_training_days = [];

    // SECTION V - Health, Sanitation, Environmental
    public $common_illnesses = [];
    public $action_when_sick = [];
    public $barangay_medical_supplies_available = [];
    public $has_barangay_health_programs;
    public $benefits_from_barangay_programs;
    public $programs_benefited_from = [];
    public $water_source = [];
    public $water_source_distance;
    public $garbage_disposal_method = [];
    public $has_own_toilet;
    public $toilet_type = [];
    public $keeps_animals;
    public $animals_kept = [];

    // SECTION VI - Housing and Basic Amenities
    public $house_type = [];
    public $tenure_status = [];
    public $has_electricity;
    public $light_source_without_power = [];
    public $appliances_owned = [];

    // SECTION VII - Recreational Facilities
    public $barangay_recreational_facilities = [];
    public $use_of_free_time = [];
    public $member_of_organization;
    public $organization_types = [];
    public $organization_meeting_frequency;
    public $organization_usual_activities;
    public $household_members_in_organization = [];
    public $position_in_organization;

    // SECTION VIII - Problems
    public $family_problems = [];
    public $health_problems = [];
    public $educational_problems = [];
    public $employment_problems = [];
    public $infrastructure_problems = [];
    public $economic_problems = [];
    public $security_problems = [];

    // SECTION IX - Summary
    public $barangay_service_ratings = [];
    public $general_feedback;
    public $available_for_training;
    public $reason_not_available;

    // Show/hide conditional fields
    public $show_no_electricity_fields = false;
    public $show_toilet_type = false;
    public $show_animals_kept = false;
    public $show_training_interested = false;
    public $show_education_fields = false;
    public $show_reason_not_available = false;

    public function mount($assessment = null)
    {
        if ($assessment) {
            $this->isEditing = true;
            $this->assessment = $assessment;
            $this->loadData();
        } else {
            // Initialize for new assessment
            $this->year = now()->year;
            $this->initializeNewAssessment();
        }
    }

    public function initializeNewAssessment()
    {
        // Ensure all checkbox/array fields are empty for new form
        $this->respondent_educational_attainment = [];
        $this->livelihood_options = [];
        $this->desired_training = [];
        $this->barangay_educational_facilities = [];
        $this->areas_of_educational_interest = [];
        $this->preferred_training_days = [];
        $this->common_illnesses = [];
        $this->action_when_sick = [];
        $this->barangay_medical_supplies_available = [];
        $this->programs_benefited_from = [];
        $this->water_source = [];
        $this->garbage_disposal_method = [];
        $this->toilet_type = [];
        $this->animals_kept = [];
        $this->house_type = [];
        $this->tenure_status = [];
        $this->light_source_without_power = [];
        $this->appliances_owned = [];
        $this->barangay_recreational_facilities = [];
        $this->use_of_free_time = [];
        $this->organization_types = [];
        $this->household_members_in_organization = [];
        $this->family_problems = [];
        $this->health_problems = [];
        $this->educational_problems = [];
        $this->employment_problems = [];
        $this->infrastructure_problems = [];
        $this->economic_problems = [];
        $this->security_problems = [];
        $this->barangay_service_ratings = [];
        
        // Update conditional fields
        $this->updateConditionalFields();
    }

    public function loadData()
    {
        $a = $this->assessment;
        $this->community_id = $a->community_id;
        $this->quarter = $a->quarter;
        $this->year = $a->year;
        
        // SECTION I
        $this->respondent_first_name = $a->respondent_first_name;
        $this->respondent_middle_name = $a->respondent_middle_name;
        $this->respondent_last_name = $a->respondent_last_name;
        $this->respondent_age = $a->respondent_age;
        $this->respondent_civil_status = $a->respondent_civil_status;
        $this->respondent_sex = $a->respondent_sex;
        $this->respondent_religion = $a->respondent_religion;
        $this->respondent_educational_attainment = $a->respondent_educational_attainment ?? [];

        // SECTION II
        $this->family_adults = $a->family_adults;
        $this->family_children = $a->family_children;

        // SECTION III
        $this->livelihood_options = $a->livelihood_options ?? [];
        $this->interested_in_livelihood_training = $a->interested_in_livelihood_training;
        $this->desired_training = $a->desired_training ?? [];

        // SECTION IV
        $this->barangay_educational_facilities = $a->barangay_educational_facilities ?? [];
        $this->household_member_currently_studying = $a->household_member_currently_studying;
        $this->interested_in_continuing_studies = $a->interested_in_continuing_studies;
        $this->areas_of_educational_interest = $a->areas_of_educational_interest ?? [];
        $this->preferred_training_time = $a->preferred_training_time;
        $this->preferred_training_days = $a->preferred_training_days ?? [];

        // SECTION V
        $this->common_illnesses = $a->common_illnesses ?? [];
        $this->action_when_sick = $a->action_when_sick ?? [];
        $this->barangay_medical_supplies_available = $a->barangay_medical_supplies_available ?? [];
        $this->has_barangay_health_programs = $a->has_barangay_health_programs;
        $this->benefits_from_barangay_programs = $a->benefits_from_barangay_programs;
        $this->programs_benefited_from = $a->programs_benefited_from ?? [];
        $this->water_source = $a->water_source ?? [];
        $this->water_source_distance = $a->water_source_distance;
        $this->garbage_disposal_method = $a->garbage_disposal_method ?? [];
        $this->has_own_toilet = $a->has_own_toilet;
        $this->toilet_type = $a->toilet_type ?? [];
        $this->keeps_animals = $a->keeps_animals;
        $this->animals_kept = $a->animals_kept ?? [];

        // SECTION VI
        $this->house_type = $a->house_type ?? [];
        $this->tenure_status = $a->tenure_status ?? [];
        $this->has_electricity = $a->has_electricity;
        $this->light_source_without_power = $a->light_source_without_power ?? [];
        $this->appliances_owned = $a->appliances_owned ?? [];

        // SECTION VII
        $this->barangay_recreational_facilities = $a->barangay_recreational_facilities ?? [];
        $this->use_of_free_time = $a->use_of_free_time ?? [];
        $this->member_of_organization = $a->member_of_organization;
        $this->organization_types = $a->organization_types ?? [];
        $this->organization_meeting_frequency = $a->organization_meeting_frequency;
        $this->organization_usual_activities = $a->organization_usual_activities;
        $this->household_members_in_organization = $a->household_members_in_organization ?? [];
        $this->position_in_organization = $a->position_in_organization;

        // SECTION VIII
        $this->family_problems = $a->family_problems ?? [];
        $this->health_problems = $a->health_problems ?? [];
        $this->educational_problems = $a->educational_problems ?? [];
        $this->employment_problems = $a->employment_problems ?? [];
        $this->infrastructure_problems = $a->infrastructure_problems ?? [];
        $this->economic_problems = $a->economic_problems ?? [];
        $this->security_problems = $a->security_problems ?? [];

        // SECTION IX
        $this->barangay_service_ratings = $a->barangay_service_ratings ?? [];
        $this->general_feedback = $a->general_feedback;
        $this->available_for_training = $a->available_for_training;
        $this->reason_not_available = $a->reason_not_available;

        $this->updateConditionalFields();
    }

    public function updatedHasElectricity()
    {
        $this->updateConditionalFields();
    }

    public function updatedHasOwnToilet()
    {
        $this->updateConditionalFields();
    }

    public function updatedInterestedInLivelihoodTraining()
    {
        $this->updateConditionalFields();
    }

    public function updatedHouseholdMemberCurrentlyStudying()
    {
        $this->updateConditionalFields();
    }

    public function updatedAvailableForTraining()
    {
        $this->updateConditionalFields();
    }

    public function updatedKeepsAnimals()
    {
        $this->updateConditionalFields();
    }

    public function updateConditionalFields()
    {
        // Show fields if electricity is "No"
        $this->show_no_electricity_fields = $this->has_electricity === 'No';

        // Show toilet type if has own toilet = "Yes"
        $this->show_toilet_type = $this->has_own_toilet === 'Yes';

        // Show animals kept if keeps animals = "Yes"
        $this->show_animals_kept = $this->keeps_animals === 'Yes';

        // Show training options if interested in livelihood training
        $this->show_training_interested = $this->interested_in_livelihood_training === 'Yes';

        // Show education fields if household member is studying
        $this->show_education_fields = $this->household_member_currently_studying === 'Yes';

        // Show reason_not_available if not available for training
        $this->show_reason_not_available = $this->available_for_training === 'No';
    }

    /**
     * Handle multiple file uploads and process document
     */
    public function updatedAssessmentFiles()
    {
        if (empty($this->assessment_files)) {
            return;
        }

        try {
            // Validate files
            $this->validate([
                'assessment_files' => 'required|array|min:1|max:4',
                'assessment_files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,xlsx,csv|max:10240',
            ]);

            // Get the files to process
            $filesToProcess = $this->assessment_files;
            
            // Separate files by type
            $imageFiles = array_filter($filesToProcess, function($file) {
                $ext = strtolower($file->getClientOriginalExtension());
                return in_array($ext, ['jpg', 'jpeg', 'png']);
            });
            
            $otherFiles = array_filter($filesToProcess, function($file) {
                $ext = strtolower($file->getClientOriginalExtension());
                return !in_array($ext, ['jpg', 'jpeg', 'png']);
            });
            
            $result = null;
            $parser = new AssessmentDocumentParser();
            
            // Handle multiple images: Process individually and merge text
            if (count($imageFiles) > 1) {
                \Log::info('Processing ' . count($imageFiles) . ' images individually for OCR');
                
                $mergedText = '';
                $totalConfidence = 0;
                $confidenceCount = 0;
                $ocrCount = 0;
                
                // Create a service to process images individually
                $ocrService = null;
                try {
                    $credentialsFile = env('GOOGLE_CREDENTIALS_FILE', 'google-credentials.json');
                    $credentialsPath = storage_path('app/' . $credentialsFile);
                    if (file_exists($credentialsPath)) {
                        $ocrService = new \App\Services\OcrService();
                    }
                } catch (\Exception $e) {
                    \Log::warning('OCR service not available for image processing');
                }
                
                foreach ($imageFiles as $index => $imageFile) {
                    $imageIndex = $index + 1;
                    \Log::info('Processing image ' . $imageIndex . '/' . count($imageFiles));
                    
                    if ($ocrService) {
                        // Use OCR for this image
                        $ocrResult = $ocrService->extractFromImage($imageFile);
                        if ($ocrResult['success']) {
                            $mergedText .= "--- Image $imageIndex ---\n";
                            $mergedText .= $ocrResult['text'] . "\n\n";
                            $totalConfidence += $ocrResult['confidence'] ?? 0;
                            $confidenceCount++;
                            $ocrCount++;
                            \Log::info('Image ' . $imageIndex . ' OCR succeeded');
                        } else {
                            \Log::warning('Image ' . $imageIndex . ' OCR failed: ' . ($ocrResult['error'] ?? 'unknown'));
                        }
                    }
                }
                
                if ($ocrCount > 0) {
                    // Successfully processed images via OCR
                    $result = [
                        'success' => true,
                        'text' => $mergedText,
                        'type' => 'images',
                        'raw_text' => $mergedText,
                        'ocr_method' => 'google_vision',
                        'confidence' => $confidenceCount > 0 ? round($totalConfidence / $confidenceCount, 2) : 0
                    ];
                    \Log::info('Multi-image OCR complete', [
                        'images_processed' => $ocrCount,
                        'confidence' => $result['confidence']
                    ]);
                } else {
                    // OCR failed, fall back to PDF conversion
                    \Log::warning('OCR processing failed for all images, falling back to PDF conversion');
                    $imageToPdf = new ImageToPdfService();
                    $pdfResult = $imageToPdf->convertImagesToPdf($imageFiles);
                    
                    if (!$pdfResult['success']) {
                        $this->importStatus = 'error';
                        $this->importMessage = $pdfResult['error'];
                        return;
                    }
                    
                    $result = $parser->parseFromPath($pdfResult['path']);
                }
            } else if (count($imageFiles) === 1) {
                // Single image, process directly
                $result = $parser->parse($imageFiles[0]);
            } else if (!empty($otherFiles)) {
                // Process first non-image file (PDF, CSV, Excel)
                $result = $parser->parse($otherFiles[0]);
            } else {
                $this->importStatus = 'error';
                $this->importMessage = 'No valid files to process';
                return;
            }

            if (!$result['success']) {
                $this->importStatus = 'error';
                $this->importMessage = $result['error'] ?? 'Failed to process file';
                return;
            }

            // Store OCR metadata if available
            $this->ocrMethod = $result['ocr_method'] ?? null;
            $this->ocrConfidence = $result['confidence'] ?? null;

            // Map extracted data to form fields
            $mapper = new AssessmentFieldMapper();
            $mappedData = $mapper->mapDataToFields($result, $result['type']);

            // Store imported data for review
            $this->importedData = $mappedData;
            $this->importStatus = 'success';
            
            // Build detailed success message with OCR info
            $imageCount = count($imageFiles);
            $message = 'File processed successfully!';
            if ($imageCount > 1) {
                $message .= " ({$imageCount} images processed)";
            }
            $message .= ' Review and confirm the extracted data below.';
            
            if ($this->ocrMethod) {
                $message .= ' ';
                if (strpos($this->ocrMethod, 'google_vision') !== false) {
                    $confidenceText = $this->ocrConfidence ? "({$this->ocrConfidence}% confidence)" : '';
                    $message .= "Extracted via Google Cloud Vision OCR {$confidenceText}";
                } else {
                    $message .= "Extracted via {$this->ocrMethod}";
                }
            }
            
            $this->importMessage = $message;
            $this->showImportedDataReview = true;
        } catch (\Exception $e) {
            $this->importStatus = 'error';
            $this->importMessage = 'Error processing files: ' . $e->getMessage();
        }
    }

    /**
     * Populate form fields with imported data
     */
    public function populateFromImportedData()
    {
        if (empty($this->importedData)) {
            return;
        }

        foreach ($this->importedData as $fieldName => $value) {
            // Use reflection to dynamically set property
            if (property_exists($this, $fieldName)) {
                $this->$fieldName = $value;
            }
        }

        $this->showImportedDataReview = false;
        $this->importStatus = null;
        session()->flash('info', 'Form fields have been populated with imported data. Please review and make any corrections before submitting.');
    }

    /**
     * Clear imported data
     */
    public function clearImportedData()
    {
        $this->importedData = [];
        $this->importStatus = null;
        $this->importMessage = '';
        $this->showImportedDataReview = false;
        $this->assessment_files = [];
        $this->ocrMethod = null;
        $this->ocrConfidence = null;
    }

    public function submit()
    {
        $validated = $this->validate([
            'input_method' => 'required|in:manual,file',
            'community_id' => 'required|exists:communities,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'year' => 'required|integer|min:2000|max:' . (now()->year + 1),
            'assessment_files' => 'nullable|required_if:input_method,file|array|max:4',
            'assessment_files.*' => 'required|file|mimes:pdf,xlsx,csv,jpg,jpeg,png|max:10240',
            
            // SECTION I
            'respondent_first_name' => 'nullable|string|max:100',
            'respondent_middle_name' => 'nullable|string|max:100',
            'respondent_last_name' => 'nullable|string|max:100',
            'respondent_age' => 'nullable|integer|min:0|max:150',
            'respondent_civil_status' => 'nullable|string|max:50',
            'respondent_sex' => 'nullable|in:Male,Female,Other',
            'respondent_religion' => 'nullable|string|max:50',
            'respondent_educational_attainment' => 'nullable|array',
            
            // SECTION II
            'family_adults' => 'nullable|integer|min:1|max:100',
            'family_children' => 'nullable|integer|min:0|max:100',
            
            // SECTION III
            'livelihood_options' => 'nullable|array',
            'interested_in_livelihood_training' => 'nullable|in:Yes,No',
            'desired_training' => 'nullable|array',
            
            // SECTION IV
            'barangay_educational_facilities' => 'nullable|array',
            'household_member_currently_studying' => 'nullable|in:Yes,No',
            'interested_in_continuing_studies' => 'nullable|in:Yes,No',
            'areas_of_educational_interest' => 'nullable|array',
            'preferred_training_time' => 'nullable|in:Morning 8-12,Afternoon 1:30-5',
            'preferred_training_days' => 'nullable|array',
            
            // SECTION V
            'common_illnesses' => 'nullable|array',
            'action_when_sick' => 'nullable|array',
            'barangay_medical_supplies_available' => 'nullable|array',
            'has_barangay_health_programs' => 'nullable|in:Yes,No',
            'benefits_from_barangay_programs' => 'nullable|in:Yes,No',
            'programs_benefited_from' => 'nullable|array',
            'water_source' => 'nullable|array',
            'water_source_distance' => 'nullable|in:Just outside,250 meters away,No idea',
            'garbage_disposal_method' => 'nullable|array',
            'has_own_toilet' => 'nullable|in:Yes,No',
            'toilet_type' => 'nullable|array',
            'keeps_animals' => 'nullable|in:Yes,No',
            'animals_kept' => 'nullable|array',
            
            // SECTION VI
            'house_type' => 'nullable|array',
            'tenure_status' => 'nullable|array',
            'has_electricity' => 'nullable|in:Yes,No',
            'light_source_without_power' => 'nullable|array',
            'appliances_owned' => 'nullable|array',
            
            // SECTION VII
            'barangay_recreational_facilities' => 'nullable|array',
            'use_of_free_time' => 'nullable|array',
            'member_of_organization' => 'nullable|in:Yes,No',
            'organization_types' => 'nullable|array',
            'organization_meeting_frequency' => 'nullable|in:Weekly,Monthly,Twice a month,Yearly',
            'organization_usual_activities' => 'nullable|string',
            'household_members_in_organization' => 'nullable|array',
            'position_in_organization' => 'nullable|string|max:100',
            
            // SECTION VIII
            'family_problems' => 'nullable|array',
            'health_problems' => 'nullable|array',
            'educational_problems' => 'nullable|array',
            'employment_problems' => 'nullable|array',
            'infrastructure_problems' => 'nullable|array',
            'economic_problems' => 'nullable|array',
            'security_problems' => 'nullable|array',
            
            // SECTION IX
            'barangay_service_ratings' => 'nullable|array',
            'general_feedback' => 'nullable|string',
            'available_for_training' => 'nullable|in:Yes,No',
            'reason_not_available' => 'nullable|string',
        ]);

        $filePath = null;
        if ($this->input_method === 'file' && !empty($this->assessment_files)) {
            // Store the first file (after potential PDF conversion in updatedAssessmentFiles)
            $filePath = $this->assessment_files[0]->store('assessments', 'public');
        }

        $data = [
            'community_id' => $this->community_id,
            'quarter' => $this->quarter,
            'year' => $this->year,
            
            // SECTION I
            'respondent_first_name' => $this->respondent_first_name,
            'respondent_middle_name' => $this->respondent_middle_name,
            'respondent_last_name' => $this->respondent_last_name,
            'respondent_age' => $this->respondent_age,
            'respondent_civil_status' => $this->respondent_civil_status,
            'respondent_sex' => $this->respondent_sex,
            'respondent_religion' => $this->respondent_religion,
            'respondent_educational_attainment' => $this->respondent_educational_attainment ?: null,
            
            // SECTION II
            'family_adults' => $this->family_adults,
            'family_children' => $this->family_children,
            
            // SECTION III
            'livelihood_options' => $this->livelihood_options ?: null,
            'interested_in_livelihood_training' => $this->interested_in_livelihood_training,
            'desired_training' => $this->desired_training ?: null,
            
            // SECTION IV
            'barangay_educational_facilities' => $this->barangay_educational_facilities ?: null,
            'household_member_currently_studying' => $this->household_member_currently_studying,
            'interested_in_continuing_studies' => $this->interested_in_continuing_studies,
            'areas_of_educational_interest' => $this->areas_of_educational_interest ?: null,
            'preferred_training_time' => $this->preferred_training_time,
            'preferred_training_days' => $this->preferred_training_days ?: null,
            
            // SECTION V
            'common_illnesses' => $this->common_illnesses ?: null,
            'action_when_sick' => $this->action_when_sick ?: null,
            'barangay_medical_supplies_available' => $this->barangay_medical_supplies_available ?: null,
            'has_barangay_health_programs' => $this->has_barangay_health_programs,
            'benefits_from_barangay_programs' => $this->benefits_from_barangay_programs,
            'programs_benefited_from' => $this->programs_benefited_from ?: null,
            'water_source' => $this->water_source ?: null,
            'water_source_distance' => $this->water_source_distance,
            'garbage_disposal_method' => $this->garbage_disposal_method ?: null,
            'has_own_toilet' => $this->has_own_toilet,
            'toilet_type' => $this->toilet_type ?: null,
            'keeps_animals' => $this->keeps_animals,
            'animals_kept' => $this->animals_kept ?: null,
            
            // SECTION VI
            'house_type' => $this->house_type ?: null,
            'tenure_status' => $this->tenure_status ?: null,
            'has_electricity' => $this->has_electricity,
            'light_source_without_power' => $this->light_source_without_power ?: null,
            'appliances_owned' => $this->appliances_owned ?: null,
            
            // SECTION VII
            'barangay_recreational_facilities' => $this->barangay_recreational_facilities ?: null,
            'use_of_free_time' => $this->use_of_free_time ?: null,
            'member_of_organization' => $this->member_of_organization,
            'organization_types' => $this->organization_types ?: null,
            'organization_meeting_frequency' => $this->organization_meeting_frequency,
            'organization_usual_activities' => $this->organization_usual_activities,
            'household_members_in_organization' => $this->household_members_in_organization ?: null,
            'position_in_organization' => $this->position_in_organization,
            
            // SECTION VIII
            'family_problems' => $this->family_problems ?: null,
            'health_problems' => $this->health_problems ?: null,
            'educational_problems' => $this->educational_problems ?: null,
            'employment_problems' => $this->employment_problems ?: null,
            'infrastructure_problems' => $this->infrastructure_problems ?: null,
            'economic_problems' => $this->economic_problems ?: null,
            'security_problems' => $this->security_problems ?: null,
            
            // SECTION IX
            'barangay_service_ratings' => $this->barangay_service_ratings ?: null,
            'general_feedback' => $this->general_feedback,
            'available_for_training' => $this->available_for_training,
            'reason_not_available' => $this->reason_not_available,
        ];

        if ($filePath) {
            $data['file_path'] = $filePath;
        }

        if ($this->isEditing) {
            // Delete old file if new one uploaded
            if ($filePath && $this->assessment->file_path) {
                Storage::disk('public')->delete($this->assessment->file_path);
            }
            $this->assessment->update($data);
            $assessment = $this->assessment;
        } else {
            $data['uploaded_by'] = Auth::id();
            $assessment = NeedsAssessment::create($data);
        }

        session()->flash('success', $this->isEditing ? 'Assessment updated successfully!' : 'Assessment created successfully!');
        return redirect()->route('assessments.show', $assessment);
    }

    public function render()
    {
        $communities = Community::all();
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 1);

        return view('livewire.assessment-form', compact('communities', 'quarters', 'years'));
    }
}
