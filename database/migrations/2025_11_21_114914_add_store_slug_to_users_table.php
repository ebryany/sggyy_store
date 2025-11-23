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
        Schema::table('users', function (Blueprint $table) {
            // Add store_slug for SEO-friendly store URLs
            $table->string('store_slug')->nullable()->unique()->after('store_name');
            
            // Add index for faster lookups
            $table->index('store_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['store_slug']);
            $table->dropColumn('store_slug');
        });
    }
};
