<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreFollower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_owner_id',
    ];

    /**
     * Relationship: The user who is following
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: The store owner being followed
     */
    public function storeOwner()
    {
        return $this->belongsTo(User::class, 'store_owner_id');
    }
}
