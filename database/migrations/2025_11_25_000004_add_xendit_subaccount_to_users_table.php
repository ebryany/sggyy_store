<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('xendit_subaccount_id')->nullable()->unique()->after('bank_account_name');
            $table->string('xendit_subaccount_email')->nullable()->after('xendit_subaccount_id');
            $table->enum('xendit_subaccount_status', ['pending', 'active', 'suspended', 'failed'])->nullable()->default('pending')->after('xendit_subaccount_email');
            $table->json('xendit_subaccount_metadata')->nullable()->after('xendit_subaccount_status');

            $table->index('xendit_subaccount_id');
            $table->index('xendit_subaccount_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['xendit_subaccount_id']);
            $table->dropIndex(['xendit_subaccount_status']);
            $table->dropColumn([
                'xendit_subaccount_id',
                'xendit_subaccount_email',
                'xendit_subaccount_status',
                'xendit_subaccount_metadata',
            ]);
        });
    }
};

