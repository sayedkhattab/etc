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
        Schema::table('store_case_files', function (Blueprint $table) {
            $table->string('case_number')->nullable()->after('id')->comment('رقم القضية الفريد المرتبط بأرشيف القضايا');
            $table->enum('case_type', ['مدعي', 'مدعى عليه'])->after('description')->comment('نوع القضية: مدعي أو مدعى عليه');
            $table->index('case_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_case_files', function (Blueprint $table) {
            $table->dropColumn('case_number');
            $table->dropColumn('case_type');
        });
    }
};
