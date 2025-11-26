<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Public ratings can be viewed by anyone
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rating $rating): bool
    {
        // Public ratings can be viewed by anyone
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $order): bool
    {
        // Only order owner can create rating
        if (!$order instanceof \App\Models\Order) {
            return false;
        }
        
        return $order->user_id === $user->id && $order->canBeRated();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rating $rating): bool
    {
        // Admin can update any rating
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only rating owner can update
        if ($rating->user_id !== $user->id) {
            return false;
        }
        
        // Verify rating belongs to user's order
        $order = $rating->order;
        return $order && $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rating $rating): bool
    {
        // Admin can delete any rating
        if ($user->isAdmin()) {
            return true;
        }
        
        // Only rating owner can delete
        if ($rating->user_id !== $user->id) {
            return false;
        }
        
        // Verify rating belongs to user's order
        $order = $rating->order;
        return $order && $order->user_id === $user->id;
    }
}








