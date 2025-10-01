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
        //
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            $table->renameColumn('water_drainage_subtotal', 'drainage_amount_subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            $table->renameColumn('drainage_amount_subtotal', 'water_drainage_subtotal');
        });
    }
};
