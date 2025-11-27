<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their own orders
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // User can view their own orders
        if ($order->user_id === $user->id) {
            return true;
        }
        
        // Admin can view any order
        if ($user->isAdmin()) {
            return true;
        }
        
        // Seller can view orders for their products/services
        if ($user->isSeller()) {
            if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
                return true;
            }
            if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create orders
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Admin can update any order
        if ($user->isAdmin()) {
            return true;
        }
        
        // Seller can update orders for their products/services
        if ($user->isSeller()) {
            if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
                return true;
            }
            if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only admin can delete orders
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // User can cancel their own pending orders
        if ($order->user_id === $user->id && $order->status === 'pending') {
            return true;
        }
        
        // Admin can cancel any order
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can request revision.
     */
    public function requestRevision(User $user, Order $order): bool
    {
        // Only buyer can request revision for completed service orders
        return $order->user_id === $user->id 
            && $order->type === 'service' 
            && $order->status === 'completed';
    }

    /**
     * Determine whether the user can upload deliverable.
     */
    public function uploadDeliverable(User $user, Order $order): bool
    {
        // Only seller can upload deliverable for their service orders
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return in_array($order->status, ['processing', 'completed']);
        }
        
        // Admin can upload deliverable for any order
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can download deliverable.
     */
    public function downloadDeliverable(User $user, Order $order): bool
    {
        // Buyer can download their own order's deliverable (only after completed)
        if ($order->user_id === $user->id) {
            return $order->status === 'completed';
        }
        
        // Seller can download deliverable they uploaded (processing or completed)
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return in_array($order->status, ['processing', 'completed']);
        }
        
        // Admin can download any deliverable
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can download task file.
     */
    public function downloadTask(User $user, Order $order): bool
    {
        // Buyer (order owner) can download task they uploaded
        if ($order->user_id === $user->id) {
            return true;
        }
        
        // Seller can download task file for their product/service orders
        if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
            return true;
        }
        
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can download any task
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can send messages.
     */
    public function sendMessage(User $user, Order $order): bool
    {
        // Buyer can send messages to their own orders
        if ($order->user_id === $user->id) {
            return true;
        }
        
        // Seller can send messages to orders for their products/services
        if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
            return true;
        }
        
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can send messages to any order
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update progress.
     */
    public function updateProgress(User $user, Order $order): bool
    {
        // Only seller can update progress for their products/services
        if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
            return true;
        }
        
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can update any progress
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can set deadline.
     */
    public function setDeadline(User $user, Order $order): bool
    {
        // Only seller can set deadline for their products/services
        if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
            return true;
        }
        
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can set any deadline
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete deliverable.
     */
    public function deleteDeliverable(User $user, Order $order): bool
    {
        // Only seller can delete deliverable they uploaded
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can delete any deliverable
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update priority.
     */
    public function updatePriority(User $user, Order $order): bool
    {
        // Only seller can update priority for their products/services
        if ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) {
            return true;
        }
        
        if ($order->type === 'service' && $order->service && $order->service->user_id === $user->id) {
            return true;
        }
        
        // Admin can update any priority
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can confirm order completion.
     * Only buyer (order owner) can confirm their order is completed.
     * 🔒 REKBER FLOW: Buyer dapat konfirmasi saat status processing, waiting_confirmation, atau completed dengan escrow holding
     */
    public function confirmCompletion(User $user, Order $order): bool
    {
        // Only buyer (order owner) can confirm completion
        if ($order->user_id === $user->id) {
            // 🔒 REKBER FLOW: Untuk product orders, buyer dapat konfirmasi saat:
            // 1. Status processing (seller sudah kirim produk)
            // 2. Status waiting_confirmation (seller sudah kirim produk)
            // 3. Status completed tapi escrow masih holding (early release)
            if ($order->type === 'product') {
                return in_array($order->status, ['processing', 'waiting_confirmation'])
                    || ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding());
            }
            
            // Untuk service orders, buyer dapat konfirmasi saat:
            // 1. Status waiting_confirmation (seller sudah kirim hasil pekerjaan)
            // 2. Status completed tapi escrow masih holding (early release)
            return $order->status === 'waiting_confirmation' 
                || ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding());
        }
        
        // Admin can also confirm any order
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }
}
