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
        Schema::create('case_archives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('case_number');
            $table->foreignId('court_type_id')->constrained()->onDelete('cascade');
            $table->year('year');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_archives');
    }
};
