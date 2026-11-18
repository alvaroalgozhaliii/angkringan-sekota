<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi', 'sumber')) {
                $table->string('sumber', 20)->default('kasir')->after('nama_kasir');
            }
            if (! Schema::hasColumn('transaksi', 'disetujui_oleh')) {
                $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            }
            if (! Schema::hasColumn('transaksi', 'disetujui_at')) {
                $table->timestamp('disetujui_at')->nullable()->after('disetujui_oleh');
            }
            $table->index('sumber');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropIndex(['sumber']);
            $table->dropColumn(['sumber', 'disetujui_oleh', 'disetujui_at']);
        });
    }
};
