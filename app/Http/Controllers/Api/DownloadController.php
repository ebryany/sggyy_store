<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class DownloadController extends BaseApiController
{
    /**
     * Get download links for product order
     * 
     * GET /api/v1/orders/{order_uuid}/downloads
     */
    public function getDownloadLinks(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this order');
        }

        // Check if order is product type
        if ($order->type !== 'product') {
            return $this->error(
                'Only product orders can be downloaded',
                [],
                'INVALID_ORDER_TYPE',
                400
            );
        }

        // Check if order can be downloaded
        if (!$order->canDownload()) {
            return $this->error(
                'This order cannot be downloaded. Check download limit or expiry.',
                [],
                'DOWNLOAD_NOT_ALLOWED',
                403
            );
        }

        // Generate signed download URL
        $downloadUrl = URL::temporarySignedRoute(
            'api.downloads.download',
            now()->addMinutes(30),
            ['download_token' => $order->uuid]
        );

        return $this->success([
            'download_url' => $downloadUrl,
            'expires_at' => now()->addMinutes(30)->toIso8601String(),
            'download_count' => $order->download_count,
            'download_limit' => $order->download_limit,
            'downloads_remaining' => $order->download_limit - $order->download_count,
        ]);
    }

    /**
     * Download product file (signed URL)
     * 
     * GET /api/v1/downloads/{download_token}
     */
    public function download(string $downloadToken)
    {
        // Find order by UUID (download_token is order UUID)
        $order = Order::where('uuid', $downloadToken)
            ->where('user_id', auth()->id())
            ->where('type', 'product')
            ->first();

        if (!$order) {
            return $this->notFound('Order');
        }

        // Check if order can be downloaded
        if (!$order->canDownload()) {
            return $this->error(
                'This order cannot be downloaded. Check download limit or expiry.',
                [],
                'DOWNLOAD_NOT_ALLOWED',
                403
            );
        }

        $product = $order->product;
        if (!$product || !$product->file_path) {
            return $this->error(
                'Product file not found',
                [],
                'FILE_NOT_FOUND',
                404
            );
        }

        // Increment download count
        $order->incrementDownloadCount();

        // Set download expiry if not set
        if (!$order->download_expires_at) {
            $order->setDownloadExpiry();
        }

        // Get file from storage
        $disk = config('filesystems.default');
        
        if (!Storage::disk($disk)->exists($product->file_path)) {
            return $this->error(
                'Product file not found in storage',
                [],
                'FILE_NOT_FOUND',
                404
            );
        }

        // Return file download
        $fileName = basename($product->file_path);
        
        return response()->download(
            Storage::disk($disk)->path($product->file_path),
            $fileName
        );
    }
}

