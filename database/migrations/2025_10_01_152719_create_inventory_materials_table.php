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
        Schema::create('inventory_materials', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');         // PK autoincrement
            $table->string('code_materials');       // Código del material
            $table->integer('stock'); 
            $table->integer('stock_min')->default(0); // ← Aquí              // Cantidad en stock
            $table->text('description')->nullable(); // Descripción opcional
            $table->decimal('cost', 10, 2);         // Costo con 2 decimales
             
            $table->string('url_evidence')->nullable();       // Evidencia opcional
            $table->string('public_id_evidence')->nullable(); // <-- NUEVO
            
            $table->string('url_invoice')->nullable();        // Factura opcional
            $table->string('public_id_invoice')->nullable();  // <-- NUEVO (opcional)
            
            $table->unsignedBigInteger('provider_id');   // FK a providers
            $table->unsignedBigInteger('unit_type_id');  // FK a unit_types
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            // Relaciones
            $table->foreign('provider_id')
                  ->references('id')
                  ->on('providers')
                  ->onDelete('cascade');

           $table->foreign('unit_type_id')
      ->references('id')
      ->on('unities')
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
        Schema::dropIfExists('inventory_materials');
    }
};
