<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // Use StorageService to get correct URL based on configured disk
        $storageService = app(\App\Services\StorageService::class);
        return $storageService->url($this->image_path);
    }
}

        // If already a full URL, return as is
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // Use StorageService to get correct URL based on configured disk
        $storageService = app(\App\Services\StorageService::class);
        return $storageService->url($this->image_path);
    }
}
