<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerServiceController extends BaseApiController
{
    /**
     * Get seller's services
     * 
     * GET /api/v1/seller/services
     */
    public function index(Request $request)
    {
        $query = Service::where('user_id', auth()->id())
            ->with(['ratings']);

        // Apply filters
        $searchFields = ['title', 'description'];
        $query = $this->applyFilters($query, $request, $searchFields);

        $services = $this->paginate($query, $request);

        return $this->successCollection(
            ServiceResource::collection($services)
        );
    }

    /**
     * Create new service
     * 
     * POST /api/v1/seller/services
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:10240'],
            'status' => ['in:active,inactive'],
        ]);

        try {
            DB::beginTransaction();

            // Generate unique slug
            $slug = Service::generateSlug($validated['title']);

            // Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services/images', 'public');
            }

            // Create service
            $service = Service::create([
                'user_id' => auth()->id(),
                'slug' => $slug,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'duration_hours' => $validated['duration_hours'],
                'image' => $imagePath,
                'status' => $validated['status'] ?? 'active',
            ]);

            DB::commit();

            return $this->created(
                new ServiceResource($service),
                'Service created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'SERVICE_ERROR',
                400
            );
        }
    }

    /**
     * Update service
     * 
     * PATCH /api/v1/seller/services/{service_uuid}
     */
    public function update(Request $request, Service $service)
    {
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('update', $service);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('You do not have access to this service');
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'duration_hours' => ['sometimes', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:10240'],
            'status' => ['in:active,inactive'],
        ]);

        try {
            DB::beginTransaction();

            // Update slug if title changed
            if (isset($validated['title']) && $validated['title'] !== $service->title) {
                $validated['slug'] = Service::generateSlug($validated['title'], $service->id);
            }

            // Upload new image if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($service->image) {
                    \Storage::disk('public')->delete($service->image);
                }
                $validated['image'] = $request->file('image')->store('services/images', 'public');
            }

            $service->update($validated);

            DB::commit();

            return $this->success(
                new ServiceResource($service->fresh()),
                'Service updated successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'SERVICE_ERROR',
                400
            );
        }
    }

    /**
     * Delete service
     * 
     * DELETE /api/v1/seller/services/{service_uuid}
     */
    public function destroy(Service $service)
    {
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('update', $service);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('You do not have access to this service');
        }

        // Check if service has orders
        if ($service->orders()->count() > 0) {
            return $this->error(
                'Cannot delete service with existing orders',
                [],
                'SERVICE_HAS_ORDERS',
                400
            );
        }

        try {
            // Delete image
            if ($service->image) {
                \Storage::disk('public')->delete($service->image);
            }

            $service->delete();

            return $this->success(null, 'Service deleted successfully');

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

