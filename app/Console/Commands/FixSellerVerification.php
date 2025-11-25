<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SellerVerification;

class FixSellerVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seller:fix-verification {email? : Email user yang ingin di-fix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix seller verification status - Refresh user data dan clear cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User dengan email '{$email}' tidak ditemukan!");
                return 1;
            }
            
            $this->fixUser($user);
        } else {
            // Fix all verified sellers
            $this->info('🔍 Checking all verified sellers...');
            $this->newLine();
            
            $verifiedSellers = SellerVerification::where('status', 'verified')
                ->with('user')
                ->get();
            
            if ($verifiedSellers->isEmpty()) {
                $this->warn('Tidak ada seller yang terverifikasi.');
                return 0;
            }
            
            $this->info("Found {$verifiedSellers->count()} verified seller(s)");
            $this->newLine();
            
            foreach ($verifiedSellers as $verification) {
                $this->fixUser($verification->user, $verification);
            }
        }
        
        $this->newLine();
        $this->info('✅ Fix completed!');
        
        return 0;
    }
    
    private function fixUser(User $user, ?SellerVerification $verification = null)
    {
        $this->line("Processing: {$user->email} (ID: {$user->id})");
        
        // Get verification if not provided
        if (!$verification) {
            $verification = $user->sellerVerification;
        }
        
        // Check current status
        $this->line("  Current role: {$user->role}");
        $this->line("  Verification status: " . ($verification ? $verification->status : 'null'));
        
        // Fix role if needed
        if ($verification && $verification->status === 'verified' && !$user->isSeller() && !$user->isAdmin()) {
            $this->warn("  ⚠️  Role belum 'seller', updating...");
            $user->update(['role' => 'seller']);
            $this->info("  ✅ Role updated to 'seller'");
        }
        
        // Refresh user and clear relations
        $user->refresh();
        $user->unsetRelation('sellerVerification');
        
        // Reload verification
        $verification = $user->fresh()->sellerVerification;
        
        // Verify final status
        $isVerified = $user->isVerifiedSeller();
        $this->line("  Final status: " . ($isVerified ? '✅ Verified Seller' : '❌ Not Verified'));
        
        if (!$isVerified && $verification && $verification->status === 'verified') {
            $this->warn("  ⚠️  Verification status is 'verified' but isVerifiedSeller() returns false!");
            $this->line("  This might be a cache issue. Try clearing cache:");
            $this->line("  php artisan cache:clear-all");
        }
        
        $this->newLine();
    }
}

