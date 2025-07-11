<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_types', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_types', 'icon')) {
                $table->string('icon')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assessment_types', function (Blueprint $table) {
            if (Schema::hasColumn('assessment_types', 'icon')) {
                $table->dropColumn('icon');
            }
        });
    }
}; 