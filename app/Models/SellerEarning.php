<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'seller_id',
        'order_id',
        'amount',
        'platform_fee',
        'status',
        'available_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($earning) {
            if (empty($earning->uuid)) {
                $earning->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        static::saving(function ($earning) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            if (empty($earning->uuid)) {
                $earning->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'available_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        if (request()->is('api/*')) {
            return 'uuid';
        }
        return 'id';
    }

    // Relationships
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isWithdrawn(): bool
    {
        return $this->status === 'withdrawn';
    }
}
