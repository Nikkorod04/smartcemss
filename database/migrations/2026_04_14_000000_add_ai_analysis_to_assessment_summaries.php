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
        Schema::table('assessment_summaries', function (Blueprint $table) {
            // Add AI analysis columns
            $table->longText('ai_analysis')->nullable()->after('baseline_satisfaction_score');
            $table->json('ai_interventions')->nullable()->after('ai_analysis');
            $table->timestamp('ai_analysis_generated_at')->nullable()->after('ai_interventions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_summaries', function (Blueprint $table) {
            $table->dropColumn(['ai_analysis', 'ai_interventions', 'ai_analysis_generated_at']);
        });
    }
};
