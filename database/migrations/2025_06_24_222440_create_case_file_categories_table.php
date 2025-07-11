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
        Schema::create('virtual_case_file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_case_file_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained('virtual_case_files')->onDelete('cascade');
            $table->foreignId('case_file_category_id')->constrained('virtual_case_file_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_case_file_category');
        Schema::dropIfExists('virtual_case_file_categories');
    }
};
