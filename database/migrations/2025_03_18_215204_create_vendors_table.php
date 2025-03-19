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
        Schema::create('vendors', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Foreign keys
            $table->foreignId('company_id')->constrained()->onDelete('cascade');

            // Basic vendor information
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();

            // Addresses and contact information
            $table->string('vendor_direccion')->nullable();
            $table->string('vendor_codigo_postal')->nullable();
            $table->string('vendor_pais')->nullable();
            $table->string('vendor_estado')->nullable();
            $table->string('vendor_telefono')->nullable();

            // Status and notes
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
