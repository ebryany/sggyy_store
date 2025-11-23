<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'user_id',
        'message',
        'attachment_path',
        'is_read',
        'read_at',
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];
    
    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper methods
    public function getAttachmentUrl(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }
        
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->attachment_path);
    }
    
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
