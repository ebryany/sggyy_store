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
        Schema::table('payments', function (Blueprint $table) {
            // Xendit integration fields
            $table->string('xendit_invoice_id')->nullable()->unique()->after('status');
            $table->string('xendit_external_id')->nullable()->unique()->after('xendit_invoice_id');
            $table->string('xendit_payment_method')->nullable()->after('xendit_external_id')->comment('VA, QRIS, E-WALLET');
            $table->json('xendit_metadata')->nullable()->after('xendit_payment_method')->comment('Store Xendit webhook payload');
            
            // Indexes
            $table->index('xendit_invoice_id');
            $table->index('xendit_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['xendit_invoice_id']);
            $table->dropIndex(['xendit_external_id']);
            $table->dropColumn([
                'xendit_invoice_id',
                'xendit_external_id',
                'xendit_payment_method',
                'xendit_metadata',
            ]);
        });
    }
};

