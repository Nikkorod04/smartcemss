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
        Schema::create('extension_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description', 1500)->nullable();
            $table->text('goals')->nullable();
            $table->text('objectives')->nullable();
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->integer('target_beneficiaries')->nullable();
            $table->json('beneficiary_categories')->nullable();
            $table->decimal('allocated_budget', 10, 2)->nullable();
            $table->foreignId('program_lead_id')->nullable()->constrained('faculties')->onDelete('set null');
            $table->json('partners')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('related_communities')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', ['draft', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extension_programs');
    }
};
