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
        Schema::create('virtual_active_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_case_file_id')->constrained('store_case_files');
            $table->string('title');
            $table->foreignId('plaintiff_id')->constrained('users');
            $table->foreignId('defendant_id')->constrained('users');
            $table->foreignId('judge_id')->nullable()->constrained('users');
            $table->enum('status', ['active', 'in_progress', 'completed'])->default('active');
            $table->timestamp('started_at');
            $table->timestamp('expected_end_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_active_cases');
    }
};
