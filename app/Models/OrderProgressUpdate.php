<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProgressUpdate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'user_id',
        'progress_from',
        'progress_to',
        'notes',
        'attachment_path',
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
    
    public function isMilestone(): bool
    {
        $milestones = [25, 50, 75, 100];
        return in_array($this->progress_to, $milestones);
    }
}
