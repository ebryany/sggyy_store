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
        Schema::table('products', function (Blueprint $table) {
            // Views tracking
            $table->unsignedBigInteger('views_count')->default(0)->after('sold_count');
            
            // Warranty in days (e.g., 7 days guarantee)
            $table->unsignedInteger('warranty_days')->default(7)->after('views_count');
            
            // Estimated delivery time in days (0 = instant for digital products)
            $table->unsignedInteger('delivery_days')->default(0)->after('warranty_days');
            
            // Add index for views_count for sorting/filtering
            $table->index('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['views_count']);
            $table->dropColumn(['views_count', 'warranty_days', 'delivery_days']);
        });
    }
};
