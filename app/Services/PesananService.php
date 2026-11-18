<?php

namespace App\Services;

use App\Models\PengaturanTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Support\ActivityLogger;
use App\Support\OrderStatus;
use App\Support\UploadGambar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class PesananService
{
    public function buatBatasBayar(): Carbon
    {
        $menit = max(1, (int) (PengaturanTransaksi::current()->batas_bayar_menit ?: 30));

        return now()->addMinutes($menit);
    }

    public function unggahBukti(Transaksi $transaksi, UploadedFile $file): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $file) {
            $item = $this->kunci($transaksi);

            if ($item->metode_pembayaran !== 'qris') {
                throw ValidationException::withMessages(['bukti_pembayaran' => 'Bukti bayar hanya untuk pembayaran QRIS.']);
            }
            if ($item->status !== OrderStatus::MENUNGGU_PEMBAYARAN) {
                throw ValidationException::withMessages(['bukti_pembayaran' => 'Bukti bayar hanya bisa diunggah saat pesanan menunggu pembayaran.']);
            }
            if ($item->batas_bayar_at && $item->batas_bayar_at->isPast()) {
                throw ValidationException::withMessages(['bukti_pembayaran' => 'Batas waktu pembayaran sudah habis.']);
            }

            $sebelum = $this->snapshot($item);
            $pathLama = $item->bukti_pembayaran;
            $path = UploadGambar::simpan($file, 'bukti-bayar');

            $item->update([
                'bukti_pembayaran' => $path,
                'alasan_penolakan' => null,
                'status' => OrderStatus::MENUNGGU_VERIFIKASI,
                'status_updated_at' => now(),
            ]);

            if ($pathLama) {
                UploadGambar::hapus($pathLama);
            }

            ActivityLogger::log(
                'Pesanan',
                'Unggah bukti pembayaran QRIS',
                $sebelum,
                $this->snapshot($item),
                'transaksi',
                $item->id,
                actorNama: 'Pelanggan'
            );
            $this->notifikasi($item, OrderStatus::MENUNGGU_VERIFIKASI);

            return $item;
        });
    }

    public function verifikasiQris(Transaksi $transaksi, string $keputusan, ?string $alasan = null): Transaksi
    {
        if (! in_array($keputusan, ['valid', 'tolak'], true)) {
            throw ValidationException::withMessages(['keputusan' => 'Keputusan verifikasi tidak valid.']);
        }
        if ($keputusan === 'tolak' && mb_strlen(trim((string) $alasan)) < 5) {
            throw ValidationException::withMessages(['alasan' => 'Alasan penolakan wajib diisi minimal 5 karakter.']);
        }

        return DB::transaction(function () use ($transaksi, $keputusan, $alasan) {
            $item = $this->kunci($transaksi);

            if ($item->status !== OrderStatus::MENUNGGU_VERIFIKASI) {
                throw ValidationException::withMessages(['status' => 'Pesanan ini tidak sedang menunggu verifikasi pembayaran.']);
            }

            $sebelum = $this->snapshot($item);

            if ($keputusan === 'tolak') {
                $pathLama = $item->bukti_pembayaran;
                $item->update([
                    'status' => OrderStatus::MENUNGGU_PEMBAYARAN,
                    'bukti_pembayaran' => null,
                    'alasan_penolakan' => trim($alasan),
                    'status_updated_at' => now(),
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                ActivityLogger::log(
                    'Pembayaran',
                    'Tolak pembayaran QRIS',
                    $sebelum,
                    $this->snapshot($item),
                    'transaksi',
                    $item->id
                );
                if ($pathLama) {
                    DB::afterCommit(fn () => UploadGambar::hapus($pathLama));
                }
                $this->notifikasi($item, OrderStatus::MENUNGGU_PEMBAYARAN, 'Bukti bayar ditolak. Alasan: '.trim($alasan));

                return $item;
            }

            $item->update([
                'status' => OrderStatus::DIPROSES,
                'uang_diterima' => $item->total_harga,
                'kembalian' => 0,
                'alasan_penolakan' => null,
                'waktu_dibayar_at' => now(),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'disetujui_oleh' => auth()->id(),
                'disetujui_at' => now(),
                'status_updated_at' => now(),
            ]);

            ActivityLogger::log(
                'Pembayaran',
                'Validasi QRIS dan mulai proses',
                $sebelum,
                $this->snapshot($item),
                'transaksi',
                $item->id
            );
            $this->kurangiStok($item);
            $this->notifikasi($item, OrderStatus::DIPROSES);

            return $item->fresh();
        });
    }

    public function konfirmasiTunai(Transaksi $transaksi, float $uangDiterima): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $uangDiterima) {
            $item = $this->kunci($transaksi);

            if (! in_array($item->status, [OrderStatus::MENUNGGU_PEMBAYARAN, OrderStatus::MENUNGGU_VERIFIKASI], true)) {
                throw ValidationException::withMessages(['status' => 'Pembayaran tunai hanya bisa dikonfirmasi saat menunggu pembayaran.']);
            }
            if ($uangDiterima + 0.01 < (float) $item->total_harga) {
                throw ValidationException::withMessages(['uang_diterima' => 'Uang yang diterima kurang dari total pesanan.']);
            }

            $sebelum = $this->snapshot($item);
            $item->update([
                'status' => OrderStatus::DIPROSES,
                'uang_diterima' => $uangDiterima,
                'kembalian' => max(0, $uangDiterima - (float) $item->total_harga),
                'waktu_dibayar_at' => now(),
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'disetujui_oleh' => auth()->id(),
                'disetujui_at' => now(),
                'status_updated_at' => now(),
            ]);

            ActivityLogger::log(
                'Pembayaran',
                'Konfirmasi tunai dan mulai proses',
                $sebelum,
                $this->snapshot($item),
                'transaksi',
                $item->id
            );
            $this->kurangiStok($item);
            $this->notifikasi($item, OrderStatus::DIPROSES);

            return $item->fresh();
        });
    }

    private function ubahStatus(Transaksi $transaksi, string $statusBaru): Transaksi
    {
        return DB::transaction(function () use ($transaksi, $statusBaru) {
            $item = $this->kunci($transaksi);
            $dari = $item->status;

            if (! OrderStatus::boleh($dari, $statusBaru)) {
                throw ValidationException::withMessages([
                    'status' => 'Transisi status '.(OrderStatus::label($dari)).' ke '.(OrderStatus::label($statusBaru)).' tidak valid.',
                ]);
            }

            $sebelum = $this->snapshot($item);
            $item->update([
                'status' => $statusBaru,
                'status_updated_at' => now(),
            ]);

            ActivityLogger::log(
                'Pesanan',
                'Ubah status pesanan',
                $sebelum + ['status' => $dari],
                ['status' => $statusBaru, 'status_updated_at' => $item->status_updated_at],
                'transaksi',
                $item->id
            );

            if ($statusBaru === OrderStatus::DIBAYAR) {
                $this->kurangiStok($item);
            }
            if ($statusBaru === OrderStatus::DIBATALKAN && $item->stok_dikurangi) {
                $this->kembalikanStok($item);
            }
            if ($statusBaru === OrderStatus::MENUNGGU_VERIFIKASI && ! $item->bukti_pembayaran) {
                throw ValidationException::withMessages(['status' => 'Bukti pembayaran belum diunggah pelanggan.']);
            }

            $this->notifikasi($item, $statusBaru);

            return $item->fresh();
        });
    }

    public function serahkanPesanan(Transaksi $transaksi): Transaksi
    {
        return DB::transaction(function () use ($transaksi) {
            $item = $this->kunci($transaksi);

            if (OrderStatus::tahapanPegawai($item->status) !== OrderStatus::SELESAI) {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan ini belum siap diserahkan.',
                ]);
            }

            $sebelum = $this->snapshot($item);
            $item->update([
                'status' => OrderStatus::SELESAI,
                'status_updated_at' => now(),
            ]);

            ActivityLogger::log(
                'Pesanan',
                'Serahkan pesanan ke pengunjung',
                $sebelum,
                $this->snapshot($item),
                'transaksi',
                $item->id
            );
            $this->notifikasi($item, OrderStatus::SELESAI, 'Pesanan selesai diserahkan.');

            return $item->fresh();
        });
    }

    public function tolakPesanan(Transaksi $transaksi, string $alasan): Transaksi
    {
        $alasan = trim($alasan);
        if (mb_strlen($alasan) < 5) {
            throw ValidationException::withMessages(['alasan' => 'Alasan penolakan wajib diisi minimal 5 karakter.']);
        }

        return DB::transaction(function () use ($transaksi, $alasan) {
            $item = $this->kunci($transaksi);
            $bolehDitolak = [
                OrderStatus::MENUNGGU_PERSETUJUAN,
                OrderStatus::MENUNGGU_PEMBAYARAN,
                OrderStatus::DIBAYAR,
                OrderStatus::DITERIMA,
                OrderStatus::DIPROSES,
                OrderStatus::DIKIRIM,
                OrderStatus::SIAP_DIAMBIL,
            ];
            if (! in_array($item->status, $bolehDitolak, true)) {
                throw ValidationException::withMessages(['status' => 'Pesanan ini tidak dapat ditolak pada status saat ini.']);
            }

            $sebelum = $this->snapshot($item);
            $item->update([
                'status' => OrderStatus::DIBATALKAN,
                'alasan_penolakan' => $alasan,
                'disetujui_oleh' => auth()->id(),
                'disetujui_at' => now(),
                'status_updated_at' => now(),
            ]);
            if ($item->stok_dikurangi) {
                $this->kembalikanStok($item);
            }

            ActivityLogger::log(
                'Pesanan',
                'Tolak pesanan',
                $sebelum,
                $this->snapshot($item),
                'transaksi',
                $item->id
            );
            $this->notifikasi($item, OrderStatus::DIBATALKAN, 'Alasan: '.$alasan);

            return $item->fresh();
        });
    }

    public function catatPesananKasir(array $data, Collection $ringkasan, float $total, float $uangDiterima): Transaksi
    {
        return DB::transaction(function () use ($data, $ringkasan, $total, $uangDiterima) {
            $user = auth()->user();
            $item = Transaksi::create([
                'nomor_transaksi' => Transaksi::generateNomorTransaksi(),
                'user_id' => $user?->id,
                'nama_kasir' => $user?->name ?: 'Kasir',
                'sumber' => 'kasir',
                'nama_pelanggan' => ($data['nama_pelanggan'] ?? null) ?: null,
                'no_telepon_pelanggan' => ($data['nomor_wa'] ?? null) ?: null,
                'catatan' => ($data['catatan'] ?? null) ?: null,
                'total_harga' => $total,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'metode_pengambilan' => 'ambil_di_toko',
                'status' => OrderStatus::DIPROSES,
                'uang_diterima' => $uangDiterima,
                'kembalian' => max(0, $uangDiterima - $total),
                'waktu_dibayar_at' => now(),
                'verified_by' => $user?->id,
                'verified_at' => now(),
                'disetujui_oleh' => $user?->id,
                'disetujui_at' => now(),
                'status_updated_at' => now(),
            ]);

            foreach ($ringkasan as $detail) {
                TransaksiDetail::create([
                    'transaksi_id' => $item->id,
                    'produk_id' => $detail['produk']->id,
                    'nama_produk' => $detail['produk']->nama,
                    'kategori' => $detail['produk']->kategori,
                    'harga_satuan' => $detail['produk']->harga,
                    'jumlah' => $detail['qty'],
                    'subtotal' => $detail['subtotal'],
                ]);
            }

            ActivityLogger::log(
                'Pesanan',
                'Buat pesanan kasir langsung',
                null,
                ['nomor' => $item->nomor_transaksi, 'total' => $total, 'metode' => $data['metode_pembayaran']],
                'transaksi',
                $item->id
            );
            $this->kurangiStok($item);
            $this->notifikasi($item, OrderStatus::DIPROSES);

            return $item->fresh();
        });
    }

    public function jalankanKedaluwarsa(): int
    {
        $ditandai = 0;
        $daftar = Transaksi::query()
            ->whereIn('status', [OrderStatus::MENUNGGU_PEMBAYARAN, OrderStatus::MENUNGGU_VERIFIKASI])
            ->whereNotNull('batas_bayar_at')
            ->where('batas_bayar_at', '<', now())
            ->pluck('id');

        foreach ($daftar as $id) {
            $transaksi = Transaksi::find($id);
            if (! $transaksi) {
                continue;
            }
            try {
                $this->ubahStatus($transaksi, OrderStatus::KEDALUWARSA);
                $ditandai++;
            } catch (ValidationException) {
                continue;
            }
        }

        return $ditandai;
    }

    private function kunci(Transaksi $transaksi): Transaksi
    {
        $item = Transaksi::whereKey($transaksi->id)->lockForUpdate()->first();
        if (! $item) {
            throw (new ModelNotFoundException)->setModel(Transaksi::class, [$transaksi->id]);
        }

        return $item;
    }

    private function kurangiStok(Transaksi $item): void
    {
        if ($item->stok_dikurangi) {
            return;
        }

        $sebelum = [];
        $sesudah = [];
        foreach ($item->details()->with('produk')->get() as $detail) {
            if (! $detail->produk_id) {
                continue;
            }

            $produk = Produk::whereKey($detail->produk_id)->lockForUpdate()->first();
            if (! $produk) {
                continue;
            }
            if ($produk->stok < $detail->jumlah) {
                throw ValidationException::withMessages([
                    'stok' => 'Stok '.$produk->nama.' tidak mencukupi (tersisa '.$produk->stok.').',
                ]);
            }

            $sebelum[$produk->nama] = 'stok '.$produk->stok;
            $produk->decrement('stok', $detail->jumlah);
            $sesudah[$produk->nama] = 'stok '.($produk->stok - $detail->jumlah);
        }

        $item->update(['stok_dikurangi' => true]);

        ActivityLogger::log(
            'Produk & Stok',
            'Kurangi stok otomatis saat pembayaran',
            $sebelum,
            $sesudah,
            'transaksi',
            $item->id
        );
    }

    private function kembalikanStok(Transaksi $item): void
    {
        $sebelum = [];
        $sesudah = [];
        foreach ($item->details()->with('produk')->get() as $detail) {
            if (! $detail->produk_id) {
                continue;
            }
            $produk = Produk::whereKey($detail->produk_id)->lockForUpdate()->first();
            if (! $produk) {
                continue;
            }
            $sebelum[$produk->nama] = 'stok '.$produk->stok;
            $produk->increment('stok', $detail->jumlah);
            $sesudah[$produk->nama] = 'stok '.($produk->stok + $detail->jumlah);
        }

        $item->update(['stok_dikurangi' => false]);

        ActivityLogger::log(
            'Produk & Stok',
            'Kembalikan stok saat pesanan dibatalkan',
            $sebelum,
            $sesudah,
            'transaksi',
            $item->id
        );
    }

    private function notifikasi(Transaksi $item, string $status, ?string $catatan = null): void
    {
        [$judul, $pesan] = OrderStatus::pesanStatus($status, $item->nomor_transaksi);
        if ($catatan) {
            $pesan .= ' '.$catatan;
        }

        $item->notifikasi()->create([
            'status' => $status,
            'judul' => $judul,
            'pesan' => $pesan,
            'channel' => 'halaman',
            'link_wa' => $item->linkWhatsapp(),
        ]);
    }

    private function snapshot(Transaksi $item): array
    {
        return [
            'status' => $item->status,
            'sumber' => $item->sumber,
            'batas_bayar_at' => $item->batas_bayar_at?->format('Y-m-d H:i:s'),
            'metode_pembayaran' => $item->metode_pembayaran,
            'total_harga' => $item->total_harga,
            'uang_diterima' => $item->uang_diterima,
            'kembalian' => $item->kembalian,
            'bukti_pembayaran' => $item->bukti_pembayaran,
            'alasan_penolakan' => $item->alasan_penolakan,
            'stok_dikurangi' => $item->stok_dikurangi ? 'ya' : 'tidak',
            'waktu_dibayar_at' => $item->waktu_dibayar_at?->format('Y-m-d H:i:s'),
        ];
    }
}
