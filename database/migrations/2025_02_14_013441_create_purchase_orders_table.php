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

            // Vendor information
            $table->string('vendor_id')->nullable();
            $table->string('vendor_direccion')->nullable();
            $table->string('vendor_codigo_postal')->nullable();
            $table->string('vendor_pais')->nullable();
            $table->string('vendor_estado')->nullable();
            $table->string('vendor_telefono')->nullable();

            // Ship to information
            $table->string('ship_to_direccion')->nullable();
            $table->string('ship_to_codigo_postal')->nullable();
            $table->string('ship_to_pais')->nullable();
            $table->string('ship_to_estado')->nullable();
            $table->string('ship_to_telefono')->nullable();

            // Bill to information
            $table->string('bill_to_direccion')->nullable();
            $table->string('bill_to_codigo_postal')->nullable();
            $table->string('bill_to_pais')->nullable();
            $table->string('bill_to_estado')->nullable();
            $table->string('bill_to_telefono')->nullable();

            // Order details
            $table->date('order_date')->nullable();
            $table->string('currency')->nullable();
            $table->string('incoterms')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('order_place')->nullable();
            $table->string('email_agent')->nullable();

            // Totals
            $table->decimal('net_total', 12, 2)->nullable();
            $table->decimal('additional_cost', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();


            // Dimensiones
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('volume', 10, 3)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('weight_lb', 8, 2)->nullable();

            // Fechas
            $table->datetime('date_required_in_destination')->nullable();
            $table->datetime('date_planned_pickup')->nullable();
            $table->datetime('date_actual_pickup')->nullable();
            $table->datetime('date_estimated_hub_arrival')->nullable();
            $table->datetime('date_actual_hub_arrival')->nullable();
            $table->datetime('date_etd')->nullable();
            $table->datetime('date_atd')->nullable();
            $table->datetime('date_eta')->nullable();
            $table->datetime('date_ata')->nullable();
            $table->datetime('date_consolidation')->nullable();
            $table->datetime('release_date')->nullable();

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
