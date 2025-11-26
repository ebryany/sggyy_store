<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Banner Model
 * 
 * General-purpose banner management for marketing and promotions
 * Independent from products/services (unlike FeaturedItem)
 * 
 * Positions:
 * - hero: Main hero banners (top of pages)
 * - sidebar: Sidebar banners/ads
 * - footer: Footer promotional banners
 * - popup: Popup/modal banners
 */
class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'image_path',
        'link_url',
        'link_text',
        'position',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
        'click_count',
        'view_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'click_count' => 'integer',
        'view_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($banner) {
            if (empty($banner->uuid)) {
                $banner->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        if (request()->is('api/*')) {
            return 'uuid';
        }
        return 'id';
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope for active banners
     * 
     * Filters banners that are:
     * - is_active = true
     * - start_date is null or in the past
     * - end_date is null or in the future
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope for ordering banners
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Scope by position
     */
    public function scopePosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope for hero banners
     */
    public function scopeHero($query)
    {
        return $query->where('position', 'hero');
    }

    /**
     * Scope for sidebar banners
     */
    public function scopeSidebar($query)
    {
        return $query->where('position', 'sidebar');
    }

    /**
     * Scope for footer banners
     */
    public function scopeFooter($query)
    {
        return $query->where('position', 'footer');
    }

    /**
     * Scope for popup banners
     */
    public function scopePopup($query)
    {
        return $query->where('position', 'popup');
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if banner is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get full image URL
     */
    public function getImageUrl(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // If it's already a full URL
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // Get from storage
        $disk = config('filesystems.default');
        return \Storage::disk($disk)->url($this->image_path);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    /**
     * Increment click count
     */
    public function incrementClicks(): void
    {
        $this->increment('click_count');
    }

    /**
     * Get click-through rate (CTR)
     */
    public function getCtrAttribute(): float
    {
        if ($this->view_count === 0) {
            return 0.0;
        }

        return round(($this->click_count / $this->view_count) * 100, 2);
    }

    /**
     * Check if banner has expired
     */
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Check if banner is scheduled (not started yet)
     */
    public function isScheduled(): bool
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isScheduled()) {
            return 'scheduled';
        }

        return 'active';
    }

    /**
     * Get available positions
     */
    public static function getAvailablePositions(): array
    {
        return [
            'hero' => 'Hero Banner (Top of page)',
            'sidebar' => 'Sidebar Banner (Right/Left sidebar)',
            'footer' => 'Footer Banner (Bottom of page)',
            'popup' => 'Popup Banner (Modal/Overlay)',
        ];
    }
}

