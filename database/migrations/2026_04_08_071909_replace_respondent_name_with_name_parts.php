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
            // Replace respondent_name with name parts
            $table->dropColumn('respondent_name');
            $table->string('respondent_first_name')->nullable();
            $table->string('respondent_middle_name')->nullable();
            $table->string('respondent_last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('needs_assessments', function (Blueprint $table) {
            $table->dropColumn(['respondent_first_name', 'respondent_middle_name', 'respondent_last_name']);
            $table->string('respondent_name')->nullable();
        });
    }
};
