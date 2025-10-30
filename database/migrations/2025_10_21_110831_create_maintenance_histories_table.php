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
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id(); // id autoincremental
            $table->string('code')->nullable();
            $table->unsignedBigInteger('id_goods'); // FK a goods
            $table->date('date')->nullable();
            $table->text('observations')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->unsignedBigInteger('id_type_maintenance'); // FK a type_maintenances
            $table->unsignedBigInteger('id_user'); // FK a users
            $table->timestamps();

            // Relaciones
            $table
                ->foreign('id_goods')
                ->references('id')
                ->on('goods')
                ->onDelete('cascade');

            $table
                ->foreign('id_type_maintenance')
                ->references('id')
                ->on('type_maintenances')
                ->onDelete('cascade');

            $table
                ->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_histories');
    }
};
