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
            // إضافة عمود content_id كمفتاح أجنبي يشير إلى جدول course_contents
            $table->foreignId('content_id')->nullable()->after('question_type_id')->constrained('course_contents')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // حذف العمود في حالة التراجع
            $table->dropForeign(['content_id']);
            $table->dropColumn('content_id');
        });
    }
};
