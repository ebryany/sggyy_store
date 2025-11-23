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
            // High Priority Fields
            $table->string('sku')->nullable()->unique()->after('slug');
            $table->text('short_description')->nullable()->after('description');
            $table->string('demo_link')->nullable()->after('file_path');
            $table->string('product_type')->nullable()->after('category'); // Template, Plugin, Script, etc.
            
            // Medium Priority Fields
            $table->decimal('sale_price', 15, 2)->nullable()->after('price');
            $table->string('video_preview')->nullable()->after('demo_link'); // YouTube/Vimeo URL
            $table->text('system_requirements')->nullable()->after('description');
            $table->string('license_type')->nullable()->after('product_type'); // GPL, Commercial, Personal
            $table->text('support_info')->nullable()->after('license_type');
            $table->string('version')->nullable()->after('product_type');
            $table->string('file_size')->nullable()->after('file_path');
            
            // SEO Fields
            $table->string('meta_title')->nullable()->after('title');
            $table->text('meta_description')->nullable()->after('meta_title');
            
            // Low Priority Fields
            $table->boolean('is_draft')->default(false)->after('is_active');
            $table->timestamp('published_at')->nullable()->after('is_draft');
            $table->integer('download_limit')->nullable()->after('file_path'); // null = unlimited
            
            // Indexes
            $table->index('sku');
            $table->index('product_type');
            $table->index('is_draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['sku']);
            $table->dropIndex(['product_type']);
            $table->dropIndex(['is_draft']);
            
            $table->dropColumn([
                'sku',
                'short_description',
                'demo_link',
                'product_type',
                'sale_price',
                'video_preview',
                'system_requirements',
                'license_type',
                'support_info',
                'version',
                'file_size',
                'meta_title',
                'meta_description',
                'is_draft',
                'published_at',
                'download_limit',
            ]);
        });
    }
};
