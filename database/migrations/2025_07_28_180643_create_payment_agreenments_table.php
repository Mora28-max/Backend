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
        Schema::create('payment_agreenments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_folio');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('process_status_id')->constrained('process_status')->onDelete('cascade');
            $table->decimal('total_debt', 10, 2);
            $table->decimal('initial_payment_amount', 10, 2);
            $table->string('payment_breakdown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_agreenments');
    }
};
