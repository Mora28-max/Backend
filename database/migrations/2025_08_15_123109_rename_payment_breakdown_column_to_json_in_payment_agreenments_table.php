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
        Schema::table('payment_agreements', function (Blueprint $table) {
            $table->json('payment_breakdown')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payment_agreements', function (Blueprint $table) {
            $table->string('payment_breakdown')->change();
        });
    }
};
