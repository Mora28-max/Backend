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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('rfc')->nullable();
            $table->string('voter_key')->nullable();
            $table->integer('type_person');
            $table->string('address');
            $table->text('int_num')->nullable();
            $table->string('ext_num')->nullable();
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->string('geolocation')->nullable();
            $table->text('reference')->nullable();
            $table->string('url_image')->nullable();
            $table->foreignId('customer_type_id')->constrained('customer_types')->onDelete('cascade');
            $table->foreignId('use_of_type_id')->constrained('use_of_types')->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
            $table->foreignId('service_status_id')->constrained('service_status')->onDelete('cascade');
            $table->foreignId('classification_type_id')->constrained('classification_types')->onDelete('cascade');
            $table->boolean('drainage_use');
            $table->string('storage_capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
