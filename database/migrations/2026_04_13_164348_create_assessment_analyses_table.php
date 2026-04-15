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
        Schema::create('assessment_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('needs_assessment_id');
            $table->longText('raw_extracted_data')->nullable(); // Raw response from ChatGPT
            $table->longText('extracted_fields')->nullable(); // JSON: Structured form fields
            $table->longText('problems_identified')->nullable(); // JSON: Array of identified issues
            $table->longText('recommendations')->nullable(); // JSON: Array of recommendations
            $table->text('summary')->nullable(); // Brief summary of the assessment
            $table->integer('confidence_score')->nullable(); // 0-100: Confidence in extraction
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->text('error_message')->nullable(); // Error details if failed
            $table->json('metadata')->nullable(); // Additional processing info
            $table->timestamps();
            
            $table->foreign('needs_assessment_id')
                  ->references('id')
                  ->on('needs_assessments')
                  ->onDelete('cascade');
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_analyses');
    }
};
