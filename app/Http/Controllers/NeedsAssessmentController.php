<?php

namespace App\Http\Controllers;

use App\Models\NeedsAssessment;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NeedsAssessmentController extends Controller
{
    /**
     * Display a listing of all needs assessments.
     */
    public function index()
    {
        $assessments = NeedsAssessment::with('community')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create()
    {
        $communities = Community::all();
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 1);
        
        return view('assessments.create', compact('communities', 'quarters', 'years'));
    }

    /**
     * Store a newly created assessment in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'input_method' => 'required|in:manual,file',
            'community_id' => 'required|exists:communities,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'year' => 'required|integer|min:2000|max:' . (now()->year + 1),
            
            // File upload
            'assessment_file' => 'nullable|required_if:input_method,file|file|mimes:pdf,xlsx,csv,jpg,jpeg,png',
            
            // SECTION I - Manual Input
            'respondent_name' => 'nullable|string|max:150',
            'respondent_age' => 'nullable|integer|min:0|max:150',
            'respondent_civil_status' => 'nullable|string|max:50',
            'respondent_sex' => 'nullable|in:Male,Female,Other',
            'respondent_religion' => 'nullable|string|max:50',
            'respondent_educational_attainment' => 'nullable|array',
            
            // SECTION II - Family Composition
            'family_composition' => 'nullable|array',
            
            // SECTION III - Economic
            'livelihood_options' => 'nullable|array',
            'interested_in_livelihood_training' => 'nullable|in:Yes,No',
            'desired_training' => 'nullable|array',
            
            // SECTION IV - Educational
            'barangay_educational_facilities' => 'nullable|array',
            'household_member_currently_studying' => 'nullable|in:Yes,No',
            'interested_in_continuing_studies' => 'nullable|in:Yes,No',
            'areas_of_educational_interest' => 'nullable|array',
            'preferred_training_time' => 'nullable|in:Morning 8-12,Afternoon 1:30-5',
            'preferred_training_days' => 'nullable|array',
            
            // SECTION V - Health
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
            
            // SECTION VI - Housing
            'house_type' => 'nullable|array',
            'tenure_status' => 'nullable|array',
            'has_electricity' => 'nullable|in:Yes,No',
            'light_source_without_power' => 'nullable|array',
            'appliances_owned' => 'nullable|array',
            
            // SECTION VII - Recreational
            'barangay_recreational_facilities' => 'nullable|array',
            'use_of_free_time' => 'nullable|array',
            'member_of_organization' => 'nullable|in:Yes,No',
            'organization_types' => 'nullable|array',
            'organization_meeting_frequency' => 'nullable|in:Weekly,Monthly,Twice a month,Yearly',
            'organization_usual_activities' => 'nullable|string',
            'household_members_in_organization' => 'nullable|array',
            'position_in_organization' => 'nullable|string|max:100',
            
            // SECTION VIII - Problems
            'family_problems' => 'nullable|array',
            'health_problems' => 'nullable|array',
            'educational_problems' => 'nullable|array',
            'employment_problems' => 'nullable|array',
            'infrastructure_problems' => 'nullable|array',
            'economic_problems' => 'nullable|array',
            'security_problems' => 'nullable|array',
            
            // SECTION IX - Summary
            'barangay_service_ratings' => 'nullable|array',
            'general_feedback' => 'nullable|string',
            'available_for_training' => 'nullable|in:Yes,No',
            'reason_not_available' => 'nullable|string',
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->input_method === 'file' && $request->hasFile('assessment_file')) {
            $file = $request->file('assessment_file');
            $filePath = $file->store('assessments', 'public');
        }

        $validated['file_path'] = $filePath;
        $validated['uploaded_by'] = Auth::id();

        $assessment = NeedsAssessment::create($validated);

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Needs assessment created successfully!');
    }

    /**
     * Display the specified assessment.
     */
    public function show(NeedsAssessment $assessment)
    {
        $assessment->load('community');
        
        return view('assessments.show', compact('assessment'));
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(NeedsAssessment $assessment)
    {
        $assessment->load('community');
        $communities = Community::all();
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 1);
        
        return view('assessments.edit', compact('assessment', 'communities', 'quarters', 'years'));
    }

    /**
     * Update the specified assessment in database.
     */
    public function update(Request $request, NeedsAssessment $assessment)
    {
        $validated = $request->validate([
            'community_id' => 'required|exists:communities,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'year' => 'required|integer|min:2000|max:' . (now()->year + 1),
            
            // SECTION I
            'respondent_name' => 'nullable|string|max:150',
            'respondent_age' => 'nullable|integer|min:0|max:150',
            'respondent_civil_status' => 'nullable|string|max:50',
            'respondent_sex' => 'nullable|in:Male,Female,Other',
            'respondent_religion' => 'nullable|string|max:50',
            'respondent_educational_attainment' => 'nullable|array',
            
            // SECTION II
            'family_composition' => 'nullable|array',
            
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

        $assessment->update($validated);

        return redirect()->route('assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully!');
    }

    /**
     * Remove the specified assessment from database.
     */
    public function destroy(NeedsAssessment $assessment)
    {
        if ($assessment->file_path) {
            Storage::disk('public')->delete($assessment->file_path);
        }
        
        $assessment->delete();
        return redirect()->route('assessments.index')
            ->with('success', 'Assessment deleted successfully!');
    }

    /**
     * Search assessments by community or year.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $assessments = NeedsAssessment::with('community')
            ->where('year', 'LIKE', "%{$query}%")
            ->orWhereHas('community', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('assessments.index', compact('assessments'));
    }

    /**
     * Filter assessments by quarter.
     */
    public function filterByQuarter($quarter)
    {
        $valid_quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        
        if (!in_array($quarter, $valid_quarters)) {
            return redirect()->route('assessments.index');
        }

        $assessments = NeedsAssessment::with('community')
            ->where('quarter', $quarter)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('assessments.index', compact('assessments'));
    }
}
