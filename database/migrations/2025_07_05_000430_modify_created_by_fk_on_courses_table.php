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
        Schema::table('courses', function (Blueprint $table) {
            // Remove existing foreign key to users and add new foreign key to admins
            if (Schema::hasColumn('courses', 'created_by')) {
                // Drop the old FK constraint if it exists
                $table->dropForeign(['created_by']);

                // Re-create the FK referencing the admins table
                $table->foreign('created_by')
                      ->references('id')
                      ->on('admins')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Rollback: restore foreign key to users table
            if (Schema::hasColumn('courses', 'created_by')) {
                $table->dropForeign(['created_by']);

                $table->foreign('created_by')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }
};
