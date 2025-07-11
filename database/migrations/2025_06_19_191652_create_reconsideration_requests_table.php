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
        Schema::create('reconsideration_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judgment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reconsideration_type_id')->constrained()->onDelete('cascade');
            $table->text('reason');
            $table->foreignId('status_id')->constrained('reconsideration_statuses')->onDelete('cascade');
            $table->text('decision')->nullable();
            $table->date('decision_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reconsideration_requests');
    }
};
