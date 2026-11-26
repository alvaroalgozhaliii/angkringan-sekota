@extends('admin.layouts.app')
@section('page-title', 'Detail Pesanan')
@section('title', 'Detail Pesanan')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $transaksi->nomor_transaksi }}</div>
        <div class="page-sub">Dipesan {{ $transaksi->formatted_tanggal_waktu }} • {{ $transaksi->nama_pelanggan ?: 'Pelanggan' }}</div>
    </div>
    <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-white">← Kembali</a>
</div>

<div class="two-col">
    <div class="panel">
        <div class="panel-title">Ringkasan Pesanan</div>
        <table>
            <tbody>
                <tr><th>Status</th><td><span class="badge badge-{{ \App\Support\OrderStatus::badge($transaksi->status) }}">{{ $transaksi->status_label }}</span></td></tr>
                <tr><th>Sumber</th><td>{{ $transaksi->sumber_label }}</td></tr>
                @if($transaksi->disetujui_at)<tr><th>Disetujui oleh</th><td>{{ $transaksi->disetujuiOleh?->name ?: '-' }} • {{ $transaksi->disetujui_at->translatedFormat('d M Y, H:i') }}</td></tr>@endif
                <tr><th>Metode bayar</th><td>{{ strtoupper($transaksi->metode_pembayaran) }}</td></tr>
                <tr><th>Pengambilan</th><td>{{ $transaksi->metode_pengambilan === 'pengiriman' ? 'Dikirim' : 'Ambil di toko' }}</td></tr>
                @if($transaksi->alamat_pengiriman)<tr><th>Alamat</th><td>{{ $transaksi->alamat_pengiriman }}</td></tr>@endif
                <tr><th>WhatsApp</th><td>
                    {{ $transaksi->no_telepon_pelanggan ?: '-' }}
                    @if($transaksi->linkWhatsapp()) <a class="link-wa" href="{{ $transaksi->linkWhatsapp() }}" target="_blank" rel="noopener">Chat WA ↗</a>@endif
                </td></tr>
                <tr><th>Batas bayar</th><td>{{ $transaksi->batas_bayar_at?->translatedFormat('d M Y, H:i') ?: '-' }}</td></tr>
                <tr><th>Uang diterima</th><td>{{ $transaksi->formatted_uang_diterima }}</td></tr>
                <tr><th>Kembalian</th><td>{{ $transaksi->formatted_kembalian }}</td></tr>
                <tr><th>Stok sudah dikurangi</th><td>{{ $transaksi->stok_dikurangi ? 'Ya' : 'Belum' }}</td></tr>
                @if($transaksi->alasan_penolakan)<tr><th>Alasan penolakan</th><td style="color:#991B1B">{{ $transaksi->alasan_penolakan }}</td></tr>@endif
            </tbody>
        </table>

        <div class="divider"></div>
        <strong style="font-size:.9rem">Item Pesanan</strong>
        <div class="table-wrap" style="margin-top:.6rem">
            <table>
                <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                <tbody>
                    @foreach($transaksi->details as $detail)
                        <tr>
                            <td>{{ $detail->nama_produk }}</td>
                            <td>Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ $detail->subtotal_format }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot><tr><th colspan="3">Total</th><th>{{ $transaksi->formatted_total }}</th></tr></tfoot>
            </table>
        </div>
    </div>

    <div>
        @php
            $status = $transaksi->status;
            $tahapSaatIni = \App\Support\OrderStatus::tahapPublik($status);
            $tahapList = [
                ['Pemrosesan pesanan', 'Validasi pembayaran otomatis memulai tahap ini.'],
                ['Penyerahan ke pengunjung', 'Pesanan diserahkan dan dinyatakan selesai.'],
            ];
            $bolehTolak = in_array($status, [
                \App\Support\OrderStatus::MENUNGGU_PERSETUJUAN,
                \App\Support\OrderStatus::MENUNGGU_PEMBAYARAN,
                \App\Support\OrderStatus::DIBAYAR,
                \App\Support\OrderStatus::DITERIMA,
                \App\Support\OrderStatus::DIPROSES,
                \App\Support\OrderStatus::DIKIRIM,
                \App\Support\OrderStatus::SIAP_DIAMBIL,
            ], true);
        @endphp

        <div class="panel">
            <div class="panel-title">Tahap Pesanan</div>
            <div class="hint" style="margin-bottom:.9rem">Validasi pembayaran otomatis memulai Pemrosesan. Pegawai hanya perlu melakukan Penyerahan ke pengunjung.</div>
            @foreach($tahapList as $index => $tahap)
                @php
                    $selesai = $index < $tahapSaatIni;
                    $aktif = $index === $tahapSaatIni;
                    $warna = $selesai ? '#16A34A' : ($aktif ? '#DC2626' : '#D1D5DB');
                @endphp
                <div style="display:flex;gap:.7rem;align-items:flex-start;padding:.6rem 0;border-bottom:1px solid var(--border)">
                    <span style="flex:0 0 28px;height:28px;border-radius:50%;background:{{ $warna }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem">{{ $selesai ? 'OK' : $index + 1 }}</span>
                    <div>
                        <strong style="font-size:.85rem">{{ $tahap[0] }}</strong>
                        <div class="hint">{{ $tahap[1] }}</div>
                        @if($aktif)<div class="hint" style="color:#DC2626;font-weight:600">Tahap saat ini</div>@endif
                    </div>
                </div>
            @endforeach

            @if($tahapanBerikutnya === \App\Support\OrderStatus::SELESAI)
                <div class="divider"></div>
                <form method="POST" action="{{ route('pegawai.pesanan.serahkan', $transaksi) }}" onsubmit="return confirm('Pesanan sudah diserahkan ke pengunjung?')">
                    @csrf
                    <button class="btn-sm btn-lg btn-green">Serahkan ke pengunjung</button>
                </form>
            @endif

            @if($bolehTolak)
                <form method="POST" action="{{ route('pegawai.pesanan.tolak', $transaksi) }}" onsubmit="return confirm('Tolak pesanan ini? Pelanggan akan mendapat notifikasi alasan.')" style="margin-top:.75rem">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="alasan_tahapan">Alasan penolakan</label>
                        <textarea id="alasan_tahapan" name="alasan" class="form-control" rows="2" minlength="5" maxlength="500" required placeholder="Contoh: Menu habis, silakan pesan menu lain."></textarea>
                    </div>
                    <button class="btn-sm btn-lg btn-red">Tolak pesanan</button>
                </form>
            @endif
        </div>

        <div class="panel">
            <div class="panel-title">Verifikasi Pembayaran</div>
            @if($transaksi->metode_pembayaran === 'qris')
                <div class="hint" style="margin-bottom:.75rem">Periksa gambar bukti transfer, nominal, waktu transaksi, dan nama merchant QRIS sebelum memproses pesanan.</div>
                @if($transaksi->bukti_pembayaran)
                    <a href="{{ $transaksi->bukti_url }}" target="_blank" rel="noopener">
                        <img src="{{ $transaksi->bukti_url }}" alt="Bukti bayar QRIS" class="thumb-lg" style="max-height:340px;object-fit:contain">
                    </a>
                    <div class="hint">Klik gambar untuk memperbesar. Merchant: {{ $pengaturan->qris_nama_merchant ?: '-' }}</div>
                @else
                    <div class="hint">Pelanggan belum mengunggah bukti bayar.</div>
                @endif

                @if($transaksi->status === \App\Support\OrderStatus::MENUNGGU_VERIFIKASI)
                    <div class="divider"></div>
                    <form method="POST" action="{{ route('pegawai.pesanan.verifikasiQris', $transaksi) }}" onsubmit="return confirm('Validasi pembayaran ini? Stok akan dikurangi otomatis.')">
                        @csrf
                        <input type="hidden" name="keputusan" value="valid">
                        <button class="btn-sm btn-lg btn-green">Validasi bukti dan mulai proses</button>
                    </form>
                    <form method="POST" action="{{ route('pegawai.pesanan.verifikasiQris', $transaksi) }}" onsubmit="return confirm('Tolak pembayaran ini? Pelanggan harus mengunggah ulang bukti bayar.')" style="margin-top:.75rem">
                        @csrf
                        <input type="hidden" name="keputusan" value="tolak">
                        <div class="form-group">
                            <label class="form-label" for="alasan">Alasan penolakan</label>
                            <textarea id="alasan" name="alasan" class="form-control" rows="2" minlength="5" maxlength="500" required placeholder="Contoh: Bukti bayar buram, tidak terlihat nominalnya."></textarea>
                        </div>
                        <button class="btn-sm btn-lg btn-red">✕ Tolak pembayaran</button>
                    </form>
                @endif
            @else
                <div class="hint">Syarat: {{ $pengaturan->tunai_syarat ?: '-' }}</div>
                <div class="hint">Instruksi: {{ $pengaturan->tunai_instruksi ?: '-' }}</div>
                @if(in_array($transaksi->status, [\App\Support\OrderStatus::MENUNGGU_PEMBAYARAN, \App\Support\OrderStatus::MENUNGGU_VERIFIKASI], true))
                    <div class="divider"></div>
                    <form method="POST" action="{{ route('pegawai.pesanan.konfirmasiTunai', $transaksi) }}" onsubmit="return confirm('Uang sudah diterima? Pesanan langsung diproses dan stok dikurangi.')">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="uang_diterima">Uang diterima (Rp)</label>
                            <input id="uang_diterima" name="uang_diterima" type="number" min="0" step="1" class="form-control" value="{{ $transaksi->total_harga }}" required>
                        </div>
                        <button class="btn-sm btn-lg btn-green">Konfirmasi uang diterima dan mulai proses</button>
                    </form>
                @endif
            @endif
        </div>

        <div class="panel">
            <div class="panel-title">Notifikasi ke Pelanggan</div>
            @forelse($transaksi->notifikasi as $notif)
                <div style="padding:.5rem 0;border-bottom:1px solid var(--border)">
                    <strong style="font-size:.83rem">{{ $notif->judul }}</strong>
                    <div class="hint">{{ $notif->pesan }}</div>
                    <div class="hint">{{ $notif->created_at?->translatedFormat('d M Y, H:i') }} • {{ strtoupper($notif->channel) }}</div>
                </div>
            @empty
                <div class="hint">Belum ada notifikasi.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
