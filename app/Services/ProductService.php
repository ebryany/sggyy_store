<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductFeature;
use App\Models\Tag;
use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data, ?UploadedFile $image = null, ?UploadedFile $file = null, ?array $images = null, ?array $tags = null, ?array $features = null): Product
    {
        $data['user_id'] = auth()->id();

        // Generate slug if not provided
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Product::generateSlug($data['title']);
        }

        // Generate SKU if not provided
        if (empty($data['sku']) && !empty($data['title'])) {
            $data['sku'] = $this->generateSku($data['title']);
        }

        // Calculate file size if file is provided
        if ($file) {
            $data['file_path'] = $this->storeFile($file);
            $data['file_size'] = $this->formatFileSize($file->getSize());
        }

        // Handle main image (for backward compatibility)
        if ($image) {
            $data['image'] = $this->storeImage($image);
        }

        // Handle published_at
        if (isset($data['is_draft']) && !$data['is_draft'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            // Handle multiple images
            if ($images && count($images) > 0) {
                $this->storeProductImages($product, $images);
            }

            // Handle tags
            if ($tags && count($tags) > 0) {
                $this->syncTags($product, $tags);
            }

            // Handle features
            if ($features && count($features) > 0) {
                $this->storeFeatures($product, $features);
            }

            DB::commit();
            return $product->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null, ?UploadedFile $file = null, ?array $images = null, ?array $tags = null, ?array $features = null): Product
    {
        // Regenerate slug if title changed
        if (isset($data['title']) && $data['title'] !== $product->title) {
            $data['slug'] = Product::generateSlug($data['title'], $product->id);
        }

        // Calculate file size if file is provided
        $disk = config('filesystems.default');
        if ($file) {
            if ($product->file_path) {
                Storage::disk($disk)->delete($product->file_path);
            }
            $data['file_path'] = $this->storeFile($file);
            $data['file_size'] = $this->formatFileSize($file->getSize());
        }

        // Handle main image (for backward compatibility)
        if ($image) {
            if ($product->image) {
                Storage::disk($disk)->delete($product->image);
            }
            $data['image'] = $this->storeImage($image);
        }

        // Handle published_at
        if (isset($data['is_draft']) && !$data['is_draft'] && empty($data['published_at']) && $product->is_draft) {
            $data['published_at'] = now();
        }

        DB::beginTransaction();
        try {
            $product->update($data);

            // Handle multiple images
            if ($images !== null) {
                $this->syncProductImages($product, $images);
            }

            // Handle tags
            if ($tags !== null) {
                $this->syncTags($product, $tags);
            }

            // Handle features
            if ($features !== null) {
                $this->syncFeatures($product, $features);
            }

            DB::commit();
            return $product->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Product $product): bool
    {
        // Delete main image
        $disk = config('filesystems.default');
        if ($product->image) {
            Storage::disk($disk)->delete($product->image);
        }

        // Delete product images
        foreach ($product->images as $image) {
            Storage::disk($disk)->delete($image->image_path);
        }

        // Delete file (check both default and private disk for backward compatibility)
        if ($product->file_path) {
            // Try private disk first (new files)
            if (Storage::disk('local')->exists($product->file_path)) {
                Storage::disk('local')->delete($product->file_path);
            }
            // Try default disk (legacy files)
            if (Storage::disk($disk)->exists($product->file_path)) {
                Storage::disk($disk)->delete($product->file_path);
            }
        }

        return $product->delete();
    }

    /**
     * Get signed download URL for product file
     * 🔒 CRITICAL SECURITY: Uses signed route for private file access
     * 
     * @param Product $product
     * @param Order $order Order instance (for validation)
     * @param int $expirationMinutes Default 30 minutes
     * @return string Signed URL
     * @throws \Exception
     */
    public function getSignedDownloadUrl(Product $product, Order $order, int $expirationMinutes = 30): string
    {
        if (!$product->file_path) {
            throw new \Exception('Product file not found');
        }

        // Check if file exists in private storage
        if (!Storage::disk('local')->exists($product->file_path)) {
            throw new \Exception('File tidak ditemukan di storage');
        }

        // 🔒 SECURITY: Generate signed route with expiration
        // Signed route includes product ID, order ID, and expiration timestamp
        // Route will validate authorization and download limits in middleware/controller
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'products.download.signed',
            now()->addMinutes($expirationMinutes),
            [
                'product' => $product->id,
                'order' => $order->id,
            ]
        );
    }
    
    /**
     * Get direct file response for download (used by signed route)
     * 🔒 CRITICAL SECURITY: Private disk access with path validation
     */
    public function getFileDownloadResponse(Product $product): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (!$product->file_path) {
            abort(404, 'File produk tidak tersedia');
        }

            // Try private disk first (new files)
        $disk = null;
        $fullPath = null;
        $allowedPath = null;
        
        if (Storage::disk('local')->exists($product->file_path)) {
            $disk = 'local';
            $fullPath = Storage::disk('local')->path($product->file_path);
            $allowedPath = Storage::disk('local')->path('products/files');
        } 
        // Backward compatibility: Try public disk (legacy files)
        elseif (Storage::disk('public')->exists($product->file_path)) {
            $disk = 'public';
            $fullPath = Storage::disk('public')->path($product->file_path);
            $allowedPath = Storage::disk('public')->path('products/files');
        } else {
            \Illuminate\Support\Facades\Log::error('Product file not found in storage', [
                'product_id' => $product->id,
                'file_path' => $product->file_path,
            ]);
            abort(404, 'File tidak ditemukan di storage');
        }

        // Validate path is within allowed directory (prevent path traversal)
        $realFullPath = realpath($fullPath);
        $realAllowedPath = realpath($allowedPath);
        
        if (!$realFullPath || !$realAllowedPath || !str_starts_with($realFullPath, $realAllowedPath)) {
            SecurityLogger::logSuspiciousActivity('Path traversal attempt in product download', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'file_path' => $product->file_path,
                'full_path' => $fullPath,
                'allowed_path' => $allowedPath,
                'disk' => $disk,
            ]);
            abort(403, 'Invalid file path');
        }

        // Return file download response
        return response()->download($realFullPath, basename($product->file_path));
    }

    private function storeImage(UploadedFile $file): string
    {
        $disk = config('filesystems.default');
        return $file->store('products/images', $disk);
    }

    /**
     * Store product file to PRIVATE disk (not public)
     * 🔒 CRITICAL SECURITY: Digital products must be in private storage
     */
    private function storeFile(UploadedFile $file): string
    {
        return $file->store('products/files', 'local'); // Changed from 'public' to 'local' (private)
    }

    private function storeProductImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $this->storeImage($image),
                    'sort_order' => $index,
                ]);
            }
        }
    }

    private function syncProductImages(Product $product, array $images): void
    {
        // Delete existing images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Store new images
        $this->storeProductImages($product, $images);
    }

    private function syncTags(Product $product, array $tags): void
    {
        $tagIds = [];
        foreach ($tags as $tagName) {
            if (!empty(trim($tagName))) {
                $tag = Tag::findOrCreateByName(trim($tagName));
                $tagIds[] = $tag->id;
            }
        }
        $product->tags()->sync($tagIds);
    }

    private function storeFeatures(Product $product, array $features): void
    {
        foreach ($features as $index => $feature) {
            if (!empty(trim($feature))) {
                ProductFeature::create([
                    'product_id' => $product->id,
                    'feature' => trim($feature),
                    'sort_order' => $index,
                ]);
            }
        }
    }

    private function syncFeatures(Product $product, array $features): void
    {
        // Delete existing features
        $product->features()->delete();

        // Store new features
        $this->storeFeatures($product, $features);
    }

    private function generateSku(string $title): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $title), 0, 6));
        $sku = $base . '-' . strtoupper(Str::random(4));
        
        // Ensure uniqueness
        $counter = 1;
        while (Product::where('sku', $sku)->exists()) {
            $sku = $base . '-' . strtoupper(Str::random(4)) . $counter;
            $counter++;
        }
        
        return $sku;
    }

    private function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}





