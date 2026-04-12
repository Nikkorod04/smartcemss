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
        // Add assessment and satisfaction fields to activities table
        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedTinyInteger('pre_assessment_score')->nullable()->comment('Pre-training assessment score (0-100)')->after('allocated_budget');
            $table->unsignedTinyInteger('post_assessment_score')->nullable()->comment('Post-training assessment score (0-100)')->after('pre_assessment_score');
            $table->unsignedTinyInteger('satisfaction_rating')->nullable()->comment('Participant satisfaction rating (1-5)')->after('post_assessment_score');
        });

        // Add baseline fields to extension_programs table
        Schema::table('extension_programs', function (Blueprint $table) {
            $table->unsignedTinyInteger('baseline_knowledge_score')->nullable()->comment('Baseline knowledge level (0-100)')->after('allocated_budget');
            $table->unsignedTinyInteger('baseline_satisfaction_score')->nullable()->comment('Baseline satisfaction score (1-5)')->after('baseline_knowledge_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['pre_assessment_score', 'post_assessment_score', 'satisfaction_rating']);
        });

        Schema::table('extension_programs', function (Blueprint $table) {
            $table->dropColumn(['baseline_knowledge_score', 'baseline_satisfaction_score']);
        });
    }
};
