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
        Schema::table('student_content_progress', function (Blueprint $table) {
            $table->boolean('is_required_content')->default(false)->after('fully_watched')->comment('يشير إلى ما إذا كان هذا محتوى إجباري للطالب بسبب رسوبه في سؤال');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_content_progress', function (Blueprint $table) {
            $table->dropColumn('is_required_content');
        });
    }
};
