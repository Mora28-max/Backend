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
        Schema::table('remaining_payments', function (Blueprint $table) {
            //
            $table->string('payment_type_id')->after('customer_id')->nullable()->default(null);
            $table->boolean('canceled')->default(false)->after('payment_type_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remaining_payments', function (Blueprint $table) {
            //
            $table->dropColumn('payment_type_id');
            $table->dropColumn('canceled');
        });
    }
};
