<?php

namespace App\Support;

final class OrderStatus
{
    public const MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';

    public const MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';

    public const MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const DIBAYAR = 'dibayar';

    public const DITERIMA = 'diterima';

    public const DIPROSES = 'diproses';

    public const DIKIRIM = 'dikirim';

    public const SIAP_DIAMBIL = 'siap_diambil';

    public const SELESAI = 'selesai';

    public const DIBATALKAN = 'dibatalkan';

    public const KEDALUWARSA = 'kedaluwarsa';

    public static function labels(): array
    {
        return [
            self::MENUNGGU_PERSETUJUAN => 'Menunggu Persetujuan',
            self::MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::DIBAYAR => 'Dibayar',
            self::DITERIMA => 'Diterima',
            self::DIPROSES => 'Diproses',
            self::DIKIRIM => 'Dikirim',
            self::SIAP_DIAMBIL => 'Siap Diambil',
            self::SELESAI => 'Selesai',
            self::DIBATALKAN => 'Dibatalkan',
            self::KEDALUWARSA => 'Kedaluwarsa',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public static function badge(string $status): string
    {
        return match ($status) {
            self::MENUNGGU_PERSETUJUAN => 'purple',
            self::MENUNGGU_PEMBAYARAN, self::MENUNGGU_VERIFIKASI => 'warning',
            self::DIBAYAR => 'info',
            self::DITERIMA, self::DIPROSES, self::DIKIRIM, self::SIAP_DIAMBIL => 'primary',
            self::SELESAI => 'success',
            default => 'danger',
        };
    }

    public static function grupAntrean(): array
    {
        return [
            'menunggu_konfirmasi' => [
                self::MENUNGGU_PERSETUJUAN,
                self::MENUNGGU_PEMBAYARAN,
                self::MENUNGGU_VERIFIKASI,
            ],
            'dalam_proses' => [
                self::DIBAYAR,
                self::DITERIMA,
                self::DIPROSES,
                self::DIKIRIM,
            ],
            'siap_diambil' => [self::SIAP_DIAMBIL],
        ];
    }

    public static function labelGrupAntrean(string $grup): ?string
    {
        return [
            'menunggu_konfirmasi' => 'Menunggu konfirmasi',
            'dalam_proses' => 'Dalam proses',
            'siap_diambil' => 'Siap diambil',
        ][$grup] ?? null;
    }

    public static function transisi(): array
    {
        return [
            self::MENUNGGU_PERSETUJUAN => [self::MENUNGGU_PEMBAYARAN, self::DIBATALKAN],
            self::MENUNGGU_PEMBAYARAN => [self::MENUNGGU_VERIFIKASI, self::DIPROSES, self::DIBATALKAN, self::KEDALUWARSA],
            self::MENUNGGU_VERIFIKASI => [self::DIPROSES, self::MENUNGGU_PEMBAYARAN, self::DIBATALKAN, self::KEDALUWARSA],
            self::DIBAYAR => [self::SELESAI, self::DIBATALKAN],
            self::DITERIMA => [self::SELESAI, self::DIBATALKAN],
            self::DIPROSES => [self::SELESAI, self::DIBATALKAN],
            self::DIKIRIM => [self::SELESAI],
            self::SIAP_DIAMBIL => [self::SELESAI],
            self::SELESAI => [],
            self::DIBATALKAN => [],
            self::KEDALUWARSA => [],
        ];
    }

    public static function boleh(string $dari, string $ke): bool
    {
        return in_array($ke, self::transisi()[$dari] ?? [], true);
    }

    public static function tahapanPegawai(string $status): ?string
    {
        return match ($status) {
            self::DIBAYAR, self::DITERIMA, self::DIPROSES, self::DIKIRIM, self::SIAP_DIAMBIL => self::SELESAI,
            default => null,
        };
    }

    public static function tahapPublik(string $status): int
    {
        return match (true) {
            $status === self::SELESAI => 2,
            in_array($status, [
                self::MENUNGGU_PERSETUJUAN, self::MENUNGGU_PEMBAYARAN, self::MENUNGGU_VERIFIKASI,
                self::DIBAYAR, self::DITERIMA, self::DIPROSES, self::DIKIRIM, self::SIAP_DIAMBIL,
            ], true) => 0,
            default => -1,
        };
    }

    public static function pesanStatus(string $status, string $nomor): array
    {
        $judul = 'Update Pesanan '.$nomor;

        return match ($status) {
            self::MENUNGGU_PERSETUJUAN => [$judul, 'Pesanan online Anda sudah masuk dan sedang menunggu persetujuan pegawai. Mohon tunda pembayaran sampai pesanan disetujui.'],
            self::MENUNGGU_PEMBAYARAN => [$judul, 'Pesanan Anda sudah masuk. Silakan unggah bukti bayar QRIS atau lunasi tunai di kasir.'],
            self::MENUNGGU_VERIFIKASI => [$judul, 'Bukti pembayaran Anda sudah diterima dan sedang diverifikasi pegawai.'],
            self::DIBAYAR => [$judul, 'Pembayaran Anda sudah valid. Pesanan langsung kami proses.'],
            self::DITERIMA => [$judul, 'Pesanan sudah diterima dan sedang diproses.'],
            self::DIPROSES => [$judul, 'Pembayaran valid. Pesanan Anda sedang disiapkan.'],
            self::DIKIRIM => [$judul, 'Pesanan Anda sedang dalam perjalanan.'],
            self::SIAP_DIAMBIL => [$judul, 'Pesanan Anda sudah siap diambil di toko.'],
            self::SELESAI => [$judul, 'Pesanan selesai diserahkan. Terima kasih sudah berbelanja di Angkringan Sekota.'],
            self::DIBATALKAN => [$judul, 'Pesanan Anda dibatalkan. Silakan hubungi kami jika ada pertanyaan.'],
            self::KEDALUWARSA => [$judul, 'Batas waktu pembayaran habis, pesanan Anda otomatis ditandai kedaluwarsa.'],
            default => [$judul, 'Status pesanan Anda berubah menjadi '.self::label($status).'.'],
        };
    }
}
