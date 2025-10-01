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
        Schema::create('measured_service_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('use_of_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('classification_type_id')->constrained()->onDelete('cascade');
            $table->string('year');
            $table->decimal('amount', 10, 2);
            $table->boolean('charge_vat');
            $table->decimal('lower_limit', 10, 2);
            $table->decimal('upper_limit', 10, 2);
            $table->decimal('cost_to_exceed', 10, 2);
            $table->boolean('charge_exceed_vat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measured_service_rates');
    }
};
