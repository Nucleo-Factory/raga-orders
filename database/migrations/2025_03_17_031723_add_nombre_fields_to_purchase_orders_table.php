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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Agregar campo ship_to_nombre después de ship_to_telefono
            $table->string('ship_to_nombre')->nullable()->after('ship_to_telefono');

            // Agregar campo bill_to_nombre después de bill_to_telefono
            $table->string('bill_to_nombre')->nullable()->after('bill_to_telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('ship_to_nombre');
            $table->dropColumn('bill_to_nombre');
        });
    }
};
