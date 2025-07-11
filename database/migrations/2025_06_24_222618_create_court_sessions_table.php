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
        Schema::create('virtual_session_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_session_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_court_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('active_case_id')->constrained('virtual_active_cases')->onDelete('cascade');
            $table->foreignId('session_type_id')->constrained('virtual_session_types')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('zoom_link')->nullable();
            $table->string('recording_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('status_id')->constrained('virtual_session_statuses')->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('virtual_session_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_session_id')->constrained('virtual_court_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['judge', 'plaintiff', 'defendant', 'witness']);
            $table->boolean('attended')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_session_attendees');
        Schema::dropIfExists('virtual_court_sessions');
        Schema::dropIfExists('virtual_session_types');
        Schema::dropIfExists('virtual_session_statuses');
    }
};
