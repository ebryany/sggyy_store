<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change payments.method from ENUM to VARCHAR to support new payment methods:
     * - veripay_qris
     * - xendit_va
     * - xendit_qris
     */
    public function up(): void
    {
        // For MySQL, we need to drop the enum and recreate as string
        // Using raw SQL to handle enum conversion properly
        DB::statement("ALTER TABLE `payments` MODIFY COLUMN `method` VARCHAR(50) NOT NULL DEFAULT 'wallet'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to enum (only with original values)
        DB::statement("ALTER TABLE `payments` MODIFY COLUMN `method` ENUM('wallet', 'bank_transfer', 'qris', 'manual') NOT NULL DEFAULT 'wallet'");
    }
};

        // Convert back to enum (only with original values)
        DB::statement("ALTER TABLE `payments` MODIFY COLUMN `method` ENUM('wallet', 'bank_transfer', 'qris', 'manual') NOT NULL DEFAULT 'wallet'");
    }
};
