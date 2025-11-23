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
        Schema::create('featured_items', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('product'); // 'product' or 'service'
            $table->unsignedBigInteger('item_id'); // product_id or service_id
            $table->string('title')->nullable(); // Custom title/banner text
            $table->text('description')->nullable(); // Custom description
            $table->string('header_bg_color')->default('#8B4513'); // Dark brown header
            $table->string('banner_bg_color')->nullable(); // Banner background color
            $table->string('main_bg_color')->nullable(); // Main content background
            $table->string('main_text_color')->default('#FFFFFF'); // Main text color
            $table->string('accent_color')->nullable(); // Accent color (yellow, etc)
            $table->json('features')->nullable(); // Features list as JSON
            $table->string('footer_text')->nullable(); // Footer text (e.g., "DR+ DA+ PA+")
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'item_id']);
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_items');
    }
};
