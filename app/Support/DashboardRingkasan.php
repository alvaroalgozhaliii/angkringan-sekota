<?php

namespace App\Support;

use App\Models\Transaksi;

final class DashboardRingkasan
{
    public static function hitung(): array
    {
        $tidakAktif = [OrderStatus::DIBATALKAN, OrderStatus::KEDALUWARSA];
        $aktif = Transaksi::query()->whereNotIn('status', $tidakAktif);

        $uangMasukHariIni = (float) (clone $aktif)
            ->whereDate('created_at', today())
            ->sum('total_harga');

        $pemasukanTotal = (float) (clone $aktif)->sum('total_harga');

        $pengunjung = function ($query) {
            $query->whereNotNull('verified_by')
                ->orWhere('status', OrderStatus::SELESAI);
        };

        $pengunjungHariIni = (clone $aktif)
            ->whereDate('created_at', today())
            ->where($pengunjung)
            ->count();

        $pengunjungTotal = (clone $aktif)->where($pengunjung)->count();

        $antrian = [];
        foreach (OrderStatus::grupAntrean() as $kunci => $statuses) {
            $antrian[$kunci] = (clone $aktif)->whereIn('status', $statuses)->count();
        }

        return [
            'uangMasukHariIni' => $uangMasukHariIni,
            'pengunjungHariIni' => $pengunjungHariIni,
            'antrian' => $antrian,
            'pengunjungTotal' => $pengunjungTotal,
            'pemasukanTotal' => $pemasukanTotal,
        ];
    }
}
