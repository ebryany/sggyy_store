<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\StoreFollower;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display the store profile page
     */
    public function show(Request $request, string $slug): View
    {
        // Find store by slug
        $store = User::where('store_slug', $slug)
            ->where(function($query) {
                $query->where('role', 'seller')
                      ->orWhere('role', 'admin');
            })
            ->firstOrFail();

        // Load relationships
        $store->load(['products', 'services', 'followers']);

        // Get active tab (default: products)
        $activeTab = $request->get('tab', 'products');

        // Products query with filters
        $productsQuery = $store->products()->where('is_active', true)->with('ratings');
        
        // Apply product filters
        if ($request->filled('product_search')) {
            $search = strip_tags($request->product_search);
            $productsQuery->where('title', 'like', '%' . $search . '%');
        }
        
        if ($request->filled('product_category')) {
            $category = strip_tags($request->product_category);
            $productsQuery->where('category', $category);
        }
        
        // Price range filter for products
        if ($request->filled('product_min_price')) {
            $minPrice = filter_var($request->product_min_price, FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false && $minPrice >= 0) {
                $productsQuery->where('price', '>=', $minPrice);
            }
        }

        if ($request->filled('product_max_price')) {
            $maxPrice = filter_var($request->product_max_price, FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false && $maxPrice >= 0) {
                $productsQuery->where('price', '<=', $maxPrice);
            }
        }

        // Rating filter for products
        if ($request->filled('product_rating')) {
            $rating = filter_var($request->product_rating, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ]);
            if ($rating !== false) {
                $productsQuery->whereHas('ratings', function ($q) use ($rating) {
                    $q->selectRaw('product_id, AVG(rating) as avg_rating')
                      ->groupBy('product_id')
                      ->havingRaw('AVG(rating) >= ?', [$rating]);
                });
            }
        }

        if ($request->filled('product_sort')) {
            switch ($request->product_sort) {
                case 'newest':
                    $productsQuery->latest();
                    break;
                case 'oldest':
                    $productsQuery->oldest();
                    break;
                case 'price_low':
                    $productsQuery->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $productsQuery->orderBy('sold_count', 'desc');
                    break;
                case 'rating':
                    $productsQuery->withAvg('ratings', 'rating')
                                  ->orderBy('ratings_avg_rating', 'desc');
                    break;
                default:
                    $productsQuery->latest();
            }
        } else {
            $productsQuery->latest();
        }

        // Per page for products
        $productPerPage = $request->get('product_per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$productPerPage, $validPerPage)) {
            $productPerPage = 12;
        }

        $products = $productsQuery->paginate((int)$productPerPage)->withQueryString();

        // Services query with filters
        $servicesQuery = $store->services()->where('is_active', true)->with('ratings');
        
        // Apply service filters
        if ($request->filled('service_search')) {
            $search = strip_tags($request->service_search);
            $servicesQuery->where('title', 'like', '%' . $search . '%');
        }
        
        if ($request->filled('service_category')) {
            $category = strip_tags($request->service_category);
            $servicesQuery->where('category', $category);
        }
        
        // Price range filter for services
        if ($request->filled('service_min_price')) {
            $minPrice = filter_var($request->service_min_price, FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false && $minPrice >= 0) {
                $servicesQuery->where('price', '>=', $minPrice);
            }
        }

        if ($request->filled('service_max_price')) {
            $maxPrice = filter_var($request->service_max_price, FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false && $maxPrice >= 0) {
                $servicesQuery->where('price', '<=', $maxPrice);
            }
        }

        // Rating filter for services
        if ($request->filled('service_rating')) {
            $rating = filter_var($request->service_rating, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ]);
            if ($rating !== false) {
                $servicesQuery->whereHas('ratings', function ($q) use ($rating) {
                    $q->selectRaw('service_id, AVG(rating) as avg_rating')
                      ->groupBy('service_id')
                      ->havingRaw('AVG(rating) >= ?', [$rating]);
                });
            }
        }

        // Duration filter for services
        if ($request->filled('service_duration_min')) {
            $durationMin = filter_var($request->service_duration_min, FILTER_VALIDATE_INT);
            if ($durationMin !== false && $durationMin >= 0) {
                $servicesQuery->where('duration_hours', '>=', $durationMin);
            }
        }

        if ($request->filled('service_duration_max')) {
            $durationMax = filter_var($request->service_duration_max, FILTER_VALIDATE_INT);
            if ($durationMax !== false && $durationMax >= 0) {
                $servicesQuery->where('duration_hours', '<=', $durationMax);
            }
        }

        if ($request->filled('service_sort')) {
            switch ($request->service_sort) {
                case 'newest':
                    $servicesQuery->latest();
                    break;
                case 'oldest':
                    $servicesQuery->oldest();
                    break;
                case 'price_low':
                    $servicesQuery->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $servicesQuery->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $servicesQuery->orderBy('completed_count', 'desc');
                    break;
                case 'rating':
                    $servicesQuery->withAvg('ratings', 'rating')
                                  ->orderBy('ratings_avg_rating', 'desc');
                    break;
                default:
                    $servicesQuery->latest();
            }
        } else {
            $servicesQuery->latest();
        }

        // Per page for services
        $servicePerPage = $request->get('service_per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$servicePerPage, $validPerPage)) {
            $servicePerPage = 12;
        }

        $services = $servicesQuery->paginate((int)$servicePerPage)->withQueryString();

        // Get categories for filters
        $productCategories = $store->products()
            ->where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $serviceCategories = $store->services()
            ->where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Calculate store stats
        $stats = [
            'total_products' => $store->products()->where('is_active', true)->count(),
            'total_services' => $store->services()->where('is_active', true)->count(),
            'total_sold' => $store->products()->sum('sold_count') + $store->services()->sum('sold_count'),
            'followers_count' => $store->followersCount(),
            'rating' => $this->calculateStoreRating($store),
            'reviews_count' => $this->getStoreReviewsCount($store),
            'member_since' => $store->created_at,
            'response_rate' => 95, // TODO: Calculate real response rate
            'completion_rate' => 98, // TODO: Calculate real completion rate
        ];

        // Check if current user is following
        $isFollowing = auth()->check() ? $store->isFollowedBy(auth()->id()) : false;

        // Get reviews for reviews tab
        $reviews = $this->getStoreReviews($store)->paginate(10)->withQueryString();

        return view('store.show', compact(
            'store',
            'products',
            'services',
            'productCategories',
            'serviceCategories',
            'stats',
            'isFollowing',
            'reviews',
            'activeTab'
        ));
    }

    /**
     * Follow/Unfollow a store
     */
    public function toggleFollow(string $slug): RedirectResponse
    {
        $store = User::where('store_slug', $slug)->firstOrFail();

        // Prevent self-follow
        if (auth()->id() === $store->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa follow toko sendiri']);
        }

        $existing = StoreFollower::where('user_id', auth()->id())
            ->where('store_owner_id', $store->id)
            ->first();

        if ($existing) {
            // Unfollow
            $existing->delete();
            return back()->with('success', 'Berhenti mengikuti toko');
        } else {
            // Follow
            StoreFollower::create([
                'user_id' => auth()->id(),
                'store_owner_id' => $store->id,
            ]);
            return back()->with('success', 'Berhasil mengikuti toko');
        }
    }

    /**
     * Calculate average store rating
     */
    private function calculateStoreRating(User $store): float
    {
        $products = $store->products()->whereHas('ratings')->with('ratings')->get();
        $services = $store->services()->whereHas('ratings')->with('ratings')->get();
        
        $allRatings = $products->pluck('ratings')->flatten()
            ->merge($services->pluck('ratings')->flatten());
        
        return $allRatings->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    private function getStoreReviewsCount(User $store): int
    {
        $productsRatings = DB::table('ratings')
            ->whereIn('product_id', $store->products()->pluck('id'))
            ->whereNotNull('comment')
            ->count();
        
        $servicesRatings = DB::table('ratings')
            ->whereIn('service_id', $store->services()->pluck('id'))
            ->whereNotNull('comment')
            ->count();
        
        return $productsRatings + $servicesRatings;
    }

    /**
     * Get all store reviews
     */
    private function getStoreReviews(User $store)
    {
        $productIds = $store->products()->pluck('id');
        $serviceIds = $store->services()->pluck('id');
        
        return DB::table('ratings')
            ->select('ratings.*', 
                DB::raw('COALESCE(products.title, services.title) as item_title'),
                DB::raw('CASE WHEN ratings.product_id IS NOT NULL THEN "product" ELSE "service" END as item_type')
            )
            ->leftJoin('products', 'ratings.product_id', '=', 'products.id')
            ->leftJoin('services', 'ratings.service_id', '=', 'services.id')
            ->leftJoin('users', 'ratings.user_id', '=', 'users.id')
            ->addSelect('users.name as user_name', 'users.avatar as user_avatar')
            ->where(function($query) use ($productIds, $serviceIds) {
                $query->whereIn('ratings.product_id', $productIds)
                      ->orWhereIn('ratings.service_id', $serviceIds);
            })
            ->whereNotNull('ratings.comment')
            ->orderBy('ratings.created_at', 'desc');
    }
}
