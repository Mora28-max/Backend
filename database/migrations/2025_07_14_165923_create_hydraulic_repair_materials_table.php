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
        Schema::create('hydraulic_repair_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('class_of_materials_id')->nullable()->constrained('classification_of_materials')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hydraulic_repair_materials');
    }
};
