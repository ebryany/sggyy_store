<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedItem extends Model
{
    protected $fillable = [
        'type',
        'item_id',
        'title',
        'description',
        'header_bg_color',
        'banner_bg_color',
        'main_bg_color',
        'main_text_color',
        'accent_color',
        'features',
        'footer_text',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'item_id');
    }

    public function item()
    {
        if ($this->type === 'product') {
            return $this->belongsTo(Product::class, 'item_id');
        }
        return $this->belongsTo(Service::class, 'item_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function getDisplayTitleAttribute(): string
    {
        return $this->title ?? $this->item->title ?? 'Featured Item';
    }

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at > now()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < now()) {
            return false;
        }

        return true;
    }
}
