<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * Handles both Banner model (new) and FeaturedItem model (legacy)
     */
    public function toArray(Request $request): array
    {
        // Check if this is a Banner model (new)
        if ($this->resource instanceof \App\Models\Banner) {
            return $this->transformBanner();
        }

        // Legacy: FeaturedItem model
        return $this->transformFeaturedItem();
    }

    /**
     * Transform Banner model (new, standalone banners)
     */
    protected function transformBanner(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->getImageUrl(),
            'image_path' => $this->image_path,
            'link' => [
                'url' => $this->link_url,
                'text' => $this->link_text ?? 'Learn More',
            ],
            'position' => $this->position,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'status' => $this->status_text,
            'schedule' => [
                'start_date' => $this->start_date?->toIso8601String(),
                'end_date' => $this->end_date?->toIso8601String(),
                'is_scheduled' => $this->isScheduled(),
                'is_expired' => $this->isExpired(),
            ],
            'analytics' => [
                'view_count' => $this->view_count,
                'click_count' => $this->click_count,
                'ctr' => $this->ctr,
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    /**
     * Transform FeaturedItem model (legacy, product/service promotions)
     */
    protected function transformFeaturedItem(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'title' => $this->display_title,
            'description' => $this->description,
            'features' => $this->features,
            'footer_text' => $this->footer_text,
            'colors' => [
                'header_bg' => $this->header_bg_color,
                'banner_bg' => $this->banner_bg_color,
                'main_bg' => $this->main_bg_color,
                'main_text' => $this->main_text_color,
                'accent' => $this->accent_color,
            ],
            'item' => $this->when($this->type === 'product', fn() => [
                'uuid' => $this->product->uuid,
                'slug' => $this->product->slug,
                'title' => $this->product->title,
                'price' => (float) $this->product->current_price,
            ]) ?: $this->when($this->type === 'service', fn() => [
                'uuid' => $this->service->uuid,
                'slug' => $this->service->slug,
                'title' => $this->service->title,
                'price' => (float) $this->service->price,
            ]),
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
