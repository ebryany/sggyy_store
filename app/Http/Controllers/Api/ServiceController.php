<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\RatingResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends BaseApiController
{
    /**
     * Get all services with filters
     * 
     * GET /api/v1/services
     * 
     * Query params:
     * - q: search term
     * - category: filter by category (if applicable)
     * - price_min: minimum price
     * - price_max: maximum price
     * - sort: latest|popular|price_asc|price_desc
     * - page: page number
     * - per_page: items per page (max 100)
     */
    public function index(Request $request)
    {
        $query = Service::query()
            ->where('status', 'active')
            ->with(['user', 'ratings']);

        // Apply filters
        $searchFields = ['title', 'description'];
        $query = $this->applyFilters($query, $request, $searchFields);

        // Paginate
        $services = $this->paginate($query, $request);

        return $this->successCollection(
            ServiceResource::collection($services)
        );
    }

    /**
     * Get single service by slug
     * 
     * GET /api/v1/services/{service_slug}
     */
    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', 'active')
            ->with(['user', 'ratings'])
            ->first();

        if (!$service) {
            return $this->notFound('Service');
        }

        return $this->success(
            new ServiceResource($service)
        );
    }

    /**
     * Get service reviews
     * 
     * GET /api/v1/services/{service_slug}/reviews
     */
    public function reviews(string $slug, Request $request)
    {
        $service = Service::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$service) {
            return $this->notFound('Service');
        }

        $query = $service->ratings()
            ->with('user')
            ->orderBy('created_at', 'desc');

        $ratings = $this->paginate($query, $request);

        return $this->successCollection(
            RatingResource::collection($ratings)
        );
    }
}

