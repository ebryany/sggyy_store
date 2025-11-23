<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users (including guests) can view services
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Service $service): bool
    {
        // All users can view active services
        if ($service->isActive()) {
            return true;
        }
        
        // Seller can view their own services even if inactive
        if ($service->user_id === $user->id) {
            return true;
        }
        
        // Admin can view any service
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only sellers and admins can create services
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        // Seller can update their own services
        if ($service->user_id === $user->id) {
            return true;
        }
        
        // Admin can update any service
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Service $service): bool
    {
        // Seller can delete their own services
        if ($service->user_id === $user->id) {
            return true;
        }
        
        // Admin can delete any service
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }
}
