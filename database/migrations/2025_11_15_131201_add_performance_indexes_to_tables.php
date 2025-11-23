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
        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            // Composite index for user_id + status (dashboard queries)
            $table->index(['user_id', 'status'], 'orders_user_status_idx');
            
            // Index for status (admin stats, revenue calculation)
            $table->index('status', 'orders_status_idx');
            
            // Index for product_id and service_id (seller queries)
            $table->index('product_id', 'orders_product_idx');
            $table->index('service_id', 'orders_service_idx');
            
            // Index for created_at (ordering/sorting)
            $table->index('created_at', 'orders_created_at_idx');
        });

        // Products table indexes
        Schema::table('products', function (Blueprint $table) {
            // Composite index for user_id + is_active (seller dashboard, listing)
            $table->index(['user_id', 'is_active'], 'products_user_active_idx');
            
            // Index for is_active (public listing)
            $table->index('is_active', 'products_active_idx');
            
            // Index for category (filtering)
            $table->index('category', 'products_category_idx');
        });

        // Services table indexes
        Schema::table('services', function (Blueprint $table) {
            // Composite index for user_id + status
            $table->index(['user_id', 'status'], 'services_user_status_idx');
            
            // Index for status (public listing)
            $table->index('status', 'services_status_idx');
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            // Index for status (admin dashboard)
            $table->index('status', 'payments_status_idx');
            
            // Index for order_id (joins)
            $table->index('order_id', 'payments_order_idx');
        });

        // Wallet transactions indexes
        Schema::table('wallet_transactions', function (Blueprint $table) {
            // Composite index for user_id + type
            $table->index(['user_id', 'type'], 'wallet_user_type_idx');
            
            // Composite index for type + status (admin approval queue)
            $table->index(['type', 'status'], 'wallet_type_status_idx');
            
            // Index for created_at (ordering)
            $table->index('created_at', 'wallet_created_at_idx');
        });

        // Notifications table indexes
        Schema::table('notifications', function (Blueprint $table) {
            // Composite index for user_id + is_read
            $table->index(['user_id', 'is_read'], 'notifications_user_read_idx');
            
            // Index for created_at (ordering)
            $table->index('created_at', 'notifications_created_at_idx');
        });

        // Ratings table indexes
        Schema::table('ratings', function (Blueprint $table) {
            // Index for product_id and service_id (average rating calculation)
            $table->index('product_id', 'ratings_product_idx');
            $table->index('service_id', 'ratings_service_idx');
            
            // Index for user_id (user's ratings)
            $table->index('user_id', 'ratings_user_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_status_idx');
            $table->dropIndex('orders_status_idx');
            $table->dropIndex('orders_product_idx');
            $table->dropIndex('orders_service_idx');
            $table->dropIndex('orders_created_at_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_user_active_idx');
            $table->dropIndex('products_active_idx');
            $table->dropIndex('products_category_idx');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('services_user_status_idx');
            $table->dropIndex('services_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_status_idx');
            $table->dropIndex('payments_order_idx');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('wallet_user_type_idx');
            $table->dropIndex('wallet_type_status_idx');
            $table->dropIndex('wallet_created_at_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_read_idx');
            $table->dropIndex('notifications_created_at_idx');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex('ratings_product_idx');
            $table->dropIndex('ratings_service_idx');
            $table->dropIndex('ratings_user_idx');
        });
    }
};
