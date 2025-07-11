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
        Schema::create('entity_custom_values', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('custom_field_id')->constrained()->onDelete('cascade');
            $table->text('field_value')->nullable();
            $table->timestamps();
            
            $table->index(['entity_type', 'entity_id', 'custom_field_id'], 'entity_custom_values_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_custom_values');
    }
};
