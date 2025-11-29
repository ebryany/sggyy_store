<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'platform_fee',
        'seller_earning',
        'status',
        'hold_until',
        'released_at',
        'released_by',
        'release_type',
        'is_disputed',
        'disputed_at',
        'dispute_reason',
        'disputed_by',
            'xendit_invoice_id',
            'xendit_external_id',
            'xendit_disbursement_id',
            'xendit_disbursement_external_id',
            'xendit_disbursement_metadata',
        ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'seller_earning' => 'decimal:2',
            'hold_until' => 'datetime',
            'released_at' => 'datetime',
            'disputed_at' => 'datetime',
            'is_disputed' => 'boolean',
            'xendit_disbursement_metadata' => 'array',
            // 🔒 SECURITY: Encrypt sensitive Xendit identifiers
            'xendit_invoice_id' => \App\Casts\Encrypted::class,
            'xendit_external_id' => \App\Casts\Encrypted::class,
            'xendit_disbursement_id' => \App\Casts\Encrypted::class,
            'xendit_disbursement_external_id' => \App\Casts\Encrypted::class,
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function disputedBy()
    {
        return $this->belongsTo(User::class, 'disputed_by');
    }

    // Helper methods
    public function isHolding(): bool
    {
        return $this->status === 'holding';
    }

    public function isReleased(): bool
    {
        return $this->status === 'released';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isDisputed(): bool
    {
        return $this->status === 'disputed' || $this->is_disputed;
    }

    public function canBeReleased(bool $allowEarlyRelease = false): bool
    {
        $baseCheck = $this->isHolding() && !$this->is_disputed;
        
        if ($allowEarlyRelease) {
            // Early release: buyer can confirm before hold period expires
            return $baseCheck;
        }
        
        // Auto release: only if hold period expired or no hold period set
        return $baseCheck && ($this->hold_until === null || now() >= $this->hold_until);
    }

    public function canBeDisputed(): bool
    {
        return $this->isHolding() && !$this->is_disputed;
    }
}

