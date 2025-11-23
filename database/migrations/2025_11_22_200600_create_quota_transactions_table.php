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
        Schema::create('quota_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ref_id')->unique(); // UUID dari API atau generate sendiri
            $table->string('trx_id')->nullable(); // Transaction ID dari API
            $table->string('produk'); // Kode produk (BPAL19, dll)
            $table->string('tujuan'); // Nomor tujuan (08xxxxxxxxxx)
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, processing, success, failed
            $table->integer('status_code')->nullable(); // 0 = success, 1 = failed
            $table->string('status_text')->nullable(); // SUKSES, GAGAL, dll
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('ref_id');
            $table->index('trx_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_transactions');
    }
};
