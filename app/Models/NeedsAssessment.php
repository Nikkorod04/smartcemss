<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NeedsAssessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'community_id',
        'quarter',
        'year',
        'file_path',
        'raw_ocr_text',
        'ai_cleaned_text',
        'uploaded_by',
        // SECTION I
        'respondent_first_name',
        'respondent_middle_name',
        'respondent_last_name',
        'respondent_age',
        'respondent_civil_status',
        'respondent_sex',
        'respondent_religion',
        'respondent_educational_attainment',
        // SECTION II
        'family_composition',
        // SECTION III
        'livelihood_options',
        'interested_in_livelihood_training',
        'desired_training',
        // SECTION IV
        'barangay_educational_facilities',
        'household_member_currently_studying',
        'interested_in_continuing_studies',
        'areas_of_educational_interest',
        'preferred_training_time',
        'preferred_training_days',
        // SECTION V
        'common_illnesses',
        'action_when_sick',
        'barangay_medical_supplies_available',
        'has_barangay_health_programs',
        'benefits_from_barangay_programs',
        'programs_benefited_from',
        'water_source',
        'water_source_distance',
        'garbage_disposal_method',
        'has_own_toilet',
        'toilet_type',
        'keeps_animals',
        'animals_kept',
        // SECTION VI
        'house_type',
        'tenure_status',
        'has_electricity',
        'light_source_without_power',
        'appliances_owned',
        // SECTION VII
        'barangay_recreational_facilities',
        'use_of_free_time',
        'member_of_organization',
        'organization_types',
        'organization_meeting_frequency',
        'organization_usual_activities',
        'household_members_in_organization',
        'position_in_organization',
        // SECTION VIII
        'family_problems',
        'health_problems',
        'educational_problems',
        'employment_problems',
        'infrastructure_problems',
        'economic_problems',
        'security_problems',
        // SECTION IX
        'barangay_service_ratings',
        'general_feedback',
        'available_for_training',
        'reason_not_available',
    ];

    protected $casts = [
        'respondent_educational_attainment' => 'json',
        'family_composition' => 'json',
        'livelihood_options' => 'json',
        'desired_training' => 'json',
        'barangay_educational_facilities' => 'json',
        'areas_of_educational_interest' => 'json',
        'preferred_training_days' => 'json',
        'common_illnesses' => 'json',
        'action_when_sick' => 'json',
        'barangay_medical_supplies_available' => 'json',
        'programs_benefited_from' => 'json',
        'water_source' => 'json',
        'garbage_disposal_method' => 'json',
        'toilet_type' => 'json',
        'animals_kept' => 'json',
        'house_type' => 'json',
        'tenure_status' => 'json',
        'light_source_without_power' => 'json',
        'appliances_owned' => 'json',
        'barangay_recreational_facilities' => 'json',
        'use_of_free_time' => 'json',
        'organization_types' => 'json',
        'organization_usual_activities' => 'json',
        'household_members_in_organization' => 'json',
        'family_problems' => 'json',
        'health_problems' => 'json',
        'educational_problems' => 'json',
        'employment_problems' => 'json',
        'infrastructure_problems' => 'json',
        'economic_problems' => 'json',
        'security_problems' => 'json',
        'barangay_service_ratings' => 'json',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }
}
