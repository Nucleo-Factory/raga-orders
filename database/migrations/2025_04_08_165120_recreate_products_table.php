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
        // First drop the foreign key constraint
        Schema::table('purchase_order_product', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Then drop the table
        Schema::dropIfExists('products');

        // Create the new table with the specified fields
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('material_id')->nullable();
            $table->string('short_text')->nullable();
            $table->string('supplying_plant')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('plant')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendo_code')->nullable();
            $table->timestamps();
        });

        // Recreate the foreign key constraint
        Schema::table('purchase_order_product', function (Blueprint $table) {
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First drop the foreign key constraint
        Schema::table('purchase_order_product', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('products');
    }
};
