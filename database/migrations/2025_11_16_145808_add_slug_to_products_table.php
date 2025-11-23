<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->index('slug');
        });

        // Generate slugs for existing products
        Product::chunk(100, function ($products) {
            foreach ($products as $product) {
                if (empty($product->slug)) {
                    $slug = Str::slug($product->title);
                    $originalSlug = $slug;
                    $counter = 1;
                    
                    // Ensure uniqueness
                    while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $product->update(['slug' => $slug]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
