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
        Schema::create('ship24_trackers', function (Blueprint $table) {
            $table->id();
            
            // Relación con purchase order
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('cascade');
            
            // Datos del tracking
            $table->string('tracking_number')->index();
            $table->string('ship24_tracker_id')->unique()->nullable();
            
            // Información adicional para Ship24
            $table->string('carrier_code')->nullable();
            $table->string('origin_country', 3)->nullable();
            $table->string('destination_country', 3)->nullable();
            
            // Estado del tracker
            $table->enum('status', ['pending', 'active', 'delivered', 'expired', 'error'])->default('pending');
            
            // Datos de tracking actuales (JSON para flexibilidad)
            $table->json('tracking_data')->nullable();
            $table->string('current_phase')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            
            // Control de actualizaciones
            $table->timestamp('last_ship24_update')->nullable();
            $table->timestamp('last_webhook_received')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para performance
            $table->index(['tracking_number', 'status']);
            $table->index('purchase_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship24_trackers');
    }
};