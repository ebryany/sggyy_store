<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ServiceResource;
use App\Models\FeaturedItem;

class FeaturedController extends BaseApiController
{
    /**
     * Get featured products
     * 
     * GET /api/v1/featured/products
     */
    public function products()
    {
        $featured = FeaturedItem::active()
            ->where('type', 'product')
            ->with('product.user', 'product.images', 'product.ratings')
            ->ordered()
            ->get()
            ->pluck('product')
            ->filter(); // Remove nulls

        return $this->success(
            ProductResource::collection($featured)
        );
    }

    /**
     * Get featured services
     * 
     * GET /api/v1/featured/services
     */
    public function services()
    {
        $featured = FeaturedItem::active()
            ->where('type', 'service')
            ->with('service.user', 'service.ratings')
            ->ordered()
            ->get()
            ->pluck('service')
            ->filter(); // Remove nulls

        return $this->success(
            ServiceResource::collection($featured)
        );
    }
}

