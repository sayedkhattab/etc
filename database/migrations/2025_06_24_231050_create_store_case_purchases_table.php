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
        Schema::create('store_case_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained('store_case_files')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', ['مدعي', 'مدعى عليه'])->default('مدعي');
            $table->decimal('price', 10, 2);
            $table->string('payment_method')->default('credit');
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->foreignId('active_case_id')->nullable()->constrained('virtual_active_cases')->nullOnDelete();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_case_purchases');
    }
};
