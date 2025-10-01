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
        Schema::table('notices_reports', function (Blueprint $table) {
            //
            $table->foreignId('customer_id')->after('report_id')->constrained()->onDelete('cascade');
            $table->boolean('finished_process')->after('customer_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices_reports', function (Blueprint $table) {
            //
            $table->dropForeign('notices_reports_customer_id_foreign');
            $table->dropColumn('customer_id');
            $table->dropColumn('finished_process');
        });
    }
};
