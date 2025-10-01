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
        Schema::table('meter_reading_schedule', function (Blueprint $table) {
            //
            $table->foreignId('process_status_id')->default(1)->constrained('process_status')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meter_reading_schedule', function (Blueprint $table) {
            //
            $table->dropColumn('process_status_id');
            $table->dropForeign('meter_reading_schedule_process_status_id_foreign');
        });
    }
};
