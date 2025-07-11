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
        Schema::create('virtual_court_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('active_case_id')->constrained('virtual_active_cases')->onDelete('cascade');
            $table->string('title');
            $table->string('case_number');
            $table->foreignId('court_type_id')->constrained('virtual_court_types')->onDelete('cascade');
            $table->year('year');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('virtual_court_archive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_archive_id')->constrained('virtual_court_archives')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->enum('file_category', ['judgment', 'defense', 'session', 'other'])->default('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_court_archive_files');
        Schema::dropIfExists('virtual_court_archives');
    }
};
