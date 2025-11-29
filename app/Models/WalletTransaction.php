<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'amount',
        'status',
        'payment_method',
        'proof_path',
        'description',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'reference_number',
        'veripay_transaction_ref',
        'veripay_payment_url',
        'veripay_metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'veripay_metadata' => 'array',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Generate reference number and UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (empty($transaction->reference_number)) {
                $transaction->reference_number = 'WT-' . strtoupper(\Illuminate\Support\Str::random(12));
            }
        });

        static::saving(function ($transaction) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            if (empty($transaction->uuid)) {
                $transaction->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}

        });
    }
}
