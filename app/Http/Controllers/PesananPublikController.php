<?php

namespace App\Http\Controllers;

use App\Models\PengaturanTransaksi;
use App\Models\Pesan;
use App\Models\Produk;
use App\Models\ProfilUsaha;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Services\PesananService;
use App\Support\ActivityLogger;
use App\Support\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PesananPublikController extends Controller
{
    public function store(Request $request, PesananService $service)
    {
        $data = $request->validate([
            'nama_pengirim' => 'required|string|max:100',
            'nomor_wa' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'catatan' => 'nullable|string|max:1000',
            'items' => 'required|string',
            'metode_pembayaran' => 'required|in:tunai,qris',
            'metode_pengambilan' => 'required|in:ambil_di_toko,pengiriman',
            'alamat_pengiriman' => 'required_if:metode_pengambilan,pengiriman|nullable|string|max:500',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,qris|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_pengirim.required' => 'Nama pemesan wajib diisi.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi untuk verifikasi pesanan.',
            'items.required' => 'Pilih minimal satu menu.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'metode_pengambilan.in' => 'Cara pesanan tidak valid.',
            'alamat_pengiriman.required_if' => 'Alamat pengiriman wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'bukti_pembayaran.required_if' => 'Foto bukti pembayaran QRIS wajib diunggah.',
            'bukti_pembayaran.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format bukti harus JPG, PNG, atau WebP.',
            'bukti_pembayaran.max' => 'Ukuran bukti maksimal 2 MB.',
        ]);

        $pengaturan = PengaturanTransaksi::current();
        if (! in_array($data['metode_pembayaran'], $pengaturan->metodeDiizinkan(), true)) {
            throw ValidationException::withMessages(['metode_pembayaran' => 'Metode pembayaran ini sedang tidak tersedia.']);
        }
        if ($data['metode_pengambilan'] === 'pengiriman' && ! $pengaturan->pengiriman_aktif) {
            throw ValidationException::withMessages(['metode_pengambilan' => 'Layanan pengiriman sedang tidak tersedia.']);
        }
        if ($data['metode_pengambilan'] === 'ambil_di_toko' && ! $pengaturan->ambil_di_toko_aktif) {
            throw ValidationException::withMessages(['metode_pengambilan' => 'Pengambilan di toko sedang tidak tersedia.']);
        }

        $items = json_decode($data['items'], true);
        if (! is_array($items) || count($items) < 1) {
            throw ValidationException::withMessages(['items' => 'Pilih minimal satu menu.']);
        }

        $ringkasan = collect();
        foreach ($items as $item) {
            $id = (int) ($item['id'] ?? 0);
            $qty = (int) ($item['qty'] ?? 0);
            $produk = Produk::find($id);
            if (! $produk || $qty < 1 || $qty > 999) {
                throw ValidationException::withMessages(['items' => 'Menu yang dipilih tidak valid.']);
            }
            if ($produk->stok < $qty) {
                throw ValidationException::withMessages(['items' => 'Stok '.$produk->nama.' tidak mencukupi.']);
            }
            $ringkasan->push([
                'produk' => $produk,
                'qty' => $qty,
                'subtotal' => (float) $produk->harga * $qty,
            ]);
        }

        $total = (float) $ringkasan->sum('subtotal');
        if ($data['metode_pengambilan'] === 'pengiriman') {
            $total += (float) $pengaturan->tarif_pengiriman;
        }

        $statusAwal = OrderStatus::MENUNGGU_PEMBAYARAN;
        $batasBayar = $data['metode_pembayaran'] === 'qris' ? $service->buatBatasBayar() : null;
        $buktiQris = $data['metode_pembayaran'] === 'qris' ? $request->file('bukti_pembayaran') : null;

        $transaksi = null;
        DB::transaction(function () use ($data, $ringkasan, $total, $statusAwal, $batasBayar, $buktiQris, $service, &$transaksi) {
            $transaksi = Transaksi::create([
                'nomor_transaksi' => Transaksi::generateNomorTransaksi(),
                'user_id' => null,
                'nama_kasir' => 'Website',
                'sumber' => 'website',
                'nama_pelanggan' => $data['nama_pengirim'],
                'no_telepon_pelanggan' => $data['nomor_wa'],
                'email_pelanggan' => $data['email'] ?? null,
                'catatan' => $data['catatan'] ?? null,
                'total_harga' => $total,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'metode_pengambilan' => $data['metode_pengambilan'],
                'alamat_pengiriman' => $data['metode_pengambilan'] === 'pengiriman' ? ($data['alamat_pengiriman'] ?? null) : null,
                'status' => $statusAwal,
                'batas_bayar_at' => $batasBayar,
                'status_updated_at' => now(),
            ]);

            foreach ($ringkasan as $item) {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk']->id,
                    'nama_produk' => $item['produk']->nama,
                    'kategori' => $item['produk']->kategori,
                    'harga_satuan' => $item['produk']->harga,
                    'jumlah' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $isi = 'Pesanan baru '.$transaksi->nomor_transaksi."\n"
                .'Pemesan: '.$data['nama_pengirim']."\n"
                .'Metode bayar: '.strtoupper($data['metode_pembayaran'])."\n"
                .'Total: Rp '.number_format($total, 0, ',', '.')."\n\n"
                .($data['catatan'] ?? '-');
            Pesan::create([
                'nama_pengirim' => $data['nama_pengirim'],
                'nomor_wa' => $data['nomor_wa'],
                'email' => $data['email'] ?? null,
                'isi_pesan' => $isi,
                'status' => 'belum_dibaca',
            ]);

            ActivityLogger::log(
                'Pesanan',
                'Buat pesanan dari website',
                null,
                ['nomor' => $transaksi->nomor_transaksi, 'total' => $total, 'metode' => $data['metode_pembayaran']],
                'transaksi',
                $transaksi->id,
                actorNama: 'Pelanggan'
            );
            if ($buktiQris) {
                $service->unggahBukti($transaksi, $buktiQris);
                $transaksi->refresh();
            } else {
                $transaksi->notifikasi()->create([
                    'status' => $statusAwal,
                    'judul' => 'Pesanan '.$transaksi->nomor_transaksi.' dibuat',
                    'pesan' => 'Pesanan tunai Anda sudah masuk. Silakan lunasi di kasir saat pengambilan atau pengiriman.',
                    'channel' => 'halaman',
                    'link_wa' => $transaksi->linkWhatsapp(),
                ]);
            }

            ActivityLogger::log('Pesanan', 'Kirim notifikasi status', null, [
                'status' => OrderStatus::label($transaksi->status),
                'nomor' => $transaksi->nomor_transaksi,
            ], 'transaksi', $transaksi->id, actorNama: 'Sistem');
        });

        $pesan = $data['metode_pembayaran'] === 'qris'
            ? 'Pesanan berhasil dibuat. Bukti pembayaran QRIS sedang diperiksa pegawai. Nomor pesanan: '.$transaksi->nomor_transaksi
            : 'Pesanan berhasil dibuat. Silakan lunasi tunai di kasir. Nomor pesanan: '.$transaksi->nomor_transaksi;
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $pesan,
                'nomor_transaksi' => $transaksi->nomor_transaksi,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'cek_url' => route('pesanan.cek', ['nomor' => $transaksi->nomor_transaksi]),
            ]);
        }

        return redirect()->route('pesanan.cek', ['nomor' => $transaksi->nomor_transaksi])->with('success', $pesan);
    }

    public function unggahBukti(Request $request, Transaksi $transaksi, PesananService $service)
    {
        $data = $request->validate([
            'nomor_transaksi' => 'required|string|max:50',
            'telepon' => 'required|string|max:30',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti bayar wajib diunggah.',
            'bukti_pembayaran.image' => 'Bukti bayar harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2 MB.',
            'telepon.required' => 'Nomor WhatsApp pemesan wajib diisi.',
        ]);

        if ($transaksi->nomor_transaksi !== $data['nomor_transaksi']
            || ! $transaksi->no_telepon_pelanggan
            || $this->normalisasiTelepon($transaksi->no_telepon_pelanggan) !== $this->normalisasiTelepon($data['telepon'])) {
            return back()->with('error', 'Nomor pesanan atau nomor WhatsApp tidak cocok.');
        }

        try {
            $service->unggahBukti($transaksi, $request->file('bukti_pembayaran'));
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', 'Bukti bayar berhasil dikirim. Menunggu verifikasi pegawai.');
    }

    public function cek(Request $request)
    {
        $transaksi = null;
        $error = null;
        if ($request->filled('nomor')) {
            $transaksi = Transaksi::with(['details', 'notifikasi' => fn ($q) => $q->latest()])
                ->where('nomor_transaksi', trim($request->nomor))
                ->first();

            if (! $transaksi) {
                $error = 'Nomor pesanan tidak ditemukan.';
            } elseif ($request->filled('telepon')) {
                $telepon = $this->normalisasiTelepon($request->telepon);
                $tersimpan = $this->normalisasiTelepon($transaksi->no_telepon_pelanggan);
                if (! $tersimpan || $telepon !== $tersimpan) {
                    $error = 'Nomor WhatsApp tidak cocok dengan data pesanan.';
                    $transaksi = null;
                }
            }
        }

        return view('public.cek-pesanan', [
            'profil' => ProfilUsaha::getSingleton(),
            'transaksi' => $transaksi,
            'error' => $error,
            'pengaturan' => PengaturanTransaksi::current(),
            'nomor' => $request->nomor,
        ]);
    }

    private function normalisasiTelepon(?string $nomor): string
    {
        $nomor = preg_replace('/\D/', '', (string) $nomor);

        if (str_starts_with($nomor, '0')) {
            return '62'.substr($nomor, 1);
        }
        if (str_starts_with($nomor, '8')) {
            return '62'.$nomor;
        }

        return $nomor;
    }
}
