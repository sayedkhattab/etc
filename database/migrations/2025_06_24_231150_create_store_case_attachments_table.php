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
        Schema::create('store_case_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained('store_case_files')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type');
            $table->enum('role', ['مدعي', 'مدعى عليه', 'عام'])->default('عام');
            $table->boolean('is_visible_before_purchase')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_case_attachments');
    }
};
