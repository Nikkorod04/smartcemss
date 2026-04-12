<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old table if it exists and recreate with quarter/year
        if (Schema::hasTable('assessment_summaries')) {
            Schema::drop('assessment_summaries');
        }

        Schema::create('assessment_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained('communities')->onDelete('cascade');
            $table->string('quarter')->nullable(); // Q1, Q2, Q3, Q4
            $table->integer('year')->nullable(); // 2025, 2026, etc
            
            // Count Metrics
            $table->integer('total_responses')->default(0);
            
            // Demographics (JSON for distributions)
            $table->json('gender_distribution')->nullable();
            $table->json('religion_distribution')->nullable();
            $table->json('education_distribution')->nullable();
            $table->json('civil_status_distribution')->nullable();
            
            // Interests & Training
            $table->json('livelihood_interests')->nullable();
            $table->json('educational_interests')->nullable();
            
            // Problems Identified (most common)
            $table->json('health_problems')->nullable();
            $table->json('family_problems')->nullable();
            $table->json('employment_problems')->nullable();
            $table->json('infrastructure_problems')->nullable();
            $table->json('economic_problems')->nullable();
            $table->json('security_problems')->nullable();
            
            // Infrastructure & Services
            $table->json('water_sources')->nullable();
            $table->json('house_types')->nullable();
            $table->decimal('electricity_access_percentage', 5, 2)->default(0);
            $table->decimal('organization_membership_percentage', 5, 2)->default(0);
            $table->decimal('training_availability_percentage', 5, 2)->default(0);
            $table->decimal('avg_service_satisfaction', 3, 2)->nullable();
            
            // Baseline for linked programs
            $table->decimal('baseline_satisfaction_score', 3, 2)->nullable();
            
            // Metadata
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraint: one summary per community per quarter-year
            $table->unique(['community_id', 'quarter', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_summaries');
    }
};
