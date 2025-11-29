<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add Veripay fields to wallet_transactions table
     */
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            // Add veripay_qris to payment_method enum
            DB::statement("ALTER TABLE `wallet_transactions` MODIFY COLUMN `payment_method` ENUM('bank_transfer', 'qris', 'manual', 'wallet', 'veripay_qris') NULL");
            
            // Add Veripay metadata fields
            $table->string('veripay_transaction_ref')->nullable()->after('payment_method')->comment('Veripay transaction reference');
            $table->string('veripay_payment_url')->nullable()->after('veripay_transaction_ref')->comment('Veripay payment URL');
            $table->json('veripay_metadata')->nullable()->after('veripay_payment_url')->comment('Veripay webhook payload');
            
            // Index for faster lookup
            $table->index('veripay_transaction_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex(['veripay_transaction_ref']);
            $table->dropColumn([
                'veripay_transaction_ref',
                'veripay_payment_url',
                'veripay_metadata',
            ]);
            
            // Revert payment_method enum
            DB::statement("ALTER TABLE `wallet_transactions` MODIFY COLUMN `payment_method` ENUM('bank_transfer', 'qris', 'manual', 'wallet') NULL");
        });
    }
};


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex(['veripay_transaction_ref']);
            $table->dropColumn([
                'veripay_transaction_ref',
                'veripay_payment_url',
                'veripay_metadata',
            ]);
            
            // Revert payment_method enum
            DB::statement("ALTER TABLE `wallet_transactions` MODIFY COLUMN `payment_method` ENUM('bank_transfer', 'qris', 'manual', 'wallet') NULL");
        });
    }
};
