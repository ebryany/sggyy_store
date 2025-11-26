<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends BaseApiController
{
    /**
     * Get cart items
     * 
     * GET /api/v1/cart
     */
    public function index(Request $request)
    {
        $cartItems = $this->getCartItems();
        
        return $this->success([
            'items' => $cartItems,
            'total_items' => count($cartItems),
            'subtotal' => array_sum(array_column($cartItems, 'price')),
        ]);
    }

    /**
     * Add item to cart
     * 
     * POST /api/v1/cart/items
     */
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'id' => ['required', 'integer'],
        ]);

        $cart = Session::get('cart', []);
        $itemUuid = $this->generateItemUuid($validated['type'], $validated['id']);
        $key = $validated['type'] . '_' . $validated['id'];

        // Check if item already in cart
        if (isset($cart[$key])) {
            return $this->error('Item already in cart', [], 'DUPLICATE_ITEM', 400);
        }

        // Verify item exists
        $item = $this->findItem($validated['type'], $validated['id']);
        if (!$item) {
            return $this->notFound(ucfirst($validated['type']));
        }

        // Add to cart
        $cart[$key] = [
            'uuid' => $itemUuid,
            'type' => $validated['type'],
            'id' => $validated['id'],
            'added_at' => now()->timestamp,
        ];

        Session::put('cart', $cart);

        return $this->created([
            'item' => $this->formatCartItem($cart[$key]),
            'cart_count' => count($cart),
        ], 'Item added to cart successfully');
    }

    /**
     * Update cart item (e.g., quantity if supported)
     * 
     * PATCH /api/v1/cart/items/{item_uuid}
     */
    public function updateItem(Request $request, string $itemUuid)
    {
        // For now, cart items don't have quantity
        // This endpoint is here for future extensibility
        return $this->error('Update not supported', [], 'NOT_IMPLEMENTED', 501);
    }

    /**
     * Remove item from cart
     * 
     * DELETE /api/v1/cart/items/{item_uuid}
     */
    public function removeItem(string $itemUuid)
    {
        $cart = Session::get('cart', []);
        
        // Find item by UUID
        $key = null;
        foreach ($cart as $k => $item) {
            if (($item['uuid'] ?? null) === $itemUuid) {
                $key = $k;
                break;
            }
        }

        if (!$key) {
            return $this->notFound('Cart item');
        }

        unset($cart[$key]);
        Session::put('cart', $cart);

        return $this->success(null, 'Item removed from cart successfully');
    }

    /**
     * Get cart items with full details
     */
    private function getCartItems(): array
    {
        $cart = Session::get('cart', []);
        $items = [];

        foreach ($cart as $key => $cartItem) {
            $item = $this->findItem($cartItem['type'], $cartItem['id']);
            
            if ($item) {
                $items[] = $this->formatCartItem($cartItem, $item);
            }
        }

        return $items;
    }

    /**
     * Find product or service by type and ID
     */
    private function findItem(string $type, int $id)
    {
        if ($type === 'product') {
            return Product::where('id', $id)
                ->where('is_active', true)
                ->first();
        }
        
        return Service::where('id', $id)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Format cart item for response
     */
    private function formatCartItem(array $cartItem, $item = null): array
    {
        if (!$item) {
            $item = $this->findItem($cartItem['type'], $cartItem['id']);
        }

        if (!$item) {
            return [];
        }

        return [
            'uuid' => $cartItem['uuid'] ?? $this->generateItemUuid($cartItem['type'], $cartItem['id']),
            'type' => $cartItem['type'],
            'id' => $cartItem['id'],
            'title' => $item->title,
            'price' => (float) ($item->current_price ?? $item->price),
            'image' => $item->image ? asset('storage/' . $item->image) : null,
            'slug' => $item->slug,
            'seller' => [
                'id' => $item->user->id,
                'name' => $item->user->name,
                'store_slug' => $item->user->store_slug,
            ],
            'added_at' => $cartItem['added_at'],
        ];
    }

    /**
     * Generate UUID for cart item
     */
    private function generateItemUuid(string $type, int $id): string
    {
        return md5($type . '_' . $id);
    }
}

