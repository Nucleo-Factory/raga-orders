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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('confirmation_hash', 64)->nullable()->index();
            $table->timestamp('hash_expires_at')->nullable();
            $table->boolean('confirmation_email_sent')->default(false);
            $table->timestamp('confirmation_email_sent_at')->nullable();
            $table->date('update_date_po')->nullable();
            $table->boolean('confirm_update_date_po')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndex(['confirmation_hash']);
            $table->dropColumn([
                'confirmation_hash',
                'hash_expires_at',
                'confirmation_email_sent',
                'confirmation_email_sent_at',
                'update_date_po',
                'confirm_update_date_po'
            ]);
        });
    }
};
