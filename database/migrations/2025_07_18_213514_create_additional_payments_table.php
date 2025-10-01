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
        Schema::create('additional_payments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('concept')->nullable();
            $table->string('notes')->nullable();
            $table->decimal('subtotal', 5, 2)->nullable();
            $table->decimal('vat', 5, 2)->nullable();
            $table->decimal('total', 5, 2)->nullable();
            $table->string('payment_folio')->nullable();
            $table->foreignId('payment_type_id')->nullable()->constrained('payment_types')->onDelete('cascade');
            $table->boolean('canceled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_payments');
    }
};
