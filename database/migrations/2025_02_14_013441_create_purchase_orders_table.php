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
        Schema::create('purchase_orders', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('company_id')->constrained()->onDelete('cascade');

            $table->string('order_number')->unique();
            $table->enum('status', ['draft', 'pending', 'approved', 'shipped', 'delivered', 'cancelled']);
            $table->decimal('total_amount', 10, 2)->default(0);

            // Información general
            $table->string('vendor')->nullable();
            $table->string('material_description')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->decimal('quantity_kgs', 10, 2)->nullable();
            $table->integer('estimated_pallets')->nullable();
            $table->integer('actual_pallets')->nullable();
            $table->string('shipping_number')->nullable();
            $table->string('booking_number')->nullable();
            $table->string('container_number')->nullable();

            // Dimensiones
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('width_cm', 8, 2)->nullable();
            $table->decimal('length_cm', 8, 2)->nullable();
            $table->decimal('volume_m3', 10, 3)->nullable();

            // Fechas
            $table->datetime('requested_delivery_date')->nullable();
            $table->datetime('estimated_pickup_date')->nullable();
            $table->datetime('actual_pickup_date')->nullable();
            $table->datetime('estimated_hub_arrival')->nullable();
            $table->datetime('actual_hub_arrival')->nullable();
            $table->datetime('etd_date')->nullable(); // Estimated Time of Departure
            $table->datetime('atd_date')->nullable(); // Actual Time of Departure
            $table->datetime('eta_date')->nullable(); // Estimated Time of Arrival
            $table->datetime('ata_date')->nullable(); // Actual Time of Arrival

            // Costos
            $table->decimal('insurance_cost', 10, 2)->nullable();
            $table->decimal('ground_transport_cost_1', 10, 2)->nullable();
            $table->decimal('ground_transport_cost_2', 10, 2)->nullable();
            $table->decimal('estimated_pallet_cost', 10, 2)->nullable();
            $table->decimal('other_costs', 10, 2)->nullable();
            $table->decimal('other_expenses', 10, 2)->nullable();

            $table->text('notes')->nullable();
            $table->text('comments')->nullable();

            $table->timestamps();

            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
