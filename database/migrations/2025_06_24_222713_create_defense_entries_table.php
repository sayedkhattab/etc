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
        Schema::create('virtual_defense_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('active_case_id')->constrained('virtual_active_cases')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_defense_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defense_entry_id')->constrained('virtual_defense_entries')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_defense_attachments');
        Schema::dropIfExists('virtual_defense_entries');
    }
};
