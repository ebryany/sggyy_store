<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All users (including guests) can view products
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        // All users can view active products
        if ($product->is_active) {
            return true;
        }
        
        // Seller can view their own products even if inactive
        if ($product->user_id === $user->id) {
            return true;
        }
        
        // Admin can view any product
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
        // Only sellers and admins can create products
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        // Seller can update their own products
        if ($product->user_id === $user->id) {
            return true;
        }
        
        // Admin can update any product
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        // Seller can delete their own products
        if ($product->user_id === $user->id) {
            return true;
        }
        
        // Admin can delete any product
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can download the product file.
     * 🔒 CRITICAL SECURITY: User MUST have purchased the product + check expiry & limit
     */
    public function download(User $user, Product $product, ?\App\Models\Order $order = null): bool
    {
        // Admin can download any product
        if ($user->isAdmin()) {
            return true;
        }
        
        // Owner can download their own product
        if ($product->user_id === $user->id) {
            return true;
        }
        
        // If order is provided, use it for validation
        if ($order) {
            // Verify order belongs to user
            if ($order->user_id !== $user->id) {
                return false;
            }
            
            // Verify order is for this product
            if ($order->product_id !== $product->id || $order->type !== 'product') {
                return false;
            }
            
            // Use order's canDownload() method (checks expiry + limit)
            return $order->canDownload();
        }
        
        // Otherwise, check if user has any completed order for this product
        // (basic check - detailed validation with expiry/limit will be in controller)
        $hasPurchased = \App\Models\Order::where('user_id', $user->id)
            ->where('type', 'product')
            ->where('product_id', $product->id)
            ->where('status', 'completed')
            ->exists();
        
        return $hasPurchased;
    }
}
