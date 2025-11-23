<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 🔒 CRITICAL SECURITY: Add download tracking fields to orders
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Download limit per order (default: 5 downloads)
            $table->integer('download_limit')->default(5)->after('deliverable_path');
            
            // Download count (incremented on each download)
            $table->integer('download_count')->default(0)->after('download_limit');
            
            // Expiry date (default: 30 days after completed_at)
            $table->timestamp('download_expires_at')->nullable()->after('download_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['download_limit', 'download_count', 'download_expires_at']);
        });
    }
};
