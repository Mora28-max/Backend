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
            $table->decimal('water_surcharge_discount', 10, 2)->after('water_surcharge')->default(0);
            $table->decimal('drainage_surcharge_discount', 10, 2)->after('drainage_surcharge')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_service_charges', function (Blueprint $table) {
            //
            $table->dropColumn('water_surcharge_discount');
            $table->dropColumn('drainage_surcharge_discount');
        });
    }
};
