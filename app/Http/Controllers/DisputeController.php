<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Escrow;
use App\Services\EscrowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DisputeController extends Controller
{
    public function __construct(
        private EscrowService $escrowService
    ) {
        $this->middleware('auth');
    }

    /**
     * Show dispute form for an order
     * 
     * GET /orders/{order}/dispute
     */
    public function create(Order $order): View
    {
        $user = auth()->user();
        
        // Authorization: Only buyer or seller can create dispute
        $isBuyer = $order->user_id === $user->id;
        $isSeller = ($order->product && $order->product->user_id === $user->id) 
                 || ($order->service && $order->service->user_id === $user->id);
        
        if (!$isBuyer && !$isSeller) {
            abort(403, 'Anda tidak memiliki akses untuk dispute order ini');
        }

        // Check if escrow exists and can be disputed
        if (!$order->escrow) {
            return redirect()->route('orders.show', $order)
                ->withErrors(['error' => 'Order ini tidak memiliki escrow untuk di-dispute']);
        }

        if (!$order->escrow->canBeDisputed()) {
            return redirect()->route('orders.show', $order)
                ->withErrors(['error' => 'Escrow tidak dapat di-dispute. Status: ' . $order->escrow->status]);
        }

        return view('disputes.create', compact('order', 'isBuyer', 'isSeller'));
    }

    /**
     * Store dispute
     * 
     * POST /orders/{order}/dispute
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        $user = auth()->user();
        
        // Authorization
        $isBuyer = $order->user_id === $user->id;
        $isSeller = ($order->product && $order->product->user_id === $user->id) 
                 || ($order->service && $order->service->user_id === $user->id);
        
        if (!$isBuyer && !$isSeller) {
            abort(403, 'Anda tidak memiliki akses untuk dispute order ini');
        }

        // Validation
        $validated = $request->validate([
            'category' => ['required', 'in:product_not_as_described,quality_issue,seller_unresponsive,delivery_issue,other'],
            'reason' => ['required', 'string', 'min:50', 'max:1000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // Max 5MB per file
        ], [
            'category.required' => 'Kategori dispute wajib dipilih',
            'reason.required' => 'Alasan dispute wajib diisi',
            'reason.min' => 'Alasan dispute minimal 50 karakter',
            'reason.max' => 'Alasan dispute maksimal 1000 karakter',
            'attachments.max' => 'Maksimal 5 file attachment',
            'attachments.*.file' => 'File tidak valid',
            'attachments.*.mimes' => 'Format file harus: JPG, PNG, atau PDF',
            'attachments.*.max' => 'Ukuran file maksimal 5MB',
        ]);

        // Check escrow
        if (!$order->escrow) {
            return back()->withErrors(['error' => 'Order ini tidak memiliki escrow untuk di-dispute']);
        }

        if (!$order->escrow->canBeDisputed()) {
            return back()->withErrors(['error' => 'Escrow tidak dapat di-dispute. Status: ' . $order->escrow->status]);
        }

        try {
            DB::beginTransaction();

            // Handle file attachments
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                $disk = config('filesystems.default');
                foreach ($request->file('attachments') as $file) {
                    $attachmentPaths[] = $file->store('disputes/attachments', $disk);
                }
            }

            // Build dispute reason with category
            $categoryLabels = [
                'product_not_as_described' => 'Produk tidak sesuai deskripsi',
                'quality_issue' => 'Kualitas produk/jasa tidak sesuai',
                'seller_unresponsive' => 'Seller tidak responsif',
                'delivery_issue' => 'Masalah pengiriman',
                'other' => 'Lainnya',
            ];

            $fullReason = "[{$categoryLabels[$validated['category']]}] " . $validated['reason'];
            if (!empty($attachmentPaths)) {
                $fullReason .= "\n\n[Lampiran: " . count($attachmentPaths) . " file]";
            }

            // Create dispute
            $this->escrowService->disputeEscrow($order->escrow, $fullReason, $user->id);

            // Store attachment paths (if needed in future)
            if (!empty($attachmentPaths)) {
                // TODO: Create DisputeAttachment model if needed
                // For now, we store in escrow metadata or create separate table
            }

            // Create notifications
            $otherUserId = $isBuyer 
                ? ($order->product?->user_id ?? $order->service?->user_id)
                : $order->user_id;

            if ($otherUserId) {
                \App\Models\Notification::create([
                    'user_id' => $otherUserId,
                    'message' => "⚠️ Dispute dibuat untuk pesanan #{$order->order_number}. Admin akan meninjau dispute ini.",
                    'type' => 'escrow_disputed',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }

            // Notify admin
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'message' => "🚨 Dispute baru untuk pesanan #{$order->order_number}. Segera tinjau dan selesaikan.",
                    'type' => 'admin_dispute_created',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }

            DB::commit();

            Log::info('Dispute created via UI', [
                'order_id' => $order->id,
                'escrow_id' => $order->escrow->id,
                'disputed_by' => $user->id,
                'category' => $validated['category'],
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Dispute berhasil dibuat. Admin akan meninjau dan menyelesaikan dispute ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create dispute', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal membuat dispute: ' . $e->getMessage()]);
        }
    }

    /**
     * List user's disputes
     * 
     * GET /disputes
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get orders with disputed escrows
        $disputedOrders = Order::whereHas('escrow', function ($query) {
                $query->where('is_disputed', true)
                      ->where('status', 'disputed');
            })
            ->where(function ($query) use ($user) {
                // Buyer's orders
                $query->where('user_id', $user->id)
                      // Or seller's orders
                      ->orWhereHas('product', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      })
                      ->orWhereHas('service', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->with(['escrow', 'product', 'service'])
            ->latest()
            ->paginate(15);

        return view('disputes.index', compact('disputedOrders'));
    }
}

