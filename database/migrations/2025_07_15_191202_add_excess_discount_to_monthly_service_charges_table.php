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
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            //
            $table->decimal('excess_water_discount', 8, 2)->default(0)->nullable()->after('excess_water_amount');
            $table->decimal('excess_drainage_discount', 8, 2)->default(0)->nullable()->after('excess_drainage_amount');
            $table->decimal('excess_water_surcharge_discount', 8, 2)->default(0)->nullable()->after('excess_water_surcharge');
            $table->decimal('excess_drainage_surcharge_discount', 8, 2)->default(0)->nullable()->after('excess_drainage_surcharge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            //
            $table->dropColumn('excess_water_discount');
            $table->dropColumn('excess_drainage_discount');
            $table->dropColumn('excess_water_surcharge_discount');
            $table->dropColumn('excess_drainage_surcharge_discount');
        });
    }
};
