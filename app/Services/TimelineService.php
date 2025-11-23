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
            'time' => $order->created_at->format('d M Y, H:i'),
            'label' => 'Pesanan Dibuat',
            'status' => 'pending',
            'icon' => '🛒',
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
                            ? $order->payment->verified_at->format('d M Y, H:i')
                            : $paymentTime,
                        'label' => 'Pembayaran Diterima',
                        'status' => 'completed',
                        'icon' => '✅',
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

        // 3. Order Status Changes
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Diterima',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $statusIcons = [
            'pending' => '⏳',
            'paid' => '💰',
            'processing' => '⚙️',
            'completed' => '✅',
            'cancelled' => '❌',
        ];

        // Add status timeline based on current status
        if ($order->status !== 'pending') {
            $timeline[] = [
                'time' => $order->updated_at->format('d M Y, H:i'),
                'label' => $statusLabels[$order->status] ?? ucfirst($order->status),
                'status' => $order->status === 'completed' ? 'completed' : 
                          ($order->status === 'cancelled' ? 'cancelled' : 'processing'),
                'icon' => $statusIcons[$order->status] ?? '📦',
                'description' => $this->getStatusDescription($order),
            ];
        }

        // 4. Progress Updates (for services) - Use actual progress update records for accurate timestamps
        if ($order->type === 'service') {
            $progressUpdates = $order->progressUpdates()->orderBy('created_at', 'desc')->get();
            
            // Show latest progress milestone (25, 50, 75, 100) or current progress if no milestones
            $milestones = [25, 50, 75, 100];
            $latestMilestone = null;
            $latestProgressUpdate = null;
            
            foreach ($progressUpdates as $update) {
                if (in_array($update->progress_to, $milestones)) {
                    $latestMilestone = $update;
                    break;
                }
                if (!$latestProgressUpdate) {
                    $latestProgressUpdate = $update;
                }
            }
            
            $displayUpdate = $latestMilestone ?? $latestProgressUpdate;
            
            if ($displayUpdate) {
                $timeline[] = [
                    'time' => $displayUpdate->created_at->format('d M Y, H:i'),
                    'label' => "Progress: {$displayUpdate->progress_to}%",
                    'status' => $displayUpdate->progress_to === 100 ? 'completed' : 'processing',
                    'icon' => '📊',
                    'description' => $this->getProgressDescription($displayUpdate->progress_to),
                ];
            } elseif ($order->progress > 0) {
                // Fallback to order updated_at if no progress updates found
                $timeline[] = [
                    'time' => $order->updated_at->format('d M Y, H:i'),
                    'label' => "Progress: {$order->progress}%",
                    'status' => $order->progress === 100 ? 'completed' : 'processing',
                    'icon' => '📊',
                    'description' => $this->getProgressDescription($order->progress),
                ];
            }
        }

        // 5. Task File Uploaded (for services)
        if ($order->task_file_path) {
            $timeline[] = [
                'time' => $order->updated_at->format('d M Y, H:i'),
                'label' => 'File Tugas Diterima',
                'status' => 'completed',
                'icon' => '📎',
                'description' => 'Seller telah menerima file tugas yang diupload',
            ];
        }

        // 6. Revision Request
        if ($order->needs_revision) {
            $timeline[] = [
                'time' => $order->updated_at->format('d M Y, H:i'),
                'label' => 'Revisi Diminta',
                'status' => 'pending',
                'icon' => '🔄',
                'description' => $order->revision_notes ?? 'Seller meminta revisi',
            ];
        }

        // 7. Completion
        if ($order->status === 'completed' && $order->completed_at) {
            $timeline[] = [
                'time' => $order->completed_at->format('d M Y, H:i'),
                'label' => 'Pesanan Selesai',
                'status' => 'completed',
                'icon' => '🎉',
                'description' => 'Pesanan telah selesai dan dapat diunduh',
            ];
        }

        // 8. Deliverable Available
        if ($order->deliverable_path) {
            $timeline[] = [
                'time' => $order->updated_at->format('d M Y, H:i'),
                'label' => 'File Hasil Tersedia',
                'status' => 'completed',
                'icon' => '📦',
                'description' => 'File hasil pekerjaan telah tersedia untuk diunduh',
            ];
        }

        // Sort timeline by time (oldest first)
        usort($timeline, function($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
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

