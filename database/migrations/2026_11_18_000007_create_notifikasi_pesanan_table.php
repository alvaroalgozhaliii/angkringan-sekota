<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->string('status', 30);
            $table->string('judul', 100);
            $table->text('pesan');
            $table->string('channel', 20)->default('halaman');
            $table->string('link_wa')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamps();

            $table->index(['transaksi_id', 'dibaca']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_pesanan');
    }
};
