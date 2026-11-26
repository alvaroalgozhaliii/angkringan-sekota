<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\PengaturanTransaksi;
use App\Models\Transaksi;
use App\Services\PesananService;
use App\Support\OrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PesananController extends PegawaiController
{
    public function index(Request $request, PesananService $service)
    {
        $service->jalankanKedaluwarsa();

        $query = Transaksi::with(['details', 'user']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_transaksi', 'like', "%{$keyword}%")
                    ->orWhere('nama_pelanggan', 'like', "%{$keyword}%")
                    ->orWhere('no_telepon_pelanggan', 'like', "%{$keyword}%");
            });
        }
        if ($request->filled('status') && array_key_exists($request->status, OrderStatus::labels())) {
            $query->where('status', $request->status);
        }
        if ($request->filled('grup') && is_string($request->grup)) {
            $statuses = OrderStatus::grupAntrean()[$request->grup] ?? null;
            if ($statuses !== null) {
                $query->whereIn('status', $statuses);
            }
        }
        if ($request->filled('metode') && in_array($request->metode, ['tunai', 'qris'], true)) {
            $query->where('metode_pembayaran', $request->metode);
        }
        if ($request->filled('sumber') && in_array($request->sumber, ['website', 'kasir'], true)) {
            $query->where('sumber', $request->sumber);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->tanggal_mulai)->toDateString());
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->tanggal_akhir)->toDateString());
        }

        $prioritas = [
            OrderStatus::MENUNGGU_VERIFIKASI,
            OrderStatus::MENUNGGU_PEMBAYARAN,
            OrderStatus::DIBAYAR,
            OrderStatus::DITERIMA,
            OrderStatus::DIPROSES,
        ];
        $kasus = collect($prioritas)->map(fn (string $status, int $urutan) => "WHEN status = '{$status}' THEN {$urutan}")->implode(' ');

        $pesanan = $query
            ->orderByRaw("CASE {$kasus} ELSE 9 END")
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('pegawai.pesanan.index', [
            'pesanan' => $pesanan,
            'statusList' => OrderStatus::labels(),
        ]);
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['details', 'notifikasi' => fn ($q) => $q->latest(), 'diverifikasiOleh', 'disetujuiOleh']);

        return view('pegawai.pesanan.show', [
            'transaksi' => $transaksi,
            'tahapanBerikutnya' => OrderStatus::tahapanPegawai($transaksi->status),
            'pengaturan' => PengaturanTransaksi::current(),
        ]);
    }

    public function serahkan(Transaksi $transaksi, PesananService $service)
    {
        return $this->jalankanAksi(
            fn () => $service->serahkanPesanan($transaksi),
            'Pesanan selesai diserahkan ke pengunjung.'
        );
    }

    private function jalankanAksi(callable $aksi, string $pesanSukses)
    {
        try {
            $aksi();
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', $pesanSukses);
    }

    public function tolak(Request $request, Transaksi $transaksi, PesananService $service)
    {
        $data = $request->validate([
            'alasan' => 'required|string|min:5|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        try {
            $service->tolakPesanan($transaksi, $data['alasan']);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', 'Pesanan online ditolak. Pelanggan mendapat notifikasi alasan.');
    }

    public function verifikasiQris(Request $request, Transaksi $transaksi, PesananService $service)
    {
        $data = $request->validate([
            'keputusan' => 'required|in:valid,tolak',
            'alasan' => 'nullable|string|max:500',
        ], [
            'keputusan.required' => 'Pilih keputusan verifikasi.',
        ]);

        try {
            $service->verifikasiQris($transaksi, $data['keputusan'], $data['alasan'] ?? null);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', $data['keputusan'] === 'valid'
            ? 'Pembayaran QRIS divalidasi. Pesanan langsung diproses dan stok sudah dikurangi.'
            : 'Pembayaran QRIS ditolak. Pelanggan diminta mengunggah ulang bukti bayar.');
    }

    public function konfirmasiTunai(Request $request, Transaksi $transaksi, PesananService $service)
    {
        $data = $request->validate([
            'uang_diterima' => 'required|numeric|min:0',
        ], [
            'uang_diterima.required' => 'Nominal uang diterima wajib diisi.',
            'uang_diterima.numeric' => 'Nominal uang harus berupa angka.',
        ]);

        try {
            $service->konfirmasiTunai($transaksi, (float) $data['uang_diterima']);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', 'Pembayaran tunai dikonfirmasi. Pesanan langsung diproses dan stok berkurang sekali.');
    }
}
