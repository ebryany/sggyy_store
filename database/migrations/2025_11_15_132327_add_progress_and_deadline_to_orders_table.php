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
            // Progress percentage (0-100) for tracking order completion
            $table->integer('progress')->default(0)->after('status');
            
            // Deadline for service completion (especially for joki tugas)
            $table->timestamp('deadline_at')->nullable()->after('progress');
            
            // Completion timestamp
            $table->timestamp('completed_at')->nullable()->after('deadline_at');
            
            // Add index for deadline queries
            $table->index('deadline_at', 'orders_deadline_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_deadline_idx');
            $table->dropColumn(['progress', 'deadline_at', 'completed_at']);
        });
    }
};
