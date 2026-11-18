<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            foreach ([
                'no_telepon_pelanggan' => fn (Blueprint $t) => $t->string('no_telepon_pelanggan', 30)->nullable()->after('nama_pelanggan'),
                'email_pelanggan' => fn (Blueprint $t) => $t->string('email_pelanggan')->nullable()->after('no_telepon_pelanggan'),
                'alamat_pengiriman' => fn (Blueprint $t) => $t->text('alamat_pengiriman')->nullable()->after('email_pelanggan'),
                'metode_pengambilan' => fn (Blueprint $t) => $t->string('metode_pengambilan', 20)->default('ambil_di_toko')->after('metode_pembayaran'),
                'bukti_pembayaran' => fn (Blueprint $t) => $t->string('bukti_pembayaran')->nullable()->after('metode_pengambilan'),
                'alasan_penolakan' => fn (Blueprint $t) => $t->text('alasan_penolakan')->nullable()->after('bukti_pembayaran'),
                'batas_bayar_at' => fn (Blueprint $t) => $t->timestamp('batas_bayar_at')->nullable()->after('status'),
                'waktu_dibayar_at' => fn (Blueprint $t) => $t->timestamp('waktu_dibayar_at')->nullable()->after('batas_bayar_at'),
                'stok_dikurangi' => fn (Blueprint $t) => $t->boolean('stok_dikurangi')->default(false)->after('waktu_dibayar_at'),
                'verified_by' => fn (Blueprint $t) => $t->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('stok_dikurangi'),
                'verified_at' => fn (Blueprint $t) => $t->timestamp('verified_at')->nullable()->after('verified_by'),
                'status_updated_at' => fn (Blueprint $t) => $t->timestamp('status_updated_at')->nullable()->after('verified_at'),
            ] as $column => $definition) {
                if (! Schema::hasColumn('transaksi', $column)) {
                    $definition($table);
                }
            }

            $table->index('status');
            $table->index('batas_bayar_at');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['batas_bayar_at']);
            $table->dropColumn([
                'no_telepon_pelanggan', 'email_pelanggan', 'alamat_pengiriman',
                'metode_pengambilan', 'bukti_pembayaran', 'alasan_penolakan',
                'batas_bayar_at', 'waktu_dibayar_at', 'stok_dikurangi',
                'verified_by', 'verified_at', 'status_updated_at',
            ]);
        });
    }
};
