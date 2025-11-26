<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'current_price' => (float) $this->current_price,
            'is_on_sale' => $this->isOnSale(),
            'discount_percentage' => $this->discount_percentage,
            'stock' => $this->stock,
            'category' => $this->category,
            'product_type' => $this->product_type,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'is_active' => $this->is_active,
            'is_draft' => $this->is_draft,
            'published_at' => $this->published_at?->toIso8601String(),
            'sold_count' => $this->sold_count,
            'views_count' => $this->views_count,
            'warranty_days' => $this->warranty_days,
            'delivery_days' => $this->delivery_days,
            'sku' => $this->sku,
            'demo_link' => $this->demo_link,
            'video_preview' => $this->video_preview,
            'license_type' => $this->license_type,
            'version' => $this->version,
            'download_limit' => $this->download_limit,
            'average_rating' => round($this->averageRating(), 2),
            'total_ratings' => $this->ratings->count(),
            'seller' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'store_slug' => $this->user->store_slug,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'images' => $this->whenLoaded('images', fn() => $this->images->map(fn($img) => [
                'id' => $img->id,
                'url' => asset('storage/' . $img->image_path),
                'sort_order' => $img->sort_order,
            ])),
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),
            'features' => $this->whenLoaded('features', fn() => $this->features->map(fn($f) => [
                'id' => $f->id,
                'title' => $f->title,
                'description' => $f->description,
                'icon' => $f->icon,
            ])),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

