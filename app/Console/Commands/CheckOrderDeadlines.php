<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOrderDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order deadlines and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking order deadlines...');
        
        $notifiedCount = 0;
        
        // Multi-tier deadline reminders: 48 hours, 24 hours, 12 hours, 6 hours, 3 hours, 1 hour
        $reminderTiers = [
            48 => ['type' => 'deadline_approaching_48h', 'icon' => '📅', 'urgency' => 'low'],
            24 => ['type' => 'deadline_approaching_24h', 'icon' => '⏰', 'urgency' => 'medium'],
            12 => ['type' => 'deadline_approaching_12h', 'icon' => '⚠️', 'urgency' => 'high'],
            6 => ['type' => 'deadline_approaching_6h', 'icon' => '🔥', 'urgency' => 'urgent'],
            3 => ['type' => 'deadline_approaching_3h', 'icon' => '🚨', 'urgency' => 'critical'],
            1 => ['type' => 'deadline_approaching_1h', 'icon' => '⚡', 'urgency' => 'critical'],
        ];
        
        foreach ($reminderTiers as $hoursBefore => $tierConfig) {
            $this->info("Checking orders with deadline in {$hoursBefore} hours...");
            
            $upcomingDeadlines = Order::with(['product', 'service', 'user'])
                ->whereIn('status', ['paid', 'processing'])
                ->whereNotNull('deadline_at')
                ->whereBetween('deadline_at', [
                    now()->addHours($hoursBefore)->subMinutes(30), // 30 min window before exact time
                    now()->addHours($hoursBefore)->addMinutes(30)  // 30 min window after exact time
                ])
                ->whereDoesntHave('user.notifications', function ($query) use ($tierConfig) {
                    $query->where('type', $tierConfig['type'])
                        ->where('created_at', '>=', now()->subHours(24));
                })
                ->get();
            
            foreach ($upcomingDeadlines as $order) {
                $hoursLeft = now()->diffInHours($order->deadline_at, false);
                $minutesLeft = now()->diffInMinutes($order->deadline_at, false);
                
                // Determine message based on time left
                $timeMessage = '';
                if ($hoursLeft >= 1) {
                    $timeMessage = "dalam {$hoursLeft} jam";
                } else {
                    $timeMessage = "dalam {$minutesLeft} menit";
                }
                
                $urgencyMessages = [
                    'low' => "⏳ Deadline pesanan #{$order->order_number} akan jatuh tempo {$timeMessage}. Pastikan semuanya berjalan lancar.",
                    'medium' => "⏰ Deadline pesanan #{$order->order_number} akan jatuh tempo {$timeMessage}! Waktunya untuk fokus menyelesaikan pekerjaan.",
                    'high' => "⚠️ Deadline pesanan #{$order->order_number} semakin dekat ({$timeMessage})! Percepat proses pengerjaan.",
                    'urgent' => "🔥 Deadline pesanan #{$order->order_number} sangat dekat ({$timeMessage})! Prioritaskan penyelesaian segera!",
                    'critical' => "🚨 URGENT: Deadline pesanan #{$order->order_number} hampir tiba ({$timeMessage})! Segera selesaikan atau komunikasikan dengan buyer!",
                ];
                
                $message = $tierConfig['icon'] . ' ' . ($urgencyMessages[$tierConfig['urgency']] ?? "Deadline pesanan #{$order->order_number} akan jatuh tempo {$timeMessage}!");
                
                // Notify buyer
                Notification::create([
                    'user_id' => $order->user_id,
                    'message' => $message,
                    'type' => $tierConfig['type'],
                    'is_read' => false,
                    'notifiable_type' => Order::class,
                    'notifiable_id' => $order->id,
                ]);
                
                // Notify seller
                $sellerId = $order->type === 'product' 
                    ? $order->product?->user_id 
                    : $order->service?->user_id;
                
                if ($sellerId) {
                    Notification::create([
                        'user_id' => $sellerId,
                        'message' => $message,
                        'type' => $tierConfig['type'],
                        'is_read' => false,
                        'notifiable_type' => Order::class,
                        'notifiable_id' => $order->id,
                    ]);
                }
                
                $notifiedCount++;
                $this->info("  → Notified for order #{$order->order_number} ({$timeMessage} remaining)");
            }
        }
        
        // 2. Find orders with passed deadlines
        $passedDeadlines = Order::with(['product', 'service', 'user'])
            ->whereIn('status', ['paid', 'processing'])
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '<', now())
            ->whereDoesntHave('user.notifications', function ($query) {
                $query->where('type', 'deadline_passed')
                    ->where('created_at', '>=', now()->subHours(24));
            })
            ->get();
        
        foreach ($passedDeadlines as $order) {
            // Notify buyer
            Notification::create([
                'user_id' => $order->user_id,
                'message' => "⚠️ Deadline pesanan #{$order->order_number} telah terlewat! Segera hubungi seller.",
                'type' => 'deadline_passed',
                'is_read' => false,
                'notifiable_type' => Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify seller
            $sellerId = $order->type === 'product' 
                ? $order->product?->user_id 
                : $order->service?->user_id;
            
            if ($sellerId) {
                Notification::create([
                    'user_id' => $sellerId,
                    'message' => "⚠️ Deadline pesanan #{$order->order_number} telah terlewat! Segera selesaikan pesanan.",
                    'type' => 'deadline_passed',
                    'is_read' => false,
                    'notifiable_type' => Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
            
            $notifiedCount++;
            $this->warn("  → OVERDUE: Order #{$order->order_number} (deadline was {$order->deadline_at->diffForHumans()})");
        }
        
        $this->info("");
        $this->info("📊 Summary:");
        $this->info("  ✅ Sent {$notifiedCount} deadline reminders");
        $this->warn("  ⚠️  Found {$passedDeadlines->count()} overdue orders");
        $this->info("");
        $this->info('✨ Done!');
        
        return 0;
    }
}
