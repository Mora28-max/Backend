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
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->string('breakdown')->after('description')->nullable();
            $table->foreignId('report_child_subcategory_id')->after('report_subcategory_id')->constrained('report_child_subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            //
            $table->dropForeign('reports_report_child_subcategory_id_foreign');
            $table->dropColumn('breakdown');
            $table->dropColumn('report_child_subcategory_id');
        });
    }
};
