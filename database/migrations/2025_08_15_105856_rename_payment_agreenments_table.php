<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePaymentAgreenmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('payment_agreenments', 'payment_agreements');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('payment_agreements', 'payment_agreenments');
    }
};
