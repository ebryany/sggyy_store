<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\JokiService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(
        private JokiService $jokiService,
        private SettingsService $settingsService
    ) {}

    public function index(Request $request): View
    {
        $query = Service::with(['user', 'ratings'])
            ->where('status', 'active');

        // ✅ PHASE 1 FIX: Sanitize search parameter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search); // Remove HTML tags
            $search = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $search); // Only allow letters, numbers, spaces, hyphens, underscores
            $search = mb_substr($search, 0, 100); // Limit length to 100 characters
            
            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // Category filter - Services don't have category column, so this filter is disabled
        // if ($request->filled('category')) {
        //     $category = trim($request->category);
        //     $category = strip_tags($category);
        //     
        //     // Get valid categories from database
        //     $validCategories = Service::where('status', 'active')
        //         ->distinct()
        //         ->pluck('category')
        //         ->toArray();
        //     
        //     if (in_array($category, $validCategories)) {
        //         $query->where('category', $category);
        //     }
        // }

        // Price range filter
        if ($request->filled('min_price')) {
            $minPrice = filter_var($request->min_price, FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false && $minPrice >= 0) {
                $query->where('price', '>=', $minPrice);
            }
        }

        if ($request->filled('max_price')) {
            $maxPrice = filter_var($request->max_price, FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false && $maxPrice >= 0) {
                $query->where('price', '<=', $maxPrice);
            }
        }

        // Rating filter (minimum rating)
        if ($request->filled('rating')) {
            $rating = filter_var($request->rating, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ]);
            if ($rating !== false) {
                $query->whereHas('ratings', function ($q) use ($rating) {
                    $q->selectRaw('service_id, AVG(rating) as avg_rating')
                      ->groupBy('service_id')
                      ->havingRaw('AVG(rating) >= ?', [$rating]);
                });
            }
        }

        // Seller filter
        if ($request->filled('seller')) {
            $seller = trim($request->seller);
            $seller = strip_tags($seller);
            // Support both ID and slug
            if (is_numeric($seller)) {
                $query->where('user_id', $seller);
            } else {
                $query->whereHas('user', function ($q) use ($seller) {
                    $q->where('store_slug', $seller)
                      ->orWhere('name', 'like', '%' . $seller . '%');
                });
            }
        }

        // Duration filter
        if ($request->filled('duration_min')) {
            $durationMin = filter_var($request->duration_min, FILTER_VALIDATE_INT);
            if ($durationMin !== false && $durationMin >= 0) {
                $query->where('duration_hours', '>=', $durationMin);
            }
        }

        if ($request->filled('duration_max')) {
            $durationMax = filter_var($request->duration_max, FILTER_VALIDATE_INT);
            if ($durationMax !== false && $durationMax >= 0) {
                $query->where('duration_hours', '<=', $durationMax);
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'rating', 'popular', 'completed'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')
                      ->orderBy('ratings_avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('completed_count', 'desc');
                break;
            case 'completed':
                $query->orderBy('completed_count', 'desc');
                break;
        }

        // Per page (limit items per page)
        $perPage = $request->get('per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 12;
        }

        $services = $query->paginate((int)$perPage)->withQueryString();

        // Services don't have category column, return empty array
        $categories = collect([]);

        return view('services.index', compact('services', 'categories'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('create', Service::class);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \App\Services\SecurityLogger::logAuthorizationFailure('Service create');
            throw $e;
        }
        
        try {
            $service = $this->jokiService->create(
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('services.show', $service)
                ->with('success', 'Jasa berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Request $request, $service): View
    {
        // Support both slug and ID for backward compatibility
        if ($service instanceof Service) {
            // Route model binding found by slug
            $serviceModel = $service;
        } else {
            // Try to find by slug or ID
            $serviceModel = Service::findBySlugOrId($service);
            
            if (!$serviceModel) {
                abort(404, 'Jasa tidak ditemukan');
            }
        }
        
        $serviceModel->load(['user', 'ratings.user']);
        
        // Get bank account info for payment methods
        $bankAccountInfo = $this->settingsService->getBankAccountInfo();
        $featureFlags = $this->settingsService->getFeatureFlags();

        return view('services.show', [
            'service' => $serviceModel,
            'bankAccountInfo' => $bankAccountInfo,
            'featureFlags' => $featureFlags
        ]);
    }

    public function edit($service): View
    {
        // Support both slug and ID
        $serviceModel = $service instanceof Service 
            ? $service 
            : Service::findBySlugOrId($service);
        
        if (!$serviceModel) {
            abort(404, 'Jasa tidak ditemukan');
        }
        
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('update', $serviceModel);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \App\Services\SecurityLogger::logAuthorizationFailure('Service update', [
                'service_id' => $serviceModel->id,
            ]);
            throw $e;
        }

        return view('services.edit', ['service' => $serviceModel]);
    }

    public function update(UpdateServiceRequest $request, $service): RedirectResponse
    {
        // Support both slug and ID
        $serviceModel = $service instanceof Service 
            ? $service 
            : Service::findBySlugOrId($service);
        
        if (!$serviceModel) {
            abort(404, 'Jasa tidak ditemukan');
        }
        
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('update', $serviceModel);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \App\Services\SecurityLogger::logAuthorizationFailure('Service update', [
                'service_id' => $serviceModel->id,
            ]);
            throw $e;
        }

        try {
            $this->jokiService->update(
                $serviceModel,
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('services.show', $serviceModel)
                ->with('success', 'Jasa berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($service): RedirectResponse
    {
        // Support both slug and ID
        $serviceModel = $service instanceof Service 
            ? $service 
            : Service::findBySlugOrId($service);
        
        if (!$serviceModel) {
            abort(404, 'Jasa tidak ditemukan');
        }
        
        // 🔒 SECURITY: Use Policy for authorization
        try {
            $this->authorize('delete', $serviceModel);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \App\Services\SecurityLogger::logAuthorizationFailure('Service delete', [
                'service_id' => $serviceModel->id,
            ]);
            throw $e;
        }

        try {
            $this->jokiService->delete($serviceModel);

            return redirect()
                ->route('services.index')
                ->with('success', 'Jasa berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}