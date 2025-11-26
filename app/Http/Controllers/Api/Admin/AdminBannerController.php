<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends BaseApiController
{
    /**
     * Get all banners
     * 
     * GET /api/v1/admin/banners
     * 
     * Query params:
     * - position: filter by position (hero|sidebar|footer|popup)
     * - is_active: filter by active status
     * - q: search by title, description
     * - sort: latest|oldest|sort_order
     * - page, per_page
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Apply filters
        $query = $this->applyFilters($query, $request, ['title', 'description']);

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->input('position'));
        }

        // Filter by is_active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Custom sorting
        if ($request->input('sort') === 'sort_order') {
            $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        }

        // Paginate
        $banners = $this->paginate($query, $request);

        return $this->successCollection(
            BannerResource::collection($banners)
        );
    }

    /**
     * Get single banner
     * 
     * GET /api/v1/admin/banners/{banner_uuid}
     */
    public function show(Banner $banner)
    {
        return $this->success(
            new BannerResource($banner)
        );
    }

    /**
     * Create new banner
     * 
     * POST /api/v1/admin/banners
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB
            'link_url' => ['nullable', 'url', 'max:500'],
            'link_text' => ['nullable', 'string', 'max:100'],
            'position' => ['required', 'in:hero,sidebar,footer,popup'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        try {
            DB::beginTransaction();

            // Upload image
            $imagePath = $request->file('image')->store('banners', 'public');

            // Create banner
            $banner = Banner::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'link_url' => $validated['link_url'] ?? null,
                'link_text' => $validated['link_text'] ?? null,
                'position' => $validated['position'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $validated['is_active'] ?? true,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
            ]);

            DB::commit();

            return $this->created(
                new BannerResource($banner),
                'Banner created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'BANNER_ERROR',
                400
            );
        }
    }

    /**
     * Update banner
     * 
     * PATCH /api/v1/admin/banners/{banner_uuid}
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'link_text' => ['nullable', 'string', 'max:100'],
            'position' => ['sometimes', 'in:hero,sidebar,footer,popup'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        try {
            DB::beginTransaction();

            // Upload new image if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                $validated['image_path'] = $request->file('image')->store('banners', 'public');
            }

            $banner->update($validated);

            DB::commit();

            return $this->success(
                new BannerResource($banner->fresh()),
                'Banner updated successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'BANNER_ERROR',
                400
            );
        }
    }

    /**
     * Delete banner
     * 
     * DELETE /api/v1/admin/banners/{banner_uuid}
     */
    public function destroy(Banner $banner)
    {
        try {
            DB::beginTransaction();

            // Delete image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $banner->delete();

            DB::commit();

            return $this->success(null, 'Banner deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'DELETE_ERROR',
                400
            );
        }
    }

    /**
     * Toggle banner active status
     * 
     * PATCH /api/v1/admin/banners/{banner_uuid}/toggle
     */
    public function toggle(Banner $banner)
    {
        try {
            $banner->update(['is_active' => !$banner->is_active]);

            return $this->success(
                new BannerResource($banner->fresh()),
                'Banner status toggled successfully'
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'TOGGLE_ERROR',
                400
            );
        }
    }

    /**
     * Get banner statistics
     * 
     * GET /api/v1/admin/banners/statistics
     */
    public function statistics()
    {
        $stats = [
            'total_banners' => Banner::count(),
            'active_banners' => Banner::where('is_active', true)->count(),
            'expired_banners' => Banner::where('end_date', '<', now())->count(),
            'scheduled_banners' => Banner::where('start_date', '>', now())->count(),
            'by_position' => [
                'hero' => Banner::where('position', 'hero')->count(),
                'sidebar' => Banner::where('position', 'sidebar')->count(),
                'footer' => Banner::where('position', 'footer')->count(),
                'popup' => Banner::where('position', 'popup')->count(),
            ],
            'total_views' => Banner::sum('view_count'),
            'total_clicks' => Banner::sum('click_count'),
            'average_ctr' => Banner::where('view_count', '>', 0)
                ->get()
                ->avg(function ($banner) {
                    return $banner->ctr;
                }) ?? 0,
        ];

        return $this->success($stats);
    }

    /**
     * Track banner view (for analytics)
     * 
     * POST /api/v1/admin/banners/{banner_uuid}/track-view
     */
    public function trackView(Banner $banner)
    {
        try {
            $banner->incrementViews();

            return $this->success([
                'view_count' => $banner->view_count,
            ], 'View tracked');

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'TRACK_ERROR',
                400
            );
        }
    }

    /**
     * Track banner click (for analytics)
     * 
     * POST /api/v1/admin/banners/{banner_uuid}/track-click
     */
    public function trackClick(Banner $banner)
    {
        try {
            $banner->incrementClicks();

            return $this->success([
                'click_count' => $banner->click_count,
                'view_count' => $banner->view_count,
                'ctr' => $banner->ctr,
            ], 'Click tracked');

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'TRACK_ERROR',
                400
            );
        }
    }

    /**
     * Get available positions
     * 
     * GET /api/v1/admin/banners/positions
     */
    public function positions()
    {
        return $this->success([
            'positions' => Banner::getAvailablePositions(),
        ]);
    }
}
