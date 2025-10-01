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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('tracking_folio');
            $table->integer('months_behind');
            $table->decimal('amount', 10, 2);
            $table->foreignId('process_status_id')->constrained('process_status')->onDelete('cascade');
            $table->foreignId('notice_type_id')->constrained('notice_types')->onDelete('cascade');
            $table->string('comment')->nullable();
            $table->string('evidence')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
