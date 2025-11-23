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
        Schema::table('orders', function (Blueprint $table) {
            // Seller accept timestamp
            $table->timestamp('accepted_at')->nullable()->after('deadline_at');
            
            // Deliverable upload timestamp
            $table->timestamp('delivered_at')->nullable()->after('accepted_at');
            
            // Auto-complete deadline (for buyer confirmation window)
            $table->timestamp('auto_complete_at')->nullable()->after('delivered_at');
            
            // Cancel reason for audit
            $table->text('cancel_reason')->nullable()->after('revision_notes');
            
            // Maximum revisions allowed (default 2)
            $table->integer('max_revisions')->default(2)->after('revision_count');
            
            // Payment expiry for transfer/QRIS (timeout)
            $table->timestamp('payment_expires_at')->nullable()->after('deadline_at');
            
            // Add indexes
            $table->index('accepted_at', 'orders_accepted_at_idx');
            $table->index('delivered_at', 'orders_delivered_at_idx');
            $table->index('auto_complete_at', 'orders_auto_complete_at_idx');
            $table->index('payment_expires_at', 'orders_payment_expires_at_idx');
        });
        
        // Update status enum to include new statuses
        // Note: Laravel doesn't support ALTER ENUM directly, so we'll use DB::statement
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE orders 
            MODIFY COLUMN status ENUM(
                'pending', 
                'paid', 
                'accepted', 
                'processing', 
                'waiting_confirmation', 
                'completed', 
                'needs_revision', 
                'cancelled', 
                'disputed'
            ) DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_accepted_at_idx');
            $table->dropIndex('orders_delivered_at_idx');
            $table->dropIndex('orders_auto_complete_at_idx');
            $table->dropIndex('orders_payment_expires_at_idx');
            $table->dropColumn([
                'accepted_at',
                'delivered_at',
                'auto_complete_at',
                'cancel_reason',
                'max_revisions',
                'payment_expires_at'
            ]);
        });
        
        // Revert status enum
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE orders 
            MODIFY COLUMN status ENUM(
                'pending', 
                'paid', 
                'processing', 
                'completed', 
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }
};
