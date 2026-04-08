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
        Schema::create('budget_utilizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_program_id')->constrained()->onDelete('cascade');
            $table->date('date_spent');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('transaction_type', ['expense', 'adjustment'])->default('expense');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_utilizations');
    }
};
