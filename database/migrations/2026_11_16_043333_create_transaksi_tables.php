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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_kasir', 100);
            $table->string('nama_pelanggan', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->string('metode_pembayaran', 20)->default('tunai'); // tunai, qris
            $table->decimal('uang_diterima', 14, 2)->default(0);
            $table->decimal('kembalian', 14, 2)->default(0);
            $table->string('status', 20)->default('selesai'); // selesai, batal
            $table->timestamps();
        });

        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->string('nama_produk', 150);
            $table->string('kategori', 50)->nullable();
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->integer('jumlah')->default(1);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
        Schema::dropIfExists('transaksi');
    }
};
