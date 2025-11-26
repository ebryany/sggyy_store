<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Banners table for general-purpose marketing banners
     * Independent from products/services (unlike featured_items)
     * 
     * Positions:
     * - hero: Main hero banners (top of pages)
     * - sidebar: Sidebar banners/ads
     * - footer: Footer promotional banners
     * - popup: Popup/modal banners
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Banner Content
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path'); // Main banner image
            
            // Link/CTA
            $table->string('link_url', 500)->nullable(); // External or internal link
            $table->string('link_text')->nullable(); // CTA button text
            
            // Placement & Display
            $table->enum('position', ['hero', 'sidebar', 'footer', 'popup'])->default('hero');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Scheduling
            $table->dateTime('start_date')->nullable(); // When to start showing
            $table->dateTime('end_date')->nullable(); // When to stop showing
            
            // Analytics
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('uuid');
            $table->index('position');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index(['start_date', 'end_date']);
            $table->index(['position', 'is_active', 'sort_order'], 'banner_display_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
