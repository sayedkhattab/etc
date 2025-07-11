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
        Schema::table('questions', function (Blueprint $table) {
            // إضافة عمود level_id للربط مع المستويات
            if (!Schema::hasColumn('questions', 'level_id')) {
                $table->foreignId('level_id')
                      ->nullable()
                      ->constrained('levels')
                      ->nullOnDelete();
            }
            
            // إضافة عمود is_pretest لتحديد إذا كان السؤال جزء من اختبار تحديد المستوى
            if (!Schema::hasColumn('questions', 'is_pretest')) {
                $table->boolean('is_pretest')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'level_id')) {
                $table->dropForeign(['level_id']);
                $table->dropColumn('level_id');
            }
            
            if (Schema::hasColumn('questions', 'is_pretest')) {
                $table->dropColumn('is_pretest');
            }
        });
    }
};
