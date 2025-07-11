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
        Schema::create('virtual_judgment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_judgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('active_case_id')->constrained('virtual_active_cases')->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('judgment_type_id')->constrained('virtual_judgment_types')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->date('judgment_date');
            $table->enum('status', ['draft', 'published', 'final'])->default('draft');
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('virtual_judgment_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judgment_id')->constrained('virtual_judgments')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->timestamps();
        });

        Schema::create('virtual_reconsideration_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judgment_id')->constrained('virtual_judgments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('awareness_date');
            $table->text('appeal_text');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_reconsideration_requests');
        Schema::dropIfExists('virtual_judgment_attachments');
        Schema::dropIfExists('virtual_judgments');
        Schema::dropIfExists('virtual_judgment_types');
    }
};
