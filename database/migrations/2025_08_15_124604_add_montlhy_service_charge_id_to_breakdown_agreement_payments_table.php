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
        Schema::table('breakdown_agreement_payments', function (Blueprint $table) {
            $table->json('monthly_service_charge_ids')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breakdown_agreement_payments', function (Blueprint $table) {
            $table->dropColumn('monthly_service_charge_ids');
        });
    }
};
