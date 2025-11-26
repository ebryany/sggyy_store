<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\TimelineService;
use App\Services\SettingsService;
use App\Services\FileUploadSecurityService;
use App\Services\SecurityLogger;
use App\Services\SecureFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private TimelineService $timelineService,
        private SettingsService $settingsService,
        private FileUploadSecurityService $fileUploadSecurity,
        private SecureFileService $secureFileService
    ) {
        $this->middleware('auth');
    }
    
    // Note: SellerService is injected in OrderService, not here

    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // If seller, show orders for their products/services
        if ($user->isSeller()) {
            $query = Order::with(['product', 'service', 'payment', 'user'])
                ->where(function ($query) use ($user) {
                    // Group OR conditions to prevent access control bypass
                    $query->whereHas('product', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->orWhereHas('service', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                });
        } else {
            // Regular user sees their own orders
            $query = Order::with(['product', 'service', 'payment', 'rating'])
                ->where('user_id', $user->id);
        }

        // ✅ PHASE 1 FIX: Validate status parameter with whitelist
        if ($request->filled('status')) {
            $validStatuses = ['pending', 'paid', 'processing', 'completed', 'cancelled', 'needs_revision'];
            $status = $request->status;
            
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            } else {
                // Log suspicious attempt
                \Illuminate\Support\Facades\Log::warning('Invalid status parameter in orders.index', [
                    'user_id' => $user->id,
                    'status' => $status,
                    'ip' => $request->ip(),
                ]);
            }
        }

        // Type filter (product or service)
        if ($request->filled('type')) {
            $type = $request->type;
            if (in_array($type, ['product', 'service'])) {
                if ($type === 'product') {
                    $query->whereNotNull('product_id');
                } else {
                    $query->whereNotNull('service_id');
                }
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

        // Search (order number, product/service title)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search);
            $search = mb_substr($search, 0, 100);
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhereHas('product', function ($pq) use ($search) {
                          $pq->where('title', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('service', function ($sq) use ($search) {
                          $sq->where('title', 'like', '%' . $search . '%');
                      });
                });
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'status'];
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
            case 'price_asc':
                $query->orderBy('total', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('total', 'desc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 15);
        $validPerPage = [10, 15, 20, 30, 50];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 15;
        }

        $orders = $query->paginate((int)$perPage)->withQueryString();
        
        // Ensure payment relationship is loaded for all orders
        $orders->loadMissing('payment');

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('view', $order);
        
        $user = auth()->user();
        
        // Helper flags for view logic
        $isOwner = $order->user_id === $user->id;
        $isSeller = $user->isSeller() && (
            ($order->product && $order->product->user_id === $user->id) ||
            ($order->service && $order->service->user_id === $user->id)
        );
        $isAdmin = $user->isAdmin();

        $order->load(['product', 'service', 'payment.verifier', 'rating', 'history.creator', 'escrow']);
        
        // Refresh order to ensure deliverable_path is up-to-date
        $order->refresh();
        
        // Real-time auto-release check: If order is completed and hold period expired, auto-release immediately
        if ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding() && $order->escrow->hold_until && $order->escrow->hold_until <= now()) {
            try {
                $escrowService = app(\App\Services\EscrowService::class);
                $escrowService->autoRelease($order->escrow);
                $order->refresh(); // Refresh to get updated escrow status
                
                \Illuminate\Support\Facades\Log::info('Escrow auto-released in real-time on page load (hold period expired)', [
                    'order_id' => $order->id,
                    'escrow_id' => $order->escrow->id,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to auto-release escrow in real-time on page load', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                // Don't fail, cron will handle it later
            }
        }
        
        // Enhanced timeline with order history
        $timeline = $this->timelineService->getOrderTimeline($order);
        
        // Merge with order history if exists
        if ($order->history->count() > 0) {
            foreach ($order->history as $history) {
                $timeline[] = [
                    'time' => $history->created_at->format('d M Y, H:i'),
                    'label' => 'Status: ' . ucfirst($history->status_to),
                    'status' => $history->status_to === 'completed' ? 'completed' : ($history->status_to === 'cancelled' ? 'cancelled' : 'processing'),
                    'icon' => $history->status_to === 'completed' ? '✅' : ($history->status_to === 'cancelled' ? '❌' : '⚙️'),
                    'description' => $history->notes ?? "Status berubah dari {$history->status_from} ke {$history->status_to}",
                ];
            }
        }
        
        // Sort timeline by time
        usort($timeline, function($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
        });

        // Get bank account info for bank transfer payment
        $bankAccountInfo = null;
        if ($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris'])) {
            $bankAccountInfo = $this->settingsService->getBankAccountInfo();
        }

        $settingsService = app(\App\Services\SettingsService::class);
        $featureFlags = $settingsService->getFeatureFlags();
        
        return view('orders.show', compact('order', 'timeline', 'bankAccountInfo', 'isOwner', 'isSeller', 'isAdmin', 'featureFlags'));
    }

    public function updateStatus(Order $order, \App\Http\Requests\OrderStatusUpdateRequest $request): RedirectResponse
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('update', $order);
        
        $user = auth()->user();

        // ✅ PHASE 2: Validation already done in FormRequest (OrderStatusUpdateRequest)
        // Status transition validation is handled in the request class

        try {
            $createdByType = $user->isAdmin() ? 'admin' : 'seller';
            
            // Log status update
            SecurityLogger::logSecurityEvent('Order status updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $order->status,
                'new_status' => $request->status,
                'updated_by' => $user->id,
                'updated_by_type' => $createdByType,
            ]);
            
            $this->orderService->updateStatus($order, $request->status, $request->notes, $createdByType);

            return back()->with('success', 'Status pesanan berhasil diperbarui');
        } catch (\Exception $e) {
            SecurityLogger::logBusinessLogicViolation('Order status update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function complete(Order $order): RedirectResponse
    {
        // 🔒 SECURITY: Use Policy for authorization  
        $this->authorize('update', $order);

        try {
            $this->orderService->completeOrder($order);

            return back()->with('success', 'Pesanan berhasil diselesaikan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Update order progress (Seller only)
     */
    public function updateProgress(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('updateProgress', $order);
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,gif', 'max:10240'], // Max 10MB
        ]);
        
        $oldProgress = $order->progress;
        $newProgress = $validated['progress'];
        
        // Handle file attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $disk = config('filesystems.default');
            $attachmentPath = $request->file('attachment')->store('orders/progress-attachments', $disk);
        }
        
        $order->update(['progress' => $newProgress]);
        
        // Save progress update record
        \App\Models\OrderProgressUpdate::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'progress_from' => $oldProgress,
            'progress_to' => $newProgress,
            'notes' => $validated['notes'] ?? null,
            'attachment_path' => $attachmentPath,
        ]);
        
        \Illuminate\Support\Facades\Log::info('Order progress updated', [
            'order_id' => $order->id,
            'old_progress' => $oldProgress,
            'new_progress' => $newProgress,
            'updated_by' => $user->id,
        ]);
        
        // Send notification to buyer about progress update
        $milestones = [25, 50, 75, 100];
        $isMilestone = in_array($newProgress, $milestones) && $oldProgress < $newProgress;
        
        if ($isMilestone) {
            // Milestone notification (more prominent)
            $milestoneMessages = [
                25 => "🎯 Progress pesanan #{$order->order_number} mencapai 25%! Pekerjaan sedang berjalan.",
                50 => "⏳ Progress pesanan #{$order->order_number} sudah setengah jalan (50%)! Seller sedang bekerja keras untuk menyelesaikan pesanan Anda.",
                75 => "🚀 Progress pesanan #{$order->order_number} hampir selesai (75%)! Tinggal sedikit lagi.",
                100 => "✅ Progress pesanan #{$order->order_number} sudah 100%! Order akan segera diselesaikan.",
            ];
            
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'message' => $milestoneMessages[$newProgress] ?? "Progress pesanan #{$order->order_number}: {$newProgress}%",
                'type' => 'progress_milestone',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
        } else {
            // Regular progress update notification
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'message' => "📊 Progress pesanan #{$order->order_number} diperbarui: {$newProgress}% ({$oldProgress}% → {$newProgress}%)",
                'type' => 'progress_updated',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
        }
        
        // Check if auto-complete when 100% - use OrderService for consistency
        // ⚠️ IMPORTANT: For service orders, require deliverable upload before auto-completing
        $order->refresh();
        if ($newProgress === 100 && in_array($order->status, ['processing', 'paid'])) {
            // For service orders, require deliverable upload before auto-completing
            if ($order->type === 'service' && !$order->deliverable_path) {
                // Don't auto-complete, but notify seller to upload deliverable
                \App\Models\Notification::create([
                    'user_id' => $order->service->user_id,
                    'message' => "⚠️ Progress pesanan #{$order->order_number} sudah 100%, tapi hasil pekerjaan belum diupload! Silakan upload hasil pekerjaan untuk menyelesaikan order.",
                    'type' => 'deliverable_required',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
                
                // Update milestone message to remind about deliverable
                \App\Models\Notification::where('user_id', $order->user_id)
                    ->where('notifiable_type', \App\Models\Order::class)
                    ->where('notifiable_id', $order->id)
                    ->where('type', 'progress_milestone')
                    ->latest()
                    ->first()
                    ?->update([
                        'message' => "✅ Progress pesanan #{$order->order_number} sudah 100%! Seller akan segera mengupload hasil pekerjaan.",
                    ]);
            } else {
                // For products or services with deliverable, proceed with auto-complete
                try {
                    $createdByType = $user->isAdmin() ? 'admin' : 'seller';
                    $this->orderService->updateStatus(
                        $order, 
                        'completed', 
                        'Progress mencapai 100% - Order otomatis diselesaikan', 
                        $createdByType
                    );
                    
                    // Log successful auto-complete
                    \Illuminate\Support\Facades\Log::info('Order auto-completed due to 100% progress', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'previous_status' => $order->status,
                        'new_status' => 'completed',
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the progress update
                    \Illuminate\Support\Facades\Log::error('Failed to auto-complete order', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'order_status' => $order->status,
                        'order_progress' => $order->progress,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Progress berhasil diupdate',
                'progress' => $validated['progress'],
            ]);
        }
        
        return back()->with('success', 'Progress berhasil diupdate');
    }
    
    /**
     * Set order deadline (Seller/Admin only)
     */
    public function setDeadline(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('setDeadline', $order);
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'deadline_at' => ['required', 'date', 'after:now'],
        ]);
        
        $order->update(['deadline_at' => $validated['deadline_at']]);
        
        \Illuminate\Support\Facades\Log::info('Order deadline set', [
            'order_id' => $order->id,
            'deadline_at' => $validated['deadline_at'],
            'set_by' => $user->id,
        ]);
        
        // Create notification for buyer
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'message' => "Deadline untuk pesanan #{$order->order_number} telah ditetapkan: " . \Carbon\Carbon::parse($validated['deadline_at'])->format('d M Y H:i'),
            'type' => 'deadline_set',
            'is_read' => false,
            'notifiable_type' => \App\Models\Order::class,
            'notifiable_id' => $order->id,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Deadline berhasil ditetapkan',
                'deadline_at' => $validated['deadline_at'],
            ]);
        }
        
        return back()->with('success', 'Deadline berhasil ditetapkan');
    }
    
    /**
     * Upload deliverable file (Seller only)
     */
    public function uploadDeliverable(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('uploadDeliverable', $order);
        
        $user = auth()->user();
        
        // Only allow upload for service orders
        if ($order->type !== 'service') {
            return back()->withErrors(['error' => 'Upload hasil hanya untuk order jasa']);
        }
        
        // Only allow upload for processing or completed orders
        if (!in_array($order->status, ['processing', 'completed'])) {
            return back()->withErrors(['error' => 'Upload hasil hanya bisa dilakukan saat order dalam status processing atau completed']);
        }
        
        $validated = $request->validate([
            'deliverable' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt', 'max:10240'], // Max 10MB
        ], [
            'deliverable.required' => 'File hasil pekerjaan wajib diupload',
            'deliverable.file' => 'File yang diupload tidak valid',
            'deliverable.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, atau TXT',
            'deliverable.max' => 'Ukuran file maksimal 10MB',
        ]);
        
        try {
            // ✅ PHASE 2: Enhanced file validation
            $file = $validated['deliverable'];
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/zip',
                'application/x-rar-compressed',
                'text/plain',
            ];
            
            // Enhanced validation with content scanning
            $validationErrors = $this->fileUploadSecurity->validateFile($file, $allowedMimeTypes, 10240);
            if (!empty($validationErrors)) {
                SecurityLogger::logFileUploadEvent('Deliverable validation failed', [
                    'order_id' => $order->id,
                    'file_name' => $file->getClientOriginalName(),
                    'errors' => $validationErrors,
                ]);
                throw new \Exception(implode(', ', $validationErrors));
            }
            
            // Generate secure filename
            $secureFilename = $this->fileUploadSecurity->generateSecureFilename($file, 'deliverable');
            
            // Log successful validation
            SecurityLogger::logFileUploadEvent('Deliverable upload validated', [
                'order_id' => $order->id,
                'file_name' => $file->getClientOriginalName(),
                'secure_filename' => $secureFilename,
            ]);
            
            // Delete old deliverable if exists
            $disk = config('filesystems.default');
            if ($order->deliverable_path) {
                \Illuminate\Support\Facades\Storage::disk($disk)->delete($order->deliverable_path);
            }
            
            // ✅ PHASE 2: Store with secure filename
            $path = $file->storeAs('orders/deliverables', $secureFilename, $disk);
            
            // Update order with deliverable path
            $order->update(['deliverable_path' => $path]);
            
            // Refresh order to ensure deliverable_path is loaded
            $order = $order->fresh();
            
            // Auto-move to waiting_confirmation status
            if (in_array($order->status, ['processing', 'needs_revision'])) {
                $order = $this->orderService->markAsWaitingConfirmation($order);
            }
            
            // Create notification for buyer (if not already created by markAsWaitingConfirmation)
            if ($order->status !== 'waiting_confirmation') {
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'message' => "📦 Seller telah mengupload hasil pekerjaan untuk pesanan #{$order->order_number}! Silakan cek dan download hasilnya.",
                    'type' => 'deliverable_uploaded',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
            
            \Illuminate\Support\Facades\Log::info('Deliverable uploaded', [
                'order_id' => $order->id,
                'seller_id' => $user->id,
                'file_path' => $path,
            ]);
            
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hasil pekerjaan berhasil diupload! Buyer akan mendapat notifikasi.',
                    'deliverable_path' => $path,
                ]);
            }
            
            return back()->with('success', 'Hasil pekerjaan berhasil diupload! Buyer akan mendapat notifikasi.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Deliverable upload failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload file: ' . $e->getMessage(),
                    'error' => $e->getMessage(),
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Gagal mengupload file: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Download deliverable file (Buyer only, after order completed)
     * 🔒 SECURITY: Enhanced with Policy + SecureFileService (path traversal protection)
     */
    public function downloadDeliverable(Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('downloadDeliverable', $order);
        
        // Only allow download for service orders
        if ($order->type !== 'service') {
            abort(404, 'File hasil pekerjaan hanya tersedia untuk order jasa');
        }
        
        if (!$order->deliverable_path) {
            return back()->withErrors(['error' => 'Hasil pekerjaan belum tersedia']);
        }
        
        try {
            // 🔒 SECURITY: Use SecureFileService for path traversal protection
            return $this->secureFileService->secureDownload(
                $order->deliverable_path,
                'orders/deliverables',
                'public'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Deliverable download failed', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors(['error' => 'Gagal mengunduh file: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Delete deliverable file (Seller/Admin only)
     */
    public function deleteDeliverable(Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('deleteDeliverable', $order);
        
        $user = auth()->user();
        
        // Only allow delete for service orders
        if ($order->type !== 'service') {
            return back()->withErrors(['error' => 'Hapus hasil hanya untuk order jasa']);
        }
        
        if (!$order->deliverable_path) {
            return back()->withErrors(['error' => 'File hasil pekerjaan tidak ada']);
        }
        
        try {
            // Delete file from storage
            $disk = config('filesystems.default');
            if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($order->deliverable_path)) {
                \Illuminate\Support\Facades\Storage::disk($disk)->delete($order->deliverable_path);
            }
            
            // Update order
            $order->update(['deliverable_path' => null]);
            
            // Create notification for buyer
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'message' => "⚠️ Seller telah menghapus hasil pekerjaan untuk pesanan #{$order->order_number}. Silakan hubungi seller untuk informasi lebih lanjut.",
                'type' => 'deliverable_deleted',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            \Illuminate\Support\Facades\Log::info('Deliverable deleted', [
                'order_id' => $order->id,
                'seller_id' => $user->id,
            ]);
            
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hasil pekerjaan berhasil dihapus',
                ]);
            }
            
            return back()->with('success', 'Hasil pekerjaan berhasil dihapus');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Deliverable delete failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus file: ' . $e->getMessage(),
                    'error' => $e->getMessage(),
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Gagal menghapus file: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Download task file uploaded by buyer (Seller/Admin only)
     * 🔒 SECURITY: Enhanced with Policy + SecureFileService (path traversal protection)
     */
    public function downloadTask(Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('downloadTask', $order);
        
        // Only allow download for service orders
        if ($order->type !== 'service') {
            abort(404, 'File tugas hanya tersedia untuk order jasa');
        }
        
        if (!$order->task_file_path) {
            return back()->withErrors(['error' => 'File tugas belum tersedia']);
        }
        
        try {
            // 🔒 SECURITY: Use SecureFileService for path traversal protection
            return $this->secureFileService->secureDownload(
                $order->task_file_path,
                'orders/tasks',
                'public'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Task file download failed', [
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors(['error' => 'Gagal mengunduh file: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Request revision (Buyer only, for completed service orders)
     */
    public function requestRevision(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('requestRevision', $order);
        
        $user = auth()->user();
        
        // Only allow revision for service orders in waiting_confirmation status
        if ($order->type !== 'service') {
            return response()->json(['success' => false, 'message' => 'Revision hanya untuk order jasa'], 400);
        }
        
        if ($order->status !== 'waiting_confirmation') {
            return response()->json(['success' => false, 'message' => 'Revision hanya bisa diminta saat order menunggu konfirmasi'], 400);
        }
        
        $validated = $request->validate([
            'revision_notes' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'revision_notes.required' => 'Catatan revisi wajib diisi',
            'revision_notes.min' => 'Catatan revisi minimal 10 karakter',
            'revision_notes.max' => 'Catatan revisi maksimal 2000 karakter',
        ]);
        
        try {
            // Use OrderService method for consistency
            $order = $this->orderService->requestRevision($order, $validated['revision_notes']);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan revisi berhasil dikirim. Seller akan segera memproses.',
                    'revision_count' => $order->revision_count,
                ]);
            }
            
            return back()->with('success', 'Permintaan revisi berhasil dikirim. Seller akan segera memproses.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Revision request failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim permintaan revisi: ' . $e->getMessage(),
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal mengirim permintaan revisi: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Send message in order (Buyer and Seller)
     */
    public function sendMessage(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('sendMessage', $order);
        
        $user = auth()->user();
        
        // Helper flags for notification logic
        $isBuyer = $order->user_id === $user->id;
        $isSeller = ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) ||
                    ($order->type === 'service' && $order->service && $order->service->user_id === $user->id);
        
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,gif', 'max:10240'], // Max 10MB
        ], [
            'message.required' => 'Pesan wajib diisi',
            'message.min' => 'Pesan minimal 1 karakter',
            'message.max' => 'Pesan maksimal 2000 karakter',
        ]);
        
        try {
            // Handle file attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $disk = config('filesystems.default');
                $attachmentPath = $request->file('attachment')->store('orders/message-attachments', $disk);
            }
            
            // Create message
            $message = \App\Models\OrderMessage::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'message' => $validated['message'],
                'attachment_path' => $attachmentPath,
            ]);
            
            // Notify the other party (buyer notifies seller, seller notifies buyer)
            $notifyUserId = $isBuyer ? ($order->service?->user_id ?? $order->product?->user_id) : $order->user_id;
            
            if ($notifyUserId) {
                \App\Models\Notification::create([
                    'user_id' => $notifyUserId,
                    'message' => "💬 Pesan baru untuk pesanan #{$order->order_number} dari " . ($isBuyer ? 'buyer' : 'seller'),
                    'type' => 'order_message',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
            
            \Illuminate\Support\Facades\Log::info('Order message sent', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'message_id' => $message->id,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim',
                    'message_data' => $message->load('user'),
                ]);
            }
            
            return back()->with('success', 'Pesan berhasil dikirim');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order message failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesan: ' . $e->getMessage(),
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal mengirim pesan: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Update order priority (Seller/Admin only)
     */
    /**
     * Seller accepts order (paid → accepted → processing)
     */
    public function acceptOrder(Order $order): RedirectResponse
    {
        $this->authorize('update', $order);
        
        try {
            $this->orderService->acceptOrder($order);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diterima dan status diupdate ke processing',
                ]);
            }
            
            return back()->with('success', 'Pesanan berhasil diterima!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Seller rejects order (paid → cancelled with refund)
     */
    public function rejectOrder(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);
        
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        try {
            $this->orderService->rejectOrder($order, $validated['reason']);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil ditolak dan dana dikembalikan ke buyer',
                ]);
            }
            
            return back()->with('success', 'Pesanan berhasil ditolak. Dana akan dikembalikan ke buyer.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Buyer confirms order completion (waiting_confirmation → completed)
     * Now supports optional rating during confirmation
     */
    public function confirmCompletion(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);
        
        $validated = $request->validate([
            'rating' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:5'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);
        
        try {
            // Prepare rating data if provided
            $ratingData = null;
            if (!empty($validated['rating']) && $validated['rating'] >= 1 && $validated['rating'] <= 5) {
                $ratingData = [
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'] ?? null,
                ];
            }
            
            // Confirm completion (includes early release escrow and rating creation)
            $this->orderService->confirmCompletion($order, $ratingData);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dikonfirmasi selesai' . (!empty($validated['rating']) ? ' dan rating berhasil diberikan' : ''),
                ]);
            }
            
            return back()->with('success', 'Pesanan berhasil dikonfirmasi selesai' . (!empty($validated['rating']) ? ' dan rating berhasil diberikan' : '') . '!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Cancel order with refund rules
     */
    public function cancelOrder(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);
        
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);
        
        try {
            $this->orderService->cancelOrder($order, $validated['reason']);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan',
                ]);
            }
            
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 400);
            }
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    public function updatePriority(Request $request, Order $order)
    {
        // 🔒 SECURITY: Use Policy for authorization
        $this->authorize('updatePriority', $order);
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'priority' => ['required', 'in:low,normal,high,urgent'],
        ]);
        
        try {
            $oldPriority = $order->priority;
            $order->update(['priority' => $validated['priority']]);
            
            // Notify buyer if priority is urgent
            if ($validated['priority'] === 'urgent') {
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'message' => "🚨 Prioritas pesanan #{$order->order_number} diubah menjadi URGENT!",
                    'type' => 'priority_updated',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
            
            \Illuminate\Support\Facades\Log::info('Order priority updated', [
                'order_id' => $order->id,
                'old_priority' => $oldPriority,
                'new_priority' => $validated['priority'],
                'updated_by' => $user->id,
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prioritas berhasil diupdate',
                    'priority' => $validated['priority'],
                ]);
            }
            
            return back()->with('success', 'Prioritas berhasil diupdate');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Priority update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate prioritas: ' . $e->getMessage(),
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal mengupdate prioritas: ' . $e->getMessage()]);
        }
    }
}
