<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SellerWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'seller_id',
        'amount',
        'status',
        'method',
        'account_number',
        'account_name',
        'bank_name',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'reference_number',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($withdrawal) {
            if (empty($withdrawal->uuid)) {
                $withdrawal->uuid = (string) Str::uuid();
            }
            if (empty($withdrawal->reference_number)) {
                $withdrawal->reference_number = 'WD-' . strtoupper(Str::random(12));
            }
        });

        static::saving(function ($withdrawal) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            if (empty($withdrawal->uuid)) {
                $withdrawal->uuid = (string) Str::uuid();
            }
        });
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

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
