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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'judge', 'student', 'witness'])->default('student')->after('email');
            $table->boolean('is_approved')->default(false)->after('role');
            $table->string('phone')->nullable()->after('is_approved');
            $table->string('national_id')->nullable()->after('phone');
            $table->string('national_id_front')->nullable()->after('national_id');
            $table->string('national_id_back')->nullable()->after('national_id_front');
            $table->string('address')->nullable()->after('national_id_back');
            $table->string('specialty')->nullable()->after('address');
            $table->string('degree')->nullable()->after('specialty');
            $table->string('avatar')->nullable()->after('degree');
            $table->text('bio')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'is_approved',
                'phone',
                'national_id',
                'national_id_front',
                'national_id_back',
                'address',
                'specialty',
                'degree',
                'avatar',
                'bio'
            ]);
        });
    }
};
