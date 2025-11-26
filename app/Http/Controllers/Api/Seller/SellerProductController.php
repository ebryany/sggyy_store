<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerProductController extends BaseApiController
{
    /**
     * Get seller's products
     * 
     * GET /api/v1/seller/products
     */
    public function index(Request $request)
    {
        $query = Product::where('user_id', auth()->id())
            ->with(['images', 'tags', 'features', 'ratings']);

        // Apply filters
        $searchFields = ['title', 'description'];
        $query = $this->applyFilters($query, $request, $searchFields);

        $products = $this->paginate($query, $request);

        return $this->successCollection(
            ProductResource::collection($products)
        );
    }

    /**
     * Create new product
     * 
     * POST /api/v1/seller/products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'product_type' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'max:512000'], // 500MB
            'image' => ['required', 'image', 'max:10240'],
            'is_active' => ['boolean'],
            'is_draft' => ['boolean'],
        ]);

        try {
            DB::beginTransaction();

            // Generate unique slug
            $slug = Product::generateSlug($validated['title']);

            // Upload file
            $filePath = $request->file('file')->store('products/files', 'private');
            $imagePath = $request->file('image')->store('products/images', 'public');

            // Create product
            $product = Product::create([
                'user_id' => auth()->id(),
                'slug' => $slug,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'short_description' => $validated['short_description'] ?? null,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'],
                'category' => $validated['category'],
                'product_type' => $validated['product_type'],
                'file_path' => $filePath,
                'file_size' => $request->file('file')->getSize(),
                'image' => $imagePath,
                'is_active' => $validated['is_active'] ?? true,
                'is_draft' => $validated['is_draft'] ?? false,
                'published_at' => now(),
            ]);

            DB::commit();

            return $this->created(
                new ProductResource($product),
                'Product created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'PRODUCT_ERROR',
                400
            );
        }
    }

    /**
     * Update product
     * 
     * PATCH /api/v1/seller/products/{product_uuid}
     */
    public function update(Request $request, Product $product)
    {
        // Check authorization
        if ($product->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this product');
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'category' => ['sometimes', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'is_draft' => ['boolean'],
        ]);

        try {
            // Update slug if title changed
            if (isset($validated['title']) && $validated['title'] !== $product->title) {
                $validated['slug'] = Product::generateSlug($validated['title'], $product->id);
            }

            $product->update($validated);

            return $this->success(
                new ProductResource($product->fresh()),
                'Product updated successfully'
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'PRODUCT_ERROR',
                400
            );
        }
    }

    /**
     * Delete product
     * 
     * DELETE /api/v1/seller/products/{product_uuid}
     */
    public function destroy(Product $product)
    {
        // Check authorization
        if ($product->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this product');
        }

        try {
            $product->delete();

            return $this->success(null, 'Product deleted successfully');

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'DELETE_ERROR',
                400
            );
        }
    }
}

