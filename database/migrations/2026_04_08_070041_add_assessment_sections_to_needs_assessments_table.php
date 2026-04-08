<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('needs_assessments', function (Blueprint $table) {
            // SECTION I: Identifying Information
            $table->string('respondent_name')->nullable();
            $table->integer('respondent_age')->nullable();
            $table->string('respondent_civil_status')->nullable();
            $table->enum('respondent_sex', ['Male', 'Female', 'Other'])->nullable();
            $table->string('respondent_religion')->nullable();
            $table->json('respondent_educational_attainment')->nullable();

            // SECTION II: Family Composition
            $table->json('family_composition')->nullable();

            // SECTION III: Economic Aspect
            $table->json('livelihood_options')->nullable();
            $table->enum('interested_in_livelihood_training', ['Yes', 'No'])->nullable();
            $table->json('desired_training')->nullable();

            // SECTION IV: Educational Aspect
            $table->json('barangay_educational_facilities')->nullable();
            $table->enum('household_member_currently_studying', ['Yes', 'No'])->nullable();
            $table->enum('interested_in_continuing_studies', ['Yes', 'No'])->nullable();
            $table->json('areas_of_educational_interest')->nullable();
            $table->enum('preferred_training_time', ['Morning 8-12', 'Afternoon 1:30-5'])->nullable();
            $table->json('preferred_training_days')->nullable();

            // SECTION V: Health, Sanitation, Environmental
            $table->json('common_illnesses')->nullable();
            $table->json('action_when_sick')->nullable();
            $table->json('barangay_medical_supplies_available')->nullable();
            $table->enum('has_barangay_health_programs', ['Yes', 'No'])->nullable();
            $table->enum('benefits_from_barangay_programs', ['Yes', 'No'])->nullable();
            $table->json('programs_benefited_from')->nullable();
            $table->json('water_source')->nullable();
            $table->enum('water_source_distance', ['Just outside', '250 meters away', 'No idea'])->nullable();
            $table->json('garbage_disposal_method')->nullable();
            $table->enum('has_own_toilet', ['Yes', 'No'])->nullable();
            $table->json('toilet_type')->nullable();
            $table->enum('keeps_animals', ['Yes', 'No'])->nullable();
            $table->json('animals_kept')->nullable();

            // SECTION VI: Housing and Basic Amenities
            $table->json('house_type')->nullable();
            $table->json('tenure_status')->nullable();
            $table->enum('has_electricity', ['Yes', 'No'])->nullable();
            $table->json('light_source_without_power')->nullable();
            $table->json('appliances_owned')->nullable();

            // SECTION VII: Recreational Facilities
            $table->json('barangay_recreational_facilities')->nullable();
            $table->json('use_of_free_time')->nullable();
            $table->enum('member_of_organization', ['Yes', 'No'])->nullable();
            $table->json('organization_types')->nullable();
            $table->enum('organization_meeting_frequency', ['Weekly', 'Monthly', 'Twice a month', 'Yearly'])->nullable();
            $table->text('organization_usual_activities')->nullable();
            $table->json('household_members_in_organization')->nullable();
            $table->string('position_in_organization')->nullable();

            // SECTION VIII: Other Needs & Problems
            $table->json('family_problems')->nullable();
            $table->json('health_problems')->nullable();
            $table->json('educational_problems')->nullable();
            $table->json('employment_problems')->nullable();
            $table->json('infrastructure_problems')->nullable();
            $table->json('economic_problems')->nullable();
            $table->json('security_problems')->nullable();

            // SECTION IX: Summary
            $table->json('barangay_service_ratings')->nullable();
            $table->text('general_feedback')->nullable();
            $table->enum('available_for_training', ['Yes', 'No'])->nullable();
            $table->text('reason_not_available')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('needs_assessments', function (Blueprint $table) {
            // Drop all the added columns
            $table->dropColumn([
                'respondent_name', 'respondent_age', 'respondent_civil_status', 'respondent_sex', 'respondent_religion', 'respondent_educational_attainment',
                'family_composition',
                'livelihood_options', 'interested_in_livelihood_training', 'desired_training',
                'barangay_educational_facilities', 'household_member_currently_studying', 'interested_in_continuing_studies', 'areas_of_educational_interest', 'preferred_training_time', 'preferred_training_days',
                'common_illnesses', 'action_when_sick', 'barangay_medical_supplies_available', 'has_barangay_health_programs', 'benefits_from_barangay_programs', 'programs_benefited_from', 'water_source', 'water_source_distance', 'garbage_disposal_method', 'has_own_toilet', 'toilet_type', 'keeps_animals', 'animals_kept',
                'house_type', 'tenure_status', 'has_electricity', 'light_source_without_power', 'appliances_owned',
                'barangay_recreational_facilities', 'use_of_free_time', 'member_of_organization', 'organization_types', 'organization_meeting_frequency', 'organization_usual_activities', 'household_members_in_organization', 'position_in_organization',
                'family_problems', 'health_problems', 'educational_problems', 'employment_problems', 'infrastructure_problems', 'economic_problems', 'security_problems',
                'barangay_service_ratings', 'general_feedback', 'available_for_training', 'reason_not_available',
            ]);
        });
    }
};
