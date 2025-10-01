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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('client_backup_contacts_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_folio');
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->foreignId('report_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('report_subcategory_id')->constrained()->onDelete('cascade');
            $table->foreignId('report_priority_id')->constrained()->onDelete('cascade');
            $table->foreignId('process_status_id')->constrained('process_status')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('should_be_paid')->default(false);
            $table->string('payment_folio')->nullable();
            $table->longText('images_for_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
