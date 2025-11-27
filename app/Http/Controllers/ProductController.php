<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\SettingsService;
use App\Services\SecurityLogger;
use App\Services\SecureFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private SettingsService $settingsService,
        private SecureFileService $secureFileService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['user', 'ratings'])
            ->where('is_active', true)
            // Exclude quota products from digital products page
            ->where(function ($q) {
                $q->where('category', '!=', 'Quota')
                  ->where('category', '!=', 'Kuota')
                  ->where('category', '!=', 'quota')
                  ->where('category', '!=', 'kuota');
            })
            ->where(function ($q) {
                $q->where('product_type', '!=', 'quota')
                  ->where('product_type', '!=', 'kuota')
                  ->orWhereNull('product_type');
            })
            // Exclude products with quota-related titles
            ->where(function ($q) {
                $q->where('title', 'not like', '%quota%')
                  ->where('title', 'not like', '%Quota%')
                  ->where('title', 'not like', '%QUOTA%')
                  ->where('title', 'not like', '%kuota%')
                  ->where('title', 'not like', '%Kuota%')
                  ->where('title', 'not like', '%KUOTA%');
            })
            ->where(function ($q) {
                $q->where('slug', 'not like', '%quota%')
                  ->where('slug', 'not like', '%kuota%')
                  ->orWhereNull('slug');
            });

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

        // ✅ PHASE 1 FIX: Validate category with whitelist
        if ($request->filled('category')) {
            $category = trim($request->category);
            $category = strip_tags($category);
            
            // Get valid categories from database
            $validCategories = Product::where('is_active', true)
                ->distinct()
                ->pluck('category')
                ->toArray();
            
            if (in_array($category, $validCategories)) {
                $query->where('category', $category);
            }
        }

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
                    $q->selectRaw('product_id, AVG(rating) as avg_rating')
                      ->groupBy('product_id')
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

        // Stock filter
        if ($request->filled('in_stock')) {
            $inStock = filter_var($request->in_stock, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($inStock !== null) {
                if ($inStock) {
                    $query->where('stock', '>', 0);
                } else {
                    $query->where('stock', '<=', 0);
                }
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'rating', 'popular', 'sold'];
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
                $query->orderBy('views_count', 'desc');
                break;
            case 'sold':
                $query->orderBy('sold_count', 'desc');
                break;
        }

        // Per page (limit items per page)
        $perPage = $request->get('per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 12;
        }

        $products = $query->paginate((int)$perPage)->withQueryString();
        
        // Get distinct categories for filter (exclude quota categories)
        $categories = Product::where('is_active', true)
            ->where(function ($q) {
                $q->where('category', '!=', 'Quota')
                  ->where('category', '!=', 'Kuota')
                  ->where('category', '!=', 'quota')
                  ->where('category', '!=', 'kuota');
            })
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            // Extract arrays for separate handling
            $images = $request->hasFile('images') ? $request->file('images') : null;
            $tags = $request->input('tags', []);
            $features = $request->input('features', []);
            
            // Remove from validated data (handled separately)
            unset($validated['images'], $validated['tags'], $validated['features']);
            
            $product = $this->productService->create(
                $validated,
                $request->file('image'),
                $request->file('file'),
                $images,
                $tags,
                $features
            );

            return redirect()
                ->route('products.show', $product)
                ->with('success', 'Produk berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $product): View
    {
        // Support both slug and ID for backward compatibility
        if ($product instanceof Product) {
            // Route model binding found by slug
            $productModel = $product;
        } else {
            // Try to find by slug or ID
            $productModel = Product::findBySlugOrId($product);
            
            if (!$productModel) {
                abort(404, 'Produk tidak ditemukan');
            }
        }
        
        $productModel->load(['user', 'ratings.user', 'images', 'tags', 'features']);
        
        // ✅ Track product view (only count once per session per product)
        $sessionKey = 'viewed_product_' . $productModel->id;
        if (!session()->has($sessionKey)) {
            $productModel->incrementViews();
            session()->put($sessionKey, true);
        }
        
        // Get bank account info for payment methods
        $bankAccountInfo = $this->settingsService->getBankAccountInfo();
        $featureFlags = $this->settingsService->getFeatureFlags();

        return view('products.show', [
            'product' => $productModel,
            'bankAccountInfo' => $bankAccountInfo,
            'featureFlags' => $featureFlags
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product): View
    {
        // Support both slug and ID
        $productModel = $product instanceof Product 
            ? $product 
            : Product::findBySlugOrId($product);
        
        if (!$productModel) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Authorization: only owner can edit
        if ($productModel->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Load relationships
        $productModel->load(['images', 'tags', 'features']);

        return view('products.edit', ['product' => $productModel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $product): RedirectResponse
    {
        // Support both slug and ID
        $productModel = $product instanceof Product 
            ? $product 
            : Product::findBySlugOrId($product);
        
        if (!$productModel) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Authorization: only owner can update
        if ($productModel->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $validated = $request->validated();
            
            // Extract arrays for separate handling
            $images = $request->hasFile('images') ? $request->file('images') : null;
            $tags = $request->input('tags', []);
            $features = $request->input('features', []);
            
            // Remove from validated data (handled separately)
            unset($validated['images'], $validated['tags'], $validated['features']);
            
            $this->productService->update(
                $productModel,
                $validated,
                $request->file('image'),
                $request->file('file'),
                $images,
                $tags,
                $features
            );

            return redirect()
                ->route('products.show', $productModel)
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product): RedirectResponse
    {
        // Support both slug and ID
        $productModel = $product instanceof Product 
            ? $product 
            : Product::findBySlugOrId($product);
        
        if (!$productModel) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Authorization: only owner or admin can delete
        if ($productModel->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $this->productService->delete($productModel);

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Download product file (public route - redirects to signed route)
     * 🔒 SECURITY: Validates purchase, expiry, limit, then redirects to signed URL
     */
    public function download($product, Request $request)
    {
        $user = auth()->user();
        
        // Support both slug and ID
        $productModel = $product instanceof Product 
            ? $product 
            : Product::findBySlugOrId($product);
        
        if (!$productModel) {
            abort(404, 'Produk tidak ditemukan');
        }
        
        // Admin and owner can download directly (bypass order check)
        if ($user->isAdmin() || $productModel->user_id === $user->id) {
            // Generate signed URL without order check (order ID = 0 for admin/owner)
            $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'products.download.signed',
                now()->addMinutes(30),
                [
                    'product' => $productModel->id,
                    'order' => 0, // Special value for admin/owner
                ]
            );
            return redirect($signedUrl);
        }
        
        // 🔒 REKBER FLOW: Find user's order (processing or completed) for this product
        $order = \App\Models\Order::where('user_id', $user->id)
            ->where('type', 'product')
            ->where('product_id', $productModel->id)
            ->whereIn('status', ['processing', 'waiting_confirmation', 'completed'])
            ->latest('created_at')
            ->first();
        
        if (!$order) {
            // Log denied attempt
            \App\Models\ProductDownload::logDenied(
                $user,
                $productModel,
                new \App\Models\Order(['id' => 0]), // Dummy order for logging
                'No completed order found'
            );
            
            abort(403, 'Anda belum membeli produk ini atau pesanan belum selesai');
        }
        
        // 🔒 SECURITY: Use Policy with order for expiry/limit check
        if (!$this->authorize('download', [$productModel, $order])) {
            // Log denied attempt
            \App\Models\ProductDownload::logDenied(
                $user,
                $productModel,
                $order,
                'Policy check failed'
            );
            
            abort(403, 'Akses download ditolak');
        }
        
        // Validate order can download (expiry + limit)
        if (!$order->canDownload()) {
            $denyReason = 'Download expired or limit exceeded';
            if ($order->download_expires_at && $order->download_expires_at->isPast()) {
                $denyReason = 'Download telah kedaluwarsa (expired: ' . $order->download_expires_at->format('d M Y H:i') . ')';
            } elseif ($order->download_count >= $order->download_limit) {
                $denyReason = 'Batas download telah tercapai (' . $order->download_count . '/' . $order->download_limit . ')';
            }
            
            // Log denied attempt
            \App\Models\ProductDownload::logDenied(
                $user,
                $productModel,
                $order,
                $denyReason
            );
            
            return back()->withErrors(['error' => $denyReason]);
        }
        
        if (!$productModel->file_path) {
            return back()->withErrors(['error' => 'File produk tidak tersedia']);
        }

        try {
            // Generate signed URL for private file access
            $signedUrl = $this->productService->getSignedDownloadUrl($productModel, $order, 30);
            
            // Redirect to signed route
            return redirect($signedUrl);
        } catch (\Exception $e) {
            // Log download error
            \Illuminate\Support\Facades\Log::error('Product download failed', [
                'user_id' => $user->id,
                'product_id' => $productModel->id,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            // Log denied attempt
            \App\Models\ProductDownload::logDenied(
                $user,
                $productModel,
                $order,
                'Failed to generate signed URL: ' . $e->getMessage()
            );
            
            return back()->withErrors(['error' => 'Gagal mengunduh file. Silakan coba lagi atau hubungi support.']);
        }
    }
    
    /**
     * Download product file via signed route (private file access)
     * 🔒 CRITICAL SECURITY: Signed route validates authorization + expiry + limit
     */
    public function downloadSigned(Product $product, \App\Models\Order $order, Request $request)
    {
        $user = auth()->user();
        
        // Special case: Admin and owner (order ID = 0)
        if ($order->id === 0) {
            if ($user->isAdmin() || $product->user_id === $user->id) {
                // Direct file access for admin/owner
                try {
                    return $this->productService->getFileDownloadResponse($product);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Admin/Owner product download failed', [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'error' => $e->getMessage(),
                    ]);
                    abort(500, 'Gagal mengunduh file');
                }
            }
            abort(403, 'Unauthorized');
        }
        
        // Verify order belongs to user
        if ($order->user_id !== $user->id) {
            \App\Models\ProductDownload::logDenied(
                $user,
                $product,
                $order,
                'Order does not belong to user'
            );
            abort(403, 'Unauthorized');
        }
        
        // Verify order is for this product
        if ($order->product_id !== $product->id || $order->type !== 'product') {
            \App\Models\ProductDownload::logDenied(
                $user,
                $product,
                $order,
                'Order does not match product'
            );
            abort(403, 'Invalid order for this product');
        }
        
        // 🔒 REKBER FLOW: Verify order status is processing or completed
        if (!in_array($order->status, ['processing', 'waiting_confirmation', 'completed'])) {
            \App\Models\ProductDownload::logDenied(
                $user,
                $product,
                $order,
                'Order not completed'
            );
            abort(403, 'Pesanan belum selesai');
        }
        
        // Check expiry and limit using order's canDownload()
        if (!$order->canDownload()) {
            $denyReason = 'Download expired or limit exceeded';
            if ($order->download_expires_at && $order->download_expires_at->isPast()) {
                $denyReason = 'Download expired';
            } elseif ($order->download_count >= $order->download_limit) {
                $denyReason = 'Download limit exceeded';
            }
            
            \App\Models\ProductDownload::logDenied(
                $user,
                $product,
                $order,
                $denyReason
            );
            abort(403, $denyReason);
        }
        
        if (!$product->file_path) {
            abort(404, 'File produk tidak tersedia');
        }

        try {
            // Increment download count (atomic operation)
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                $order->incrementDownloadCount();
            });
            
            // Log successful download
            \App\Models\ProductDownload::logSuccess(
                $user,
                $product,
                $order,
                $request->ip(),
                $request->userAgent()
            );
            
            // Return file download response
            return $this->productService->getFileDownloadResponse($product);
        } catch (\Exception $e) {
            // Log download error
            \Illuminate\Support\Facades\Log::error('Product download failed', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            // Log denied attempt
            \App\Models\ProductDownload::logDenied(
                $user,
                $product,
                $order,
                'File download error: ' . $e->getMessage()
            );
            
            abort(500, 'Gagal mengunduh file');
        }
    }
}
