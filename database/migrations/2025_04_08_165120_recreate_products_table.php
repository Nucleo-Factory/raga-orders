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
        // Primero eliminamos la tabla si existe
        Schema::dropIfExists('products');

        // Luego creamos la nueva tabla con los campos especificados
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
