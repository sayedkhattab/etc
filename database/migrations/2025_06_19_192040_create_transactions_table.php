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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['course_purchase', 'case_purchase']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->foreignId('status_id')->constrained('payment_statuses')->onDelete('cascade');
            $table->dateTime('transaction_date');
            $table->json('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
