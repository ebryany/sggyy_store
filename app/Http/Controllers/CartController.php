<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        // Cart can be accessed by guests, but checkout requires auth
    }

    public function index()
    {
        $cartItems = $this->getCartItems();
        
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'id' => ['required', 'integer'],
        ]);

        $cart = Session::get('cart', []);
        $key = $validated['type'] . '_' . $validated['id'];

        // Check if item already in cart
        if (isset($cart[$key])) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item sudah ada di keranjang',
                    'error' => 'Item sudah ada di keranjang'
                ]);
            }
            return back()->with('info', 'Item sudah ada di keranjang');
        }

        // Add to cart
        $cart[$key] = [
            'type' => $validated['type'],
            'id' => $validated['id'],
            'added_at' => now()->timestamp,
        ];

        Session::put('cart', $cart);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang',
                'cart_count' => count($cart)
            ]);
        }

        return back()->with('success', 'Item berhasil ditambahkan ke keranjang');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'id' => ['required', 'integer'],
        ]);

        $cart = Session::get('cart', []);
        $key = $validated['type'] . '_' . $validated['id'];

        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put('cart', $cart);
            
            return back()->with('success', 'Item berhasil dihapus dari keranjang');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang');
    }

    public function clear()
    {
        Session::forget('cart');
        
        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    /**
     * Get cart items count
     */
    public static function getCartCount(): int
    {
        $cart = Session::get('cart', []);
        return count($cart);
    }

    /**
     * Get cart items with full details
     */
    private function getCartItems(): array
    {
        $cart = Session::get('cart', []);
        $items = [];

        foreach ($cart as $key => $item) {
            if ($item['type'] === 'product') {
                $product = Product::find($item['id']);
                if ($product) {
                    $items[] = [
                        'type' => 'product',
                        'data' => $product,
                        'key' => $key,
                    ];
                }
            } elseif ($item['type'] === 'service') {
                $service = Service::find($item['id']);
                if ($service) {
                    $items[] = [
                        'type' => 'service',
                        'data' => $service,
                        'key' => $key,
                    ];
                }
            }
        }

        return $items;
    }
}
