<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the foreign key exists before trying to drop it
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'purchase_order_product'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME = 'purchase_order_product_product_id_foreign'
        ");

        if (!empty($foreignKeys)) {
            Schema::table('purchase_order_product', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }

        // Then drop the table
        Schema::dropIfExists('products');

        // Create the new table with only material_id
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('material_id')->nullable();
            $table->timestamps();
        });

        // Recreate the foreign key constraint
        if (Schema::hasTable('purchase_order_product') && Schema::hasColumn('purchase_order_product', 'product_id')) {
            Schema::table('purchase_order_product', function (Blueprint $table) {
                $table->foreign('product_id')
                      ->references('id')
                      ->on('products')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if the foreign key exists before trying to drop it
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
            AND TABLE_NAME = 'purchase_order_product'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME = 'purchase_order_product_product_id_foreign'
        ");

        if (!empty($foreignKeys)) {
            Schema::table('purchase_order_product', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }

        Schema::dropIfExists('products');
    }
};
