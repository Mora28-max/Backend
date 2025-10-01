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
        Schema::create('fixed_service_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('use_of_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('classification_type_id')->constrained()->onDelete('cascade');
            $table->string('year');
            $table->decimal('amount', 10, 2);
            $table->boolean('charge_vat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_service_rates');
    }
};
