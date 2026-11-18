<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_transaksi', function (Blueprint $table) {
            foreach ([
                'qris_gambar' => fn (Blueprint $t) => $t->string('qris_gambar')->nullable(),
                'qris_nama_merchant' => fn (Blueprint $t) => $t->string('qris_nama_merchant')->nullable(),
                'tunai_syarat' => fn (Blueprint $t) => $t->text('tunai_syarat')->nullable(),
                'tunai_instruksi' => fn (Blueprint $t) => $t->text('tunai_instruksi')->nullable(),
                'pengiriman_aktif' => fn (Blueprint $t) => $t->boolean('pengiriman_aktif')->default(false),
                'ambil_di_toko_aktif' => fn (Blueprint $t) => $t->boolean('ambil_di_toko_aktif')->default(true),
                'area_pengiriman' => fn (Blueprint $t) => $t->text('area_pengiriman')->nullable(),
                'tarif_pengiriman' => fn (Blueprint $t) => $t->decimal('tarif_pengiriman', 12, 2)->default(0),
                'alamat_toko' => fn (Blueprint $t) => $t->text('alamat_toko')->nullable(),
                'batas_bayar_menit' => fn (Blueprint $t) => $t->unsignedSmallInteger('batas_bayar_menit')->default(30),
            ] as $column => $definition) {
                if (! Schema::hasColumn('pengaturan_transaksi', $column)) {
                    $definition($table);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'qris_gambar', 'qris_nama_merchant', 'tunai_syarat', 'tunai_instruksi',
                'pengiriman_aktif', 'ambil_di_toko_aktif', 'area_pengiriman',
                'tarif_pengiriman', 'alamat_toko', 'batas_bayar_menit',
            ]);
        });
    }
};
