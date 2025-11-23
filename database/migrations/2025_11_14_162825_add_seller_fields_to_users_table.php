<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('store_name')->nullable()->after('avatar');
            $table->text('store_description')->nullable();
            $table->string('store_banner')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'store_name',
                'store_description',
                'store_banner',
                'store_logo',
                'phone',
                'address',
                'social_instagram',
                'social_twitter',
                'social_facebook',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
            ]);
        });
    }
};
