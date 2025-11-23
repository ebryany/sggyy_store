<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerWithdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminWithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = SellerWithdrawal::with(['seller'])
            ->latest();

        // ✅ PHASE 1 FIX: Validate status parameter with whitelist
        if ($request->filled('status')) {
            $validStatuses = ['pending', 'processing', 'completed', 'rejected'];
            $status = $request->status;
            
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            } else {
                \Illuminate\Support\Facades\Log::warning('Invalid status parameter in admin.withdrawals.index', [
                    'user_id' => auth()->id(),
                    'status' => $status,
                    'ip' => $request->ip(),
                ]);
            }
        }

        // ✅ PHASE 1 FIX: Validate method parameter with whitelist
        if ($request->filled('method')) {
            $validMethods = ['bank_transfer', 'e_wallet'];
            $method = $request->method;
            
            if (in_array($method, $validMethods)) {
                $query->where('method', $method);
            } else {
                \Illuminate\Support\Facades\Log::warning('Invalid method parameter in admin.withdrawals.index', [
                    'user_id' => auth()->id(),
                    'method' => $method,
                    'ip' => $request->ip(),
                ]);
            }
        }

        // ✅ PHASE 1 FIX: Sanitize search parameter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search); // Remove HTML tags
            $search = preg_replace('/[^\p{L}\p{N}\s\-_@.]/u', '', $search); // Allow letters, numbers, spaces, hyphens, underscores, @, dots (for email)
            $search = mb_substr($search, 0, 100); // Limit length
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhereHas('seller', function ($sellerQuery) use ($search) {
                          $sellerQuery->where('name', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $dateFrom = $request->date_from;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->date_to;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        }

        // Amount range filter
        if ($request->filled('min_amount')) {
            $minAmount = filter_var($request->min_amount, FILTER_VALIDATE_FLOAT);
            if ($minAmount !== false && $minAmount >= 0) {
                $query->where('amount', '>=', $minAmount);
            }
        }

        if ($request->filled('max_amount')) {
            $maxAmount = filter_var($request->max_amount, FILTER_VALIDATE_FLOAT);
            if ($maxAmount !== false && $maxAmount >= 0) {
                $query->where('amount', '<=', $maxAmount);
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'amount_asc', 'amount_desc', 'status'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 20);
        $validPerPage = [10, 15, 20, 30, 50];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 20;
        }

        $withdrawals = $query->paginate((int)$perPage)->withQueryString();

        // Statistics
        $stats = [
            'total' => SellerWithdrawal::count(),
            'pending' => SellerWithdrawal::where('status', 'pending')->count(),
            'processing' => SellerWithdrawal::where('status', 'processing')->count(),
            'completed' => SellerWithdrawal::where('status', 'completed')->count(),
            'rejected' => SellerWithdrawal::where('status', 'rejected')->count(),
            'total_amount_pending' => SellerWithdrawal::where('status', 'pending')->sum('amount'),
            'total_amount_processing' => SellerWithdrawal::where('status', 'processing')->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    public function show(SellerWithdrawal $withdrawal): View
    {
        $withdrawal->load(['seller', 'processor']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Request $request, SellerWithdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== 'pending') {
            return back()->withErrors(['error' => 'Withdrawal sudah diproses']);
        }

        try {
            return DB::transaction(function () use ($withdrawal, $request) {
                $withdrawal->update([
                    'status' => 'processing',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                Log::info('Withdrawal approved by admin', [
                    'withdrawal_id' => $withdrawal->id,
                    'seller_id' => $withdrawal->seller_id,
                    'amount' => $withdrawal->amount,
                    'admin_id' => auth()->id(),
                ]);

                // Create notification for seller
                \App\Models\Notification::create([
                    'user_id' => $withdrawal->seller_id,
                    'message' => "✅ Permintaan penarikan saldo #{$withdrawal->reference_number} (Rp " . number_format($withdrawal->amount, 0, ',', '.') . ") telah disetujui dan sedang diproses.",
                    'type' => 'withdrawal_approved',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\SellerWithdrawal::class,
                    'notifiable_id' => $withdrawal->id,
                ]);

                return back()->with('success', 'Withdrawal berhasil disetujui');
            });
        } catch (\Exception $e) {
            Log::error('Failed to approve withdrawal', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal menyetujui withdrawal: ' . $e->getMessage()]);
        }
    }

    public function complete(Request $request, SellerWithdrawal $withdrawal): RedirectResponse
    {
        if (!in_array($withdrawal->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Withdrawal tidak dapat diselesaikan']);
        }

        try {
            return DB::transaction(function () use ($withdrawal, $request) {
                $withdrawal->update([
                    'status' => 'completed',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                Log::info('Withdrawal completed by admin', [
                    'withdrawal_id' => $withdrawal->id,
                    'seller_id' => $withdrawal->seller_id,
                    'amount' => $withdrawal->amount,
                    'admin_id' => auth()->id(),
                ]);

                // Create notification for seller
                \App\Models\Notification::create([
                    'user_id' => $withdrawal->seller_id,
                    'message' => "✅ Penarikan saldo #{$withdrawal->reference_number} (Rp " . number_format($withdrawal->amount, 0, ',', '.') . ") telah selesai diproses. Saldo telah ditransfer.",
                    'type' => 'withdrawal_completed',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\SellerWithdrawal::class,
                    'notifiable_id' => $withdrawal->id,
                ]);

                return back()->with('success', 'Withdrawal berhasil diselesaikan');
            });
        } catch (\Exception $e) {
            Log::error('Failed to complete withdrawal', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal menyelesaikan withdrawal: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, SellerWithdrawal $withdrawal): RedirectResponse
    {
        if (!in_array($withdrawal->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Withdrawal tidak dapat ditolak']);
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter',
        ]);

        try {
            return DB::transaction(function () use ($withdrawal, $validated) {
                // Revert earnings back to available status
                // Find earnings that were marked as withdrawn around the time this withdrawal was created
                // We use a 5-minute window to find earnings that were likely used for this withdrawal
                $seller = $withdrawal->seller;
                $withdrawalCreatedAt = $withdrawal->created_at;
                
                // Find earnings that were updated to 'withdrawn' around the withdrawal creation time
                // This assumes earnings were marked withdrawn when withdrawal was created
                $earningsToRevert = $seller->sellerEarnings()
                    ->where('status', 'withdrawn')
                    ->whereBetween('updated_at', [
                        $withdrawalCreatedAt->copy()->subMinutes(5),
                        $withdrawalCreatedAt->copy()->addMinutes(5)
                    ])
                    ->orderBy('updated_at', 'desc')
                    ->lockForUpdate()
                    ->get();
                
                // Revert earnings until we've covered the withdrawal amount
                $remainingAmount = $withdrawal->amount;
                $revertedEarnings = [];
                
                foreach ($earningsToRevert as $earning) {
                    if ($remainingAmount <= 0) {
                        break;
                    }
                    
                    // Revert this earning back to available
                    $earning->update(['status' => 'available']);
                    $revertedEarnings[] = $earning->id;
                    $remainingAmount -= $earning->amount;
                }
                
                // If we couldn't revert enough, log a warning but continue
                if ($remainingAmount > 0) {
                    Log::warning('Could not revert all earnings for withdrawal rejection', [
                        'withdrawal_id' => $withdrawal->id,
                        'withdrawal_amount' => $withdrawal->amount,
                        'remaining_amount' => $remainingAmount,
                        'reverted_earnings' => $revertedEarnings,
                    ]);
                }
                
                // Mark withdrawal as rejected
                $withdrawal->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                Log::info('Withdrawal rejected by admin - earnings reverted', [
                    'withdrawal_id' => $withdrawal->id,
                    'seller_id' => $withdrawal->seller_id,
                    'amount' => $withdrawal->amount,
                    'admin_id' => auth()->id(),
                    'reason' => $validated['rejection_reason'],
                    'reverted_earnings' => $revertedEarnings,
                    'reverted_count' => count($revertedEarnings),
                ]);

                // Create notification for seller
                \App\Models\Notification::create([
                    'user_id' => $withdrawal->seller_id,
                    'message' => "❌ Permintaan penarikan saldo #{$withdrawal->reference_number} (Rp " . number_format($withdrawal->amount, 0, ',', '.') . ") ditolak. Alasan: " . Str::limit($validated['rejection_reason'], 100),
                    'type' => 'withdrawal_rejected',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\SellerWithdrawal::class,
                    'notifiable_id' => $withdrawal->id,
                ]);

                return back()->with('success', 'Withdrawal berhasil ditolak');
            });
        } catch (\Exception $e) {
            Log::error('Failed to reject withdrawal', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal menolak withdrawal: ' . $e->getMessage()]);
        }
    }
}

