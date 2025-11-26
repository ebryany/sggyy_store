<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock',
        'category',
        'product_type',
        'file_path',
        'file_size',
        'image',
        'is_active',
        'is_draft',
        'published_at',
        'sold_count',
        'views_count',
        'warranty_days',
        'delivery_days',
        'sku',
        'demo_link',
        'video_preview',
        'system_requirements',
        'license_type',
        'support_info',
        'version',
        'download_limit',
        'meta_title',
        'meta_description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // 🔒 CRITICAL: Always generate UUID if not set
            // This ensures UUID is always present even if seeder doesn't provide it
            if (empty($product->uuid)) {
                $product->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        static::saving(function ($product) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            // This catches cases where boot::creating might not fire
            if (empty($product->uuid)) {
                $product->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_draft' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'warranty_days' => 'integer',
            'delivery_days' => 'integer',
            'download_limit' => 'integer',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class)->orderBy('sort_order');
    }
    
    public function downloads()
    {
        return $this->hasMany(ProductDownload::class);
    }

    // Helper methods
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0 && $this->is_active;
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get formatted warranty text
     */
    public function getWarrantyText(): string
    {
        if ($this->warranty_days === 0) {
            return 'Tidak ada garansi';
        }
        return $this->warranty_days . ' Hari';
    }

    /**
     * Get formatted delivery time text
     */
    public function getDeliveryText(): string
    {
        if ($this->delivery_days === 0) {
            return 'Instan';
        }
        return $this->delivery_days . ' Hari';
    }

    /**
     * Get the route key for the model.
     * Use slug for public routes, UUID for seller/admin API routes
     */
    public function getRouteKeyName(): string
    {
        // For API seller/admin routes, use UUID
        if (request()->is('api/*/seller/products/*') || request()->is('api/*/admin/products/*')) {
            return 'uuid';
        }
        // For public routes, use slug
        return 'slug';
    }

    /**
     * Retrieve model by slug or ID
     */
    public static function findBySlugOrId(string $identifier): ?self
    {
        // Try to find by slug first
        $product = self::where('slug', $identifier)->first();
        
        // If not found, try by ID (for backward compatibility)
        if (!$product && is_numeric($identifier)) {
            $product = self::find($identifier);
        }
        
        return $product;
    }

    /**
     * Generate unique slug from title
     */
    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure uniqueness
        while (self::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get current price (sale price if available, otherwise regular price)
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->isOnSale()) {
            return null;
        }
        
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Get primary image (first from gallery or main image)
     */
    public function getPrimaryImageAttribute(): ?string
    {
        $firstImage = $this->images()->first();
        return $firstImage ? $firstImage->image_path : $this->image;
    }

    /**
     * Check if product is published
     */
    public function isPublished(): bool
    {
        return !$this->is_draft && $this->is_active && 
               ($this->published_at === null || $this->published_at <= now());
    }
}





