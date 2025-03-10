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
        // First, add all new columns as nullable to avoid the not-null violation
        Schema::table('products', function (Blueprint $table) {
            $table->string('material_id')->nullable();
            $table->string('legacy_material')->nullable();
            $table->string('contract')->nullable();
            $table->decimal('order_quantity', 10, 2)->nullable();
            $table->string('qty_unit')->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->decimal('price_per_uon', 10, 2)->nullable();
            $table->decimal('net_value', 10, 2)->nullable();
            $table->decimal('vat_rate', 5, 2)->nullable();
            $table->decimal('vat_value', 10, 2)->nullable();
            $table->date('delivery_date')->nullable();
        });

        // Then drop the old columns
        Schema::table('products', function (Blueprint $table) {
            // Keep the description column since it's in both old and new schema
            $table->dropColumn([
                'name',
                'price',
                'sku',
                'stock',
                'status'
            ]);
        });

        // If you want to make some columns required after migration,
        // you can do it in a separate migration after data is migrated
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Restore old columns
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Drop new columns
            $table->dropColumn([
                'material_id',
                'legacy_material',
                'contract',
                'order_quantity',
                'qty_unit',
                'price_per_unit',
                'price_per_uon',
                'net_value',
                'vat_rate',
                'vat_value',
                'delivery_date'
            ]);
        });
    }
};
