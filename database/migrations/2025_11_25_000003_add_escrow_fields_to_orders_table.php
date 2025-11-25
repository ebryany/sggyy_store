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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('escrow_id')->nullable()->after('service_id')->constrained()->onDelete('set null');
            $table->boolean('is_disputed')->default(false)->after('escrow_id');
            
            $table->index('escrow_id');
            $table->index('is_disputed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['escrow_id']);
            $table->dropIndex(['is_disputed']);
            $table->dropForeign(['escrow_id']);
            $table->dropColumn(['escrow_id', 'is_disputed']);
        });
    }
};

