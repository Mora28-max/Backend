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
        Schema::create('goods', function (Blueprint $table) {
            $table->id(); // id_goods int auto-increment
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->unsignedBigInteger('id_status'); // FK a goods_status
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('id_category'); // FK a categories
            
            $table->string('url_evidence')->nullable();       // Evidencia opcional
            $table->string('public_id_evidence')->nullable(); // <-- NUEVO
            
            $table->string('url_invoice')->nullable();        // Factura opcional
            $table->string('public_id_invoice')->nullable();  // <-- NUEVO (opcional)

            $table->unsignedBigInteger('id_user'); // FK a users
            $table->unsignedBigInteger('id_provider'); // FK a providers
            $table->timestamps();

            // Relaciones
            $table
            ->foreign('id_status')
            ->references('id')
            ->on('goods_status')
            ->onDelete('cascade');

            $table
            ->foreign('id_category')
            ->references('id')
            ->on('categories')
            ->onDelete('cascade');

            $table
            ->foreign('id_user')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table
            ->foreign('id_provider')
            ->references('id')
            ->on('providers')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
};
