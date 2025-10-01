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
            $table->decimal('old_reading', 8, 2)->nullable()->after('drainage_discount');
            $table->decimal('new_reading', 8, 2)->nullable()->after('old_reading');
            $table->decimal('difference', 8, 2)->nullable()->after('new_reading');
            $table->decimal('excessive', 8, 2)->default(0)->after('difference');
            $table->decimal('excess_water_amount', 8, 2)->default(0)->nullable()->after('excessive');
            $table->decimal('excess_water_surcharge', 8, 2)->default(0)->nullable()->after('excess_water_amount');
            $table->decimal('excess_drainage_amount', 8, 2)->default(0)->nullable()->after('excess_water_surcharge');
            $table->decimal('excess_drainage_surcharge', 8, 2)->default(0)->nullable()->after('excess_drainage_amount');
            $table->decimal('subtotal_excess', 8, 2)->default(0)->nullable()->after('excess_drainage_surcharge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            //
            $table->dropColumn('old_reading');
            $table->dropColumn('new_reading');
            $table->dropColumn('difference');
            $table->dropColumn('excessive');
            $table->dropColumn('excess_water_amount');
            $table->dropColumn('excess_water_surcharge');
            $table->dropColumn('excess_drainage_amount');
            $table->dropColumn('excess_drainage_surcharge');
            $table->dropColumn('subtotal_excess');
        });
    }
};
