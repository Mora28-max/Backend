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
        Schema::create('monthly_service_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->boolean('has_drainage')->default(false);
            $table->integer('year');
            $table->integer('month');
            $table->decimal('water_amount', 10, 2);
            $table->decimal('drainage_amount', 10, 2);
            $table->integer('overdue_months')->nullable();
            $table->decimal('water_surcharge', 10, 2)->nullable();
            $table->decimal('drainage_surcharge', 10, 2)->nullable();
            $table->decimal('water_discount', 10, 2)->nullable();
            $table->decimal('drainage_discount', 10, 2)->nullable();
            $table->decimal('water_amount_subtotal', 10, 2);
            $table->decimal('water_drainage_subtotal', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('folio')->nullable();
            $table->string('monthly_payment_statement_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_service_charges');
    }
};
