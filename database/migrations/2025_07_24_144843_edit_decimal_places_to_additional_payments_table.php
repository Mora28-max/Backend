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
        Schema::table('additional_payments', function (Blueprint $table) {
            //
            $table->decimal('subtotal', 10, 2)->change();
            $table->decimal('vat', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('additional_payments', function (Blueprint $table) {
            //
            $table->decimal('subtotal', 5, 2)->change();
            $table->decimal('vat', 5, 2)->change();
            $table->decimal('total', 5, 2)->change();
        });
    }
};
