<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;

class TimelineService
{
    /**
     * Get order timeline based on order status and events
     * 
     * @param Order $order
     * @return array<int, array{time: string, label: string, status: string, icon: string, description: string}>
     */
    public function getOrderTimeline(Order $order): array
    {
        $timeline = [];

        // 1. Order Created
        $timeline[] = [
            'time' => $order->created_at->format('d-m-Y H:i'),
            'label' => 'Pesanan Dibuat',
            'status' => 'completed',
            'icon' => 'document',
            'description' => "Pesanan #{$order->order_number} berhasil dibuat",
        ];

        // 2. Payment Status
        if ($order->payment) {
            $paymentStatus = $order->payment->status;
            $paymentTime = $order->payment->created_at->format('d M Y, H:i');
            
            switch ($paymentStatus) {
                case 'pending':
                    $timeline[] = [
                        'time' => $paymentTime,
                        'label' => 'Menunggu Pembayaran',
                        'status' => 'pending',
                        'icon' => '⏳',
                        'description' => "Pembayaran melalui {$order->payment->getMethodDisplayName()} menunggu konfirmasi",
                    ];
                    break;
                    
                case 'verified':
                    $timeline[] = [
                        'time' => $order->payment->verified_at 
                            ? $order->payment->verified_at->format('d-m-Y H:i')
                            : $order->payment->created_at->format('d-m-Y H:i'),
                        'label' => 'Pesanan Dibayarkan',
                        'status' => 'completed',
                        'icon' => 'money',
                        'amount' => 'Rp' . number_format($order->total, 0, ',', '.'),
                        'description' => "Pembayaran melalui {$order->payment->getMethodDisplayName()} telah dikonfirmasi",
                    ];
                    break;
                    
                case 'rejected':
                    $timeline[] = [
                        'time' => $paymentTime,
                        'label' => 'Pembayaran Ditolak',
                        'status' => 'rejected',
                        'icon' => '❌',
                        'description' => $order->payment->rejection_reason ?? 'Pembayaran ditolak',
                    ];
                    break;
            }
        }

        // 3. Task File Uploaded (for services) - HARUS SETELAH Pesanan Dibuat, SEBELUM Pesanan Dibayarkan
        if ($order->task_file_path) {
            // Use created_at for task file (uploaded during checkout)
            $timeline[] = [
                'time' => $order->created_at->format('d-m-Y H:i'),
                'label' => 'File Tugas Diterima',
                'status' => 'completed',
                'icon' => '📎',
                'description' => 'Buyer telah mengupload file tugas. Seller dapat mulai mengerjakan.',
            ];
        }

        // 4. Progress Updates (for services) - HARUS SETELAH Pesanan Dibayarkan, SEBELUM Pesanan Dikirimkan
        if ($order->type === 'service') {
            $progressUpdates = $order->progressUpdates()->orderBy('created_at', 'asc')->get();
            
            // Show ALL progress milestones (25, 50, 75, 100) in chronological order
            $milestones = [25, 50, 75, 100];
            
            foreach ($progressUpdates as $update) {
                if (in_array($update->progress_to, $milestones)) {
                $timeline[] = [
                        'time' => $update->created_at->format('d-m-Y H:i'),
                        'label' => "Progress: {$update->progress_to}%",
                        'status' => $update->progress_to === 100 ? 'completed' : 'processing',
                    'icon' => '📊',
                        'description' => $this->getProgressDescription($update->progress_to),
                ];
                }
            }
        }

        // 5. Deliverable Available (for services) - HARUS SETELAH Progress 100%, SEBELUM Pesanan Dikirimkan
        if ($order->deliverable_path) {
            // Use delivered_at if available, otherwise use updated_at
            $deliverableTime = $order->delivered_at ?? $order->updated_at;
            $timeline[] = [
                'time' => $deliverableTime->format('d-m-Y H:i'),
                'label' => 'File Hasil Tersedia',
                'status' => 'completed',
                'icon' => '📦',
                'description' => 'Seller telah mengupload hasil pekerjaan. File dapat diunduh.',
            ];
        }

        // 6. Revision Request (if any)
        if ($order->needs_revision) {
            $timeline[] = [
                'time' => $order->updated_at->format('d-m-Y H:i'),
                'label' => 'Revisi Diminta',
                'status' => 'pending',
                'icon' => '🔄',
                'description' => $order->revision_notes ?? 'Buyer meminta revisi',
            ];
        }

        // 7. Order Status Changes - REKBER FLOW
        // Untuk product orders: pending → paid → processing → waiting_confirmation → completed
        // Untuk service orders: pending → paid → processing → waiting_confirmation → completed
        
        // 🔒 REKBER FLOW: Hanya tampilkan step penting di timeline, bukan semua status internal
        // Skip status 'pending' dan 'paid' karena sudah ditangani di payment section
        if ($order->status !== 'pending' && $order->status !== 'paid') {
            $timeValue = $order->updated_at;
            
            // "Pesanan Dikirimkan" - untuk product: saat seller kirim produk, untuk service: saat deliverable diupload
            if (in_array($order->status, ['processing', 'waiting_confirmation'])) {
                // Gunakan waktu dari order history jika ada, atau updated_at
                $historyEntry = $order->history()
                    ->whereIn('status_to', ['processing', 'waiting_confirmation'])
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $timeValue = $historyEntry ? $historyEntry->created_at : $order->updated_at;
                
                // Untuk service: "Pesanan Dikirimkan" muncul saat waiting_confirmation (setelah deliverable diupload)
                // Untuk product: "Pesanan Dikirimkan" muncul saat processing atau waiting_confirmation
                if ($order->type === 'service' && $order->status === 'waiting_confirmation') {
                    $timeline[] = [
                        'time' => $timeValue->format('d-m-Y H:i'),
                        'label' => 'Pesanan Dikirimkan',
                        'status' => 'completed',
                        'icon' => 'truck',
                        'description' => 'Seller telah mengirim hasil pekerjaan. Buyer dapat review dan konfirmasi.',
                    ];
                } elseif ($order->type === 'product' && in_array($order->status, ['processing', 'waiting_confirmation'])) {
                    $timeline[] = [
                        'time' => $timeValue->format('d-m-Y H:i'),
                        'label' => 'Pesanan Dikirimkan',
                        'status' => 'completed',
                        'icon' => 'truck',
                        'description' => 'Seller telah mengirim produk. File dapat diunduh.',
                    ];
                }
            }
            
            // "Pesanan Selesai" - setelah buyer konfirmasi (status completed)
            if ($order->status === 'completed') {
                $timeValue = $order->completed_at ?? $order->updated_at;
                
                // Gunakan waktu dari order history jika ada
                $historyEntry = $order->history()
                    ->where('status_to', 'completed')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($historyEntry) {
                    $timeValue = $historyEntry->created_at;
                }
                
                $timeline[] = [
                    'time' => $timeValue->format('d-m-Y H:i'),
                    'label' => 'Pesanan Selesai',
                    'status' => 'completed',
                    'icon' => 'box',
                    'description' => 'Buyer telah mengkonfirmasi pesanan selesai. Dana rekber akan dilepas ke seller.',
                ];
            }
        }
        
        // 8. Rating step (setelah completed)
        if ($order->status === 'completed' && $order->canBeRated() && !$order->rating) {
            $timeline[] = [
                'time' => ($order->completed_at ?? $order->updated_at)->format('d-m-Y H:i'),
                'label' => 'Belum Dinilai',
                'status' => 'pending',
                'icon' => 'star',
                'description' => 'Beri rating untuk pesanan ini',
            ];
        }

        // 9. Rating step if already rated
        if ($order->rating) {
            $timeline[] = [
                'time' => $order->rating->created_at->format('d-m-Y H:i'),
                'label' => 'Sudah Dinilai',
                'status' => 'completed',
                'icon' => 'star',
                'description' => "Rating: {$order->rating->rating}/5 bintang",
            ];
        }

        // 🔒 REKBER FLOW: Sort timeline by time (oldest first)
        // Handle empty time strings (for pending steps like rating) - put them at the end
        usort($timeline, function($a, $b) {
            $timeA = !empty($a['time']) ? strtotime($a['time']) : PHP_INT_MAX;
            $timeB = !empty($b['time']) ? strtotime($b['time']) : PHP_INT_MAX;
            return $timeA - $timeB;
        });

        return $timeline;
    }

    /**
     * Get status description
     */
    private function getStatusDescription(Order $order): string
    {
        $descriptions = [
            'paid' => 'Pembayaran telah dikonfirmasi, pesanan siap diproses',
            'processing' => 'Pesanan sedang dalam proses pengerjaan',
            'completed' => 'Pesanan telah selesai dan dapat diunduh',
            'cancelled' => 'Pesanan telah dibatalkan',
        ];

        $description = $descriptions[$order->status] ?? "Status pesanan: " . ucfirst($order->status);

        if ($order->type === 'service' && $order->progress > 0) {
            $description .= " (Progress: {$order->progress}%)";
        }

        return $description;
    }

    /**
     * Get progress description
     */
    private function getProgressDescription(int $progress): string
    {
        if ($progress === 100) {
            return 'Pekerjaan telah selesai 100%';
        } elseif ($progress >= 75) {
            return 'Pekerjaan hampir selesai';
        } elseif ($progress >= 50) {
            return 'Pekerjaan sudah setengah jalan';
        } elseif ($progress >= 25) {
            return 'Pekerjaan sedang berjalan';
        } else {
            return 'Pekerjaan baru dimulai';
        }
    }
}

