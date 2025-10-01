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
        Schema::create('water_tank_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_tank_id')->constrained('water_tank')->onDelete('cascade');
            $table->decimal('water_level', 8, 2);
            $table->decimal('water_reception', 8, 2);
            $table->dateTime('log_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_tank_logs');
    }
};
