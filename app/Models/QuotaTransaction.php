<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ref_id',
        'trx_id',
        'produk',
        'tujuan',
        'harga',
        'saldo_awal',
        'saldo_akhir',
        'status',
        'status_code',
        'status_text',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'saldo_awal' => 'decimal:2',
            'saldo_akhir' => 'decimal:2',
            'status_code' => 'integer',
        ];
    }

    /**
     * Relationship: User who made the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'success' => 'green',
            'failed' => 'red',
            'processing' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'success' => 'Sukses',
            'failed' => 'Gagal',
            'processing' => 'Diproses',
            'pending' => 'Menunggu',
            default => ucfirst($this->status),
        };
    }
}
