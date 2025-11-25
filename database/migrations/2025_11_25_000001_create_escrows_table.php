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
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            
            // Escrow amounts
            $table->decimal('amount', 15, 2)->comment('Total amount held in escrow');
            $table->decimal('platform_fee', 15, 2)->comment('Platform commission');
            $table->decimal('seller_earning', 15, 2)->comment('Amount for seller after commission');
            
            // Status tracking
            $table->enum('status', ['holding', 'released', 'refunded', 'disputed'])->default('holding');
            
            // Hold period
            $table->timestamp('hold_until')->nullable()->comment('Auto-release timestamp');
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('release_type', ['early', 'auto', 'manual'])->nullable()->comment('How escrow was released');
            
            // Dispute tracking
            $table->boolean('is_disputed')->default(false);
            $table->timestamp('disputed_at')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->foreignId('disputed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Xendit tracking (for audit)
            $table->string('xendit_invoice_id')->nullable()->unique();
            $table->string('xendit_external_id')->nullable()->unique();
            
            // Audit trail
            $table->timestamps();
            
            // Indexes for performance
            $table->index('status');
            $table->index('hold_until');
            $table->index('is_disputed');
            $table->index('xendit_invoice_id');
            $table->index('xendit_external_id');
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escrows');
    }
};

