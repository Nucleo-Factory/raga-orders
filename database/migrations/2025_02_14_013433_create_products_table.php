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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->text('description')->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->string('material_id')->nullable();
            $table->string('legacy_material')->nullable();
            $table->string('contract')->nullable();
            $table->integer('order_quantity')->nullable();
            $table->string('qty_unit')->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->decimal('price_per_uon', 10, 2)->nullable();
            $table->decimal('net_value', 10, 2)->nullable();
            $table->decimal('vat_rate', 5, 2)->nullable();
            $table->decimal('vat_value', 10, 2)->nullable();
            $table->date('delivery_date')->nullable();

            $table->timestamps();

            $table->index('description');
            $table->index('material_id');
            $table->index('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
