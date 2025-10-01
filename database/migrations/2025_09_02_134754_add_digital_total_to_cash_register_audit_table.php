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
        Schema::table('cash_register_audit', function (Blueprint $table) {
            $table->decimal('digital_total', 10, 2)->after('system_cash')->default(0);
            $table->json('digital_details')->after('details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_register_audit', function (Blueprint $table) {
            $table->dropColumn('digital_total');
            $table->dropColumn('digital_details');
        });
    }
};
