<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 🔒 SECURITY ENHANCEMENTS:
     * - Add unique constraint to seller_earnings.order_id (prevent duplicate earnings)
     * - Add indexes for faster security checks
     */
    public function up(): void
    {
        // 🔒 CRITICAL: Prevent duplicate seller earnings for same order
        Schema::table('seller_earnings', function (Blueprint $table) {
            // Add unique constraint on order_id
            // This prevents race condition where PaymentVerified event triggers twice
            $table->unique('order_id', 'unique_seller_earning_per_order');
        });

        // Add performance indexes for security-related queries
        Schema::table('orders', function (Blueprint $table) {
            // Index for checking user's completed orders (download authorization)
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            
            // Index for checking seller's orders
            $table->index(['product_id', 'status'], 'idx_orders_product_status');
            $table->index(['service_id', 'status'], 'idx_orders_service_status');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            // Index for faster transaction lookups
            $table->index(['user_id', 'status', 'type'], 'idx_wallet_user_status_type');
        });

        Schema::table('seller_withdrawals', function (Blueprint $table) {
            // Index for withdrawal queries
            $table->index(['seller_id', 'status'], 'idx_withdrawals_seller_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_earnings', function (Blueprint $table) {
            $table->dropUnique('unique_seller_earning_per_order');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_status');
            $table->dropIndex('idx_orders_product_status');
            $table->dropIndex('idx_orders_service_status');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_wallet_user_status_type');
        });

        Schema::table('seller_withdrawals', function (Blueprint $table) {
            $table->dropIndex('idx_withdrawals_seller_status');
        });
    }
};

