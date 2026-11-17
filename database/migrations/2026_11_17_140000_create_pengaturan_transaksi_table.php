<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->boolean('tunai_aktif')->default(true);
            $table->boolean('qris_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_transaksi');
    }
};
