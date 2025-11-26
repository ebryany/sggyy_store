<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\RatingResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{
    /**
     * Get all products with filters
     * 
     * GET /api/v1/products
     * 
     * Query params:
     * - q: search term
     * - category: filter by category
     * - tag: filter by tag
     * - price_min: minimum price
     * - price_max: maximum price
     * - sort: latest|popular|price_asc|price_desc
     * - page: page number
     * - per_page: items per page (max 100)
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->where('is_draft', false)
            ->with(['user', 'images', 'tags', 'ratings']);

        // Apply filters
        $searchFields = ['title', 'description', 'short_description'];
        $query = $this->applyFilters($query, $request, $searchFields);

        // Paginate
        $products = $this->paginate($query, $request);

        return $this->successCollection(
            ProductResource::collection($products)
        );
    }

    /**
     * Get single product by slug
     * 
     * GET /api/v1/products/{product_slug}
     */
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_draft', false)
            ->with(['user', 'images', 'tags', 'features', 'ratings'])
            ->first();

        if (!$product) {
            return $this->notFound('Product');
        }

        // Increment views
        $product->incrementViews();

        return $this->success(
            new ProductResource($product)
        );
    }

    /**
     * Get product reviews
     * 
     * GET /api/v1/products/{product_slug}/reviews
     */
    public function reviews(string $slug, Request $request)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_draft', false)
            ->first();

        if (!$product) {
            return $this->notFound('Product');
        }

        $query = $product->ratings()
            ->with('user')
            ->orderBy('created_at', 'desc');

        $ratings = $this->paginate($query, $request);

        return $this->successCollection(
            RatingResource::collection($ratings)
        );
    }
}

