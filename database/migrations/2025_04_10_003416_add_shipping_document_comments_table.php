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
        Schema::table('shipping_document_comments', function (Blueprint $table) {
            $table->string('stage')->default('shipping_document')->after('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_document_comments', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }
};
