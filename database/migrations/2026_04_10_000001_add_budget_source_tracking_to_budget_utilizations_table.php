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
        Schema::table('budget_utilizations', function (Blueprint $table) {
            // Budget source tracking
            $table->string('budget_source')->nullable()->comment('Where the budget was acquired from (e.g., University Fund, External Grant, Donation)');
            $table->text('source_description')->nullable()->comment('Detailed description of the budget source');
            $table->json('people_involved')->nullable()->comment('List of people involved with their roles (names, positions, offices)');
            $table->json('offices_involved')->nullable()->comment('List of offices/departments involved in the budget allocation or approval');
            $table->string('approval_status')->default('pending')->comment('Status of budget approval (pending, approved, rejected)');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('User ID of the person who approved this budget');
            $table->timestamp('approved_at')->nullable()->comment('Timestamp of approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_utilizations', function (Blueprint $table) {
            $table->dropColumn([
                'budget_source',
                'source_description',
                'people_involved',
                'offices_involved',
                'approval_status',
                'approved_by',
                'approved_at',
            ]);
        });
    }
};
