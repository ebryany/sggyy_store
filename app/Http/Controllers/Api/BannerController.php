<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\BannerResource;
use App\Models\FeaturedItem;

class BannerController extends BaseApiController
{
    /**
     * Get active banners
     * 
     * GET /api/v1/banners
     */
    public function index()
    {
        $banners = FeaturedItem::active()
            ->with(['product.user', 'service.user'])
            ->ordered()
            ->get();

        return $this->success(
            BannerResource::collection($banners)
        );
    }
}

