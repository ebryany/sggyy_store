<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingService
{
    /**
     * Create rating for an order
     * 
     * @param Order $order
     * @param array $data
     * @return Rating
     * @throws \Exception
     */
    public function createRating(Order $order, array $data): Rating
    {
        try {
            return DB::transaction(function () use ($order, $data) {
                // Check if user already rated this order
                $existing = Rating::where('user_id', auth()->id())
                    ->where('order_id', $order->id)
                    ->first();

                if ($existing) {
                    throw new \Exception('Anda sudah memberikan rating untuk pesanan ini');
                }

                // Validate order status - only completed orders can be rated
                if ($order->status !== 'completed') {
                    throw new \Exception('Hanya pesanan yang sudah selesai yang dapat diberi rating');
                }

                // Determine product_id or service_id based on order type
                $ratingData = [
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'rating' => $data['rating'],
                    'comment' => $data['comment'] ?? null,
                ];

                if ($order->type === 'product' && $order->product_id) {
                    $ratingData['product_id'] = $order->product_id;
                } elseif ($order->type === 'service' && $order->service_id) {
                    $ratingData['service_id'] = $order->service_id;
                }

                $rating = Rating::create($ratingData);

                Log::info('Rating created', [
                    'rating_id' => $rating->id,
                    'order_id' => $order->id,
                    'user_id' => auth()->id(),
                    'rating_value' => $data['rating'],
                ]);

                return $rating;
            });
        } catch (\Exception $e) {
            Log::error('Failed to create rating', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memberikan rating: ' . $e->getMessage());
        }
    }

    /**
     * Update rating
     * 
     * @param Rating $rating
     * @param array $data
     * @return Rating
     * @throws \Exception
     */
    public function updateRating(Rating $rating, array $data): Rating
    {
        try {
            $rating->update([
                'rating' => $data['rating'] ?? $rating->rating,
                'comment' => $data['comment'] ?? $rating->comment,
            ]);

            Log::info('Rating updated', [
                'rating_id' => $rating->id,
                'user_id' => auth()->id(),
            ]);

            return $rating->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update rating', [
                'rating_id' => $rating->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memperbarui rating: ' . $e->getMessage());
        }
    }

    /**
     * Delete rating
     * 
     * @param Rating $rating
     * @return bool
     * @throws \Exception
     */
    public function deleteRating(Rating $rating): bool
    {
        try {
            $ratingId = $rating->id;
            $deleted = $rating->delete();

            Log::info('Rating deleted', [
                'rating_id' => $ratingId,
                'user_id' => auth()->id(),
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Failed to delete rating', [
                'rating_id' => $rating->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menghapus rating: ' . $e->getMessage());
        }
    }
}

