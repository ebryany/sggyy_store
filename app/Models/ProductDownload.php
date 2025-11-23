<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDownload extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'ip_address',
        'user_agent',
        'result',
        'deny_reason',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Log successful download
     */
    public static function logSuccess(User $user, Product $product, Order $order, ?string $ip = null, ?string $userAgent = null): self
    {
        return self::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'result' => 'success',
            'downloaded_at' => now(),
        ]);
    }

    /**
     * Log denied download
     */
    public static function logDenied(User $user, Product $product, Order $order, string $reason, ?string $ip = null, ?string $userAgent = null): self
    {
        return self::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'result' => 'denied',
            'deny_reason' => $reason,
            'downloaded_at' => now(),
        ]);
    }
}
