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
        Schema::create('beneficiary_extension_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->onDelete('cascade');
            $table->foreignId('extension_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['beneficiary_id', 'extension_program_id'], 'beneficiary_ext_prog_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_extension_program');
    }
};
