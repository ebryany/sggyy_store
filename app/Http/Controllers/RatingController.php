<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Order;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RatingController extends Controller
{
    public function __construct(
        private RatingService $ratingService
    ) {
        $this->middleware('auth');
    }

    public function create(Order $order): View
    {
        // ✅ PHASE 1 FIX: Authorization check using Policy
        $this->authorize('create', $order);

        return view('ratings.create', compact('order'));
    }

    public function store(RatingRequest $request): RedirectResponse
    {
        try {
            $order = Order::findOrFail($request->order_id);

            // Authorization: only order owner can rate
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            $rating = $this->ratingService->createRating($order, $request->validated());

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Rating berhasil diberikan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Rating $rating, Request $request): RedirectResponse
    {
        // ✅ PHASE 1 FIX: Authorization check using Policy
        $this->authorize('update', $rating);

        $request->validate([
            'rating' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->ratingService->updateRating($rating, $request->validated());

            return back()->with('success', 'Rating berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Rating $rating): RedirectResponse
    {
        // ✅ PHASE 1 FIX: Authorization check using Policy
        $this->authorize('delete', $rating);

        try {
            $this->ratingService->deleteRating($rating);

            return back()->with('success', 'Rating berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
