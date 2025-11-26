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
        Schema::table('escrows', function (Blueprint $table) {
            $table->string('xendit_disbursement_id')->nullable()->unique()->after('xendit_external_id');
            $table->string('xendit_disbursement_external_id')->nullable()->unique()->after('xendit_disbursement_id');
            $table->json('xendit_disbursement_metadata')->nullable()->after('xendit_disbursement_external_id');

            $table->index('xendit_disbursement_id');
            $table->index('xendit_disbursement_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escrows', function (Blueprint $table) {
            $table->dropIndex(['xendit_disbursement_id']);
            $table->dropIndex(['xendit_disbursement_external_id']);
            $table->dropColumn([
                'xendit_disbursement_id',
                'xendit_disbursement_external_id',
                'xendit_disbursement_metadata',
            ]);
        });
    }
};

