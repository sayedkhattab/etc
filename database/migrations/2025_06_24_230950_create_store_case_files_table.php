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
        Schema::create('store_case_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('store_case_categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('facts')->nullable();
            $table->text('legal_articles')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('difficulty', ['سهل', 'متوسط', 'صعب'])->default('متوسط');
            $table->integer('estimated_duration_days')->default(7);
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('purchases_count')->default(0);
            $table->foreignId('admin_id')->constrained('admins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_case_files');
    }
};
