<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pesanan — {{ $profil->nama_usaha }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: {{ $profil->warnaTema() }};
            --red-dark: #9E0C23;
            --cream: #FDF5F0;
            --cream-dark: #F5E8E0;
            --white: #FFFFFF;
            --ink: #1A1A1A;
            --muted: #666666;
            --border: #ECE6E2;
            --shadow: 0 12px 32px rgba(80, 30, 20, .08);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at top left, var(--cream-dark) 0, transparent 42%), var(--cream);
            line-height: 1.55;
            min-height: 100vh;
        }
        a { color: var(--red); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .wrap { max-width: 960px; margin: 0 auto; padding: 28px 18px 64px; }

        .site-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 26px;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-logo {
            width: 46px; height: 46px; border-radius: 50%; background: var(--white);
            border: 2px solid rgba(255,255,255,.7); padding: 3px; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0,0,0,.1);
        }
        .brand-logo img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; display: block; }
        .brand-name { font-size: .82rem; font-weight: 800; line-height: 1.2; color: var(--red); text-transform: uppercase; letter-spacing: .03em; }
        .brand-sub { font-size: .68rem; color: var(--muted); margin-top: 2px; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 7px; padding: 9px 14px;
            border: 1px solid var(--border); border-radius: 999px; background: var(--white);
            color: var(--ink); font: inherit; font-size: .78rem; font-weight: 700; cursor: pointer;
            box-shadow: 0 3px 10px rgba(0,0,0,.03); white-space: nowrap;
        }
        .btn-back:hover { border-color: var(--red); color: var(--red); text-decoration: none; }

        .hero { margin-bottom: 20px; }
        .hero h1 { font-size: 1.75rem; line-height: 1.2; margin: 0 0 5px; font-weight: 800; }
        .hero p { margin: 0; color: var(--muted); font-size: .87rem; }

        .alert {
            display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px;
            border-radius: 12px; margin-bottom: 16px; font-size: .84rem; font-weight: 500;
        }
        .alert span { font-size: 1rem; line-height: 1.2; }
        .alert.ok { background: #DCFCE7; color: #166534; }
        .alert.err { background: #FEE2E2; color: #991B1B; }
        .alert.info { background: #EFF6FF; color: #1E40AF; }
        .alert.warn { background: #FFFBEB; color: #92400E; }

        .card {
            background: var(--white); border: 1px solid var(--border); border-radius: 18px;
            padding: 22px; box-shadow: var(--shadow); margin-bottom: 18px;
        }
        .card-title {
            display: flex; align-items: center; gap: 10px; margin: 0 0 16px;
            font-size: .98rem; font-weight: 800;
        }
        .card-title .icon {
            width: 34px; height: 34px; border-radius: 10px; background: #FFF1F3;
            color: var(--red); display: grid; place-items: center; font-size: .95rem; flex-shrink: 0;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .field label { display: block; font-size: .76rem; font-weight: 700; color: var(--muted); margin-bottom: 6px; }
        .input {
            width: 100%; padding: 11px 13px; border: 1px solid var(--border); border-radius: 11px;
            font: inherit; font-size: .86rem; color: var(--ink); background: var(--white);
            transition: border-color .2s, box-shadow .2s;
        }
        .input:focus { outline: 0; border-color: var(--red); box-shadow: 0 0 0 3px rgba(200,16,46,.12); }
        .search-actions { display: flex; justify-content: flex-end; margin-top: 16px; }
        .btn {
            border: 0; border-radius: 11px; padding: 11px 18px; font: inherit;
            font-size: .84rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            transition: transform .2s, box-shadow .2s, background .2s;
        }
        .btn:hover { transform: translateY(-1px); text-decoration: none; }
        .btn-primary { background: var(--red); color: var(--white); box-shadow: 0 6px 16px rgba(200,16,46,.2); }
        .btn-primary:hover { background: var(--red-dark); }
        .btn-outline { background: var(--white); color: var(--red); border: 1px solid var(--red); }
        .btn-outline:hover { background: #FFF5F6; }
        .btn-wa { background: #16A34A; color: var(--white); }

        .summary-card { padding: 0; overflow: hidden; }
        .summary-top {
            padding: 20px 22px; background: linear-gradient(135deg, #FFF8F5, #FFF1F3);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        }
        .summary-top h2 { margin: 0 0 4px; font-size: 1.1rem; font-weight: 800; color: var(--ink); }
        .summary-meta { font-size: .78rem; color: var(--muted); }
        .badge {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
            border-radius: 999px; font-size: .74rem; font-weight: 800; white-space: nowrap;
        }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-success { background: #DCFCE7; color: #166534; }
        .badge-info { background: #DBEAFE; color: #1E40AF; }
        .badge-primary { background: #EEF2FF; color: #3730A3; }
        .badge-purple { background: #F3E8FF; color: #6B21A8; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }

        .stepper {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 4px;
            padding: 18px 22px 20px;
        }
        .step { position: relative; text-align: center; min-width: 0; }
        .step-line {
            position: absolute; top: 14px; left: 50%; width: 100%; height: 2px;
            background: var(--border); z-index: 0;
        }
        .step:last-child .step-line { display: none; }
        .step.done .step-line { background: #BBF7D0; }
        .step-dot {
            position: relative; z-index: 1; width: 28px; height: 28px; margin: 0 auto 6px;
            border-radius: 50%; display: grid; place-items: center; font-size: .72rem; font-weight: 800;
            background: #F3F4F6; color: #9CA3AF; box-shadow: 0 0 0 3px var(--white), 0 0 0 4px var(--border);
        }
        .step.done .step-dot { background: #DCFCE7; color: #166534; box-shadow: 0 0 0 3px var(--white), 0 0 0 4px #BBF7D0; }
        .step.active .step-dot { background: var(--red); color: var(--white); box-shadow: 0 0 0 4px rgba(200,16,46,.14); }
        .step-label { font-size: .68rem; font-weight: 600; color: var(--muted); }
        .step.active .step-label { color: var(--red); font-weight: 800; }

        .content-grid { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr); gap: 18px; align-items: start; }
        .stack { display: flex; flex-direction: column; gap: 18px; }
        .left-stack { order: 1; }
        .right-stack { order: 2; }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .data-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
        .data-table th, .data-table td { padding: 10px 12px; text-align: left; vertical-align: top; border-bottom: 1px solid var(--border); }
        .data-table thead th {
            background: #FAF7F5; color: var(--muted); font-size: .68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .data-table tbody tr:nth-child(even) { background: #FFFCFA; }
        .data-table tfoot td { background: #FFF5F6; color: var(--red); font-weight: 800; border-top: 2px solid #FEE2E2; }
        .section-subtitle { font-size: .8rem; font-weight: 800; color: var(--muted); margin: 20px 0 10px; }

        .qris-layout { display: grid; grid-template-columns: 156px 1fr; gap: 18px; align-items: start; }
        .qris-preview {
            border: 1px solid var(--border); border-radius: 14px; background: var(--white);
            padding: 10px; text-align: center;
        }
        .preview-img { width: 100%; max-width: 240px; display: block; margin: 0 auto; border-radius: 8px; cursor: zoom-in; }
        .info-list { display: grid; gap: 8px; margin: 0 0 14px; }
        .info-row { display: flex; justify-content: space-between; gap: 12px; font-size: .8rem; padding-bottom: 7px; border-bottom: 1px dashed var(--border); }
        .info-row span:first-child { color: var(--muted); }
        .info-row strong { font-weight: 800; text-align: right; }
        .countdown {
            background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; border-radius: 12px;
            padding: 10px 12px; font-size: .78rem; margin-bottom: 14px;
        }
        .upload-box { border: 1px dashed #F1B9C4; background: #FFF9FA; border-radius: 14px; padding: 14px; }
        .upload-box .input { background: var(--white); }
        .hint { font-size: .72rem; color: var(--muted); margin-top: 5px; }
        .hint.error { color: #991B1B; font-weight: 700; }
        .upload-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .instruction {
            border-left: 4px solid var(--red); background: #FFF8F5; border-radius: 12px;
            padding: 14px; font-size: .82rem;
        }
        .instruction strong { display: block; margin-bottom: 4px; }

        .timeline { list-style: none; margin: 0; padding: 0; }
        .timeline li { position: relative; padding: 0 0 18px 34px; }
        .timeline li:not(:last-child)::before {
            content: ''; position: absolute; left: 11px; top: 26px; bottom: 0; width: 2px; background: var(--border);
        }
        .timeline-dot {
            position: absolute; left: 0; top: 1px; width: 24px; height: 24px; border-radius: 50%;
            background: #FFF1F3; color: var(--red); display: grid; place-items: center; font-size: .7rem; font-weight: 800;
        }
        .timeline li:first-child .timeline-dot { background: var(--red); color: var(--white); }
        .timeline-title { font-size: .82rem; font-weight: 700; }
        .timeline-message { font-size: .78rem; color: #444; margin-top: 2px; }
        .timeline-meta { font-size: .7rem; color: var(--muted); margin-top: 2px; }

        .lightbox {
            display: none; position: fixed; inset: 0; z-index: 6000; background: rgba(0,0,0,.78);
            align-items: center; justify-content: center; padding: 20px;
        }
        .lightbox-box {
            position: relative; max-width: min(92vw, 520px); width: 100%; background: var(--white);
            border-radius: 16px; padding: 18px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,.35);
        }
        .lightbox-close {
            position: absolute; top: 8px; right: 8px; width: 44px; height: 44px; border-radius: 50%;
            border: 1px solid var(--border); background: var(--white); color: var(--ink);
            font: inherit; font-size: 1rem; font-weight: 800; cursor: pointer;
        }
        .lightbox-box img { width: 100%; max-height: min(72vh, 70dvh); object-fit: contain; }

        @media (max-width: 820px) {
            .content-grid { grid-template-columns: 1fr; }
            .left-stack { order: 2; }
            .right-stack { order: 1; }
        }
        @media (max-width: 600px) {
            .wrap { padding: 18px 14px 48px; }
            .site-header { margin-bottom: 20px; }
            .brand-name { font-size: .72rem; }
            .btn-back { min-width: 44px; min-height: 44px; padding: 9px 12px; }
            .btn-back .label { display: none; }
            .hero h1 { font-size: 1.45rem; }
            .form-grid { grid-template-columns: 1fr; }
            .input { font-size: 16px; min-height: 46px; }
            .search-actions .btn { width: 100%; }
            .btn { min-height: 44px; }
            .card { padding: 16px; border-radius: 14px; }
            .summary-top { padding: 18px 16px; }
            .stepper { padding: 16px 12px; }
            .step-label { font-size: .64rem; line-height: 1.35; overflow-wrap: anywhere; }
            .step-dot { width: 28px; height: 28px; }
            .step-line { top: 14px; }
            .qris-layout { grid-template-columns: 1fr; }
            .qris-preview { max-width: 220px; margin: 0 auto; }
            .upload-actions .btn { width: 100%; }
            .data-table { font-size: .76rem; }
            .data-table th, .data-table td { padding: 9px 8px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="site-header">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $profil->nama_usaha }}">
            </div>
            <div>
                <div class="brand-name">{{ $profil->nama_usaha }}</div>
                <div class="brand-sub">Status pesanan online</div>
            </div>
        </div>
        <a class="btn-back" href="{{ route('home') }}">← <span class="label">Kembali ke beranda</span></a>
    </div>

    <div class="hero">
        <h1>Cek Status Pesanan</h1>
        <p>Masukkan nomor pesanan dan nomor WhatsApp yang dipakai saat memesan.</p>
    </div>

    @if(session('success')) <div class="alert ok"><span>✅</span><div>{{ session('success') }}</div></div> @endif
    @if(session('error')) <div class="alert err"><span>⚠️</span><div>{{ session('error') }}</div></div> @endif
    @if($error) <div class="alert err"><span>⚠️</span><div>{{ $error }}</div></div> @endif

    <div class="card">
        <h2 class="card-title"><span class="icon">🔎</span> Lacak pesanan Anda</h2>
        <form method="GET" action="{{ route('pesanan.cek') }}">
            <div class="form-grid">
                <div class="field">
                    <label for="nomor">Nomor Pesanan</label>
                    <input class="input" id="nomor" name="nomor" value="{{ old('nomor', $nomor) }}" placeholder="TRX-20261118-001" required>
                </div>
                <div class="field">
                    <label for="telepon">Nomor WhatsApp</label>
                    <input class="input" id="telepon" name="telepon" value="{{ old('telepon', request('telepon')) }}" placeholder="0812-3456-7890">
                </div>
            </div>
            <div class="search-actions"><button class="btn btn-primary" type="submit">Cek Pesanan →</button></div>
        </form>
    </div>

    @if($transaksi)
        @php
            $status = $transaksi->status;
            $qris = $transaksi->metode_pembayaran === 'qris';
            $badge = \App\Support\OrderStatus::badge($status);
            $bisaUnggah = $qris
                && $status === \App\Support\OrderStatus::MENUNGGU_PEMBAYARAN
                && (! $transaksi->batas_bayar_at || $transaksi->batas_bayar_at->isFuture());
            $langkahLabel = ['Pemrosesan pesanan', 'Penyerahan ke pengunjung'];
            $tahap = \App\Support\OrderStatus::tahapPublik($status);
            $langkahAktif = $tahap >= 0 && $tahap < count($langkahLabel) ? $tahap : -1;
        @endphp

        <div class="card summary-card">
            <div class="summary-top">
                <div>
                    <h2>{{ $transaksi->nomor_transaksi }}</h2>
                    <div class="summary-meta">Dipesan {{ $transaksi->formatted_tanggal_waktu }} atas nama {{ $transaksi->nama_pelanggan ?: 'Pelanggan' }}</div>
                </div>
                <span class="badge badge-{{ $badge }}">{{ $transaksi->status_label }}</span>
            </div>
            <div class="stepper">
                @foreach($langkahLabel as $i => $labelLangkah)
                    <div class="step @if($i < $tahap) done @endif @if($i === $langkahAktif) active @endif">
                        <span class="step-line"></span>
                        <div class="step-dot">{{ $i < $tahap ? '✓' : $i + 1 }}</div>
                        <div class="step-label">{{ $labelLangkah }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($bisaUnggah && $transaksi->batas_bayar_at)
            <div class="alert warn"><span>⏳</span><div>Selesaikan pembayaran sebelum <strong>{{ $transaksi->batas_bayar_at->translatedFormat('d M Y, H:i') }}</strong> (<span id="sisaWaktu" aria-live="polite">menunggu...</span>).</div></div>
        @endif
        @if($status === \App\Support\OrderStatus::MENUNGGU_PEMBAYARAN && ! $qris)
            <div class="alert info"><span>🕒</span><div>Pesanan tunai Anda sudah masuk. Silakan lunasi di kasir saat pengambilan atau pengiriman.</div></div>
        @endif
        @if(in_array($status, [\App\Support\OrderStatus::DIBAYAR, \App\Support\OrderStatus::DITERIMA, \App\Support\OrderStatus::DIPROSES, \App\Support\OrderStatus::DIKIRIM, \App\Support\OrderStatus::SIAP_DIAMBIL], true))
            <div class="alert info"><span>🕒</span><div>Pembayaran valid. Pesanan Anda sedang diproses.</div></div>
        @endif
        @if($status === \App\Support\OrderStatus::SELESAI)
            <div class="alert ok"><span>✓</span><div>Pesanan selesai diserahkan. Terima kasih sudah berbelanja di Angkringan Sekota.</div></div>
        @endif
        @if($status === \App\Support\OrderStatus::DIBATALKAN && $transaksi->alasan_penolakan)
            <div class="alert err"><span>⛔</span><div>Pesanan ditolak: {{ $transaksi->alasan_penolakan }}</div></div>
        @elseif($transaksi->alasan_penolakan)
            <div class="alert err"><span>⚠️</span><div>Bukti bayar ditolak: {{ $transaksi->alasan_penolakan }} Silakan unggah ulang bukti bayar yang lebih jelas.</div></div>
        @endif

        <div class="content-grid">
            <div class="stack left-stack">
                <div class="card">
                    <h2 class="card-title"><span class="icon">🧾</span> Rincian Pesanan</h2>
                    <div class="table-wrap">
                        <table class="data-table">
                            <tbody>
                                <tr><th>Metode pembayaran</th><td>{{ strtoupper($transaksi->metode_pembayaran) }}</td></tr>
                                <tr><th>Cara pesanan</th><td>{{ $transaksi->metode_pengambilan === 'pengiriman' ? 'Dikirim' : 'Ambil di toko' }}</td></tr>
                                @if($transaksi->alamat_pengiriman)<tr><th>Alamat pengiriman</th><td>{{ $transaksi->alamat_pengiriman }}</td></tr>@endif
                                @if($transaksi->catatan)<tr><th>Catatan</th><td>{{ $transaksi->catatan }}</td></tr>@endif
                            </tbody>
                        </table>
                    </div>

                    <div class="section-subtitle">Menu yang dipesan</div>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead><tr><th>Menu</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                            <tbody>
                                @foreach($transaksi->details as $detail)
                                    <tr>
                                        <td>{{ $detail->nama_produk }}</td>
                                        <td>Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->subtotal_format }}</td>
                                    </tr>
                                @endforeach
                                @if($transaksi->metode_pengambilan === 'pengiriman')
                                    <tr><td colspan="3">Ongkos kirim</td><td>Rp{{ number_format($pengaturan->tarif_pengiriman, 0, ',', '.') }}</td></tr>
                                @endif
                            </tbody>
                            <tfoot><tr><td colspan="3">Total</td><td>{{ $transaksi->formatted_total }}</td></tr></tfoot>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <h2 class="card-title"><span class="icon">🕰️</span> Riwayat Status</h2>
                    <ul class="timeline">
                        @forelse($transaksi->notifikasi as $notif)
                            <li>
                                <div class="timeline-dot">•</div>
                                <div class="timeline-title">{{ $notif->judul }}</div>
                                <div class="timeline-message">{{ $notif->pesan }}</div>
                                <div class="timeline-meta">{{ $notif->created_at?->translatedFormat('d M Y, H:i') }}</div>
                            </li>
                        @empty
                            <li><div class="timeline-message">Belum ada riwayat status.</div></li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="stack right-stack">
                @if($bisaUnggah)
                    <div class="card">
                        <h2 class="card-title"><span class="icon">📱</span> Bayar via QRIS</h2>
                        <div class="alert info"><span>ℹ️</span><div>Transfer sesuai nominal, lalu unggah bukti pembayaran. Pegawai akan mengecek manual bukti transaksi sebelum menyetujui pesanan.</div></div>
                        <div class="qris-layout">
                            <div class="qris-preview">
                                @if($pengaturan->qrisUrl())
                                    <img src="{{ $pengaturan->qrisUrl() }}" alt="Kode QRIS" class="preview-img" data-preview>
                                @else
                                    <div class="alert err"><span>⚠️</span><div>Kode QRIS belum tersedia. Silakan hubungi kasir.</div></div>
                                @endif
                            </div>
                            <div>
                                <div class="info-list">
                                    <div class="info-row"><span>Merchant</span><strong>{{ $pengaturan->qris_nama_merchant ?: '-' }}</strong></div>
                                    <div class="info-row"><span>Nominal</span><strong>{{ $transaksi->formatted_total }}</strong></div>
                                    <div class="info-row"><span>Batas bayar</span><strong>{{ $transaksi->batas_bayar_at?->translatedFormat('d M Y, H:i') ?: '-' }}</strong></div>
                                </div>
                                <form id="formUnggahBukti" method="POST" action="{{ route('pesanan.bukti', $transaksi) }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="nomor_transaksi" value="{{ $transaksi->nomor_transaksi }}">
                                    <div class="field" style="margin-bottom:10px">
                                        <label for="telepon-bukti">Nomor WhatsApp pemesan</label>
                                        <input class="input" id="telepon-bukti" name="telepon" value="{{ old('telepon', request('telepon')) }}" required>
                                    </div>
                                    <div class="upload-box">
                                        <label for="bukti_pembayaran">Foto Bukti Transfer</label>
                                        <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" accept="image/jpeg,image/png,image/webp" style="display:none">
                                        <button class="btn btn-outline" id="btnPilihBukti" type="button">📷 Pilih Foto Bukti</button>
                                        <div class="hint" id="namaBukti">Belum ada foto dipilih.</div>
                                        <div class="hint">JPG/PNG/WebP, maksimal 2 MB. Pastikan nominal dan waktu transaksi terlihat jelas.</div>
                                        @error('bukti_pembayaran') <div class="alert err" style="margin-top:8px"><span>⚠️</span><div>{{ $message }}</div></div> @enderror
                                    </div>
                                    <div class="upload-actions"><button class="btn btn-primary" type="submit">Kirim Bukti Bayar</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($status === \App\Support\OrderStatus::MENUNGGU_VERIFIKASI)
                    <div class="card">
                        <h2 class="card-title"><span class="icon">✅</span> Bukti bayar terkirim</h2>
                        <div class="alert info"><span>⏳</span><div>Bukti bayar Anda sedang diverifikasi pegawai. Halaman ini akan menampilkan status terbaru setelah verifikasi.</div></div>
                        @if($transaksi->bukti_url)
                            <div class="qris-preview"><img src="{{ $transaksi->bukti_url }}" alt="Bukti bayar" class="preview-img" data-preview></div>
                        @endif
                    </div>
                @elseif($qris === false && in_array($status, [\App\Support\OrderStatus::MENUNGGU_PEMBAYARAN, \App\Support\OrderStatus::MENUNGGU_VERIFIKASI], true))
                    <div class="card">
                        <h2 class="card-title"><span class="icon">💵</span> Pembayaran Tunai</h2>
                        <div class="instruction">
                            <strong>Instruksi pembayaran</strong>
                            @if($pengaturan->tunai_syarat)<div>{{ $pengaturan->tunai_syarat }}</div>@endif
                            @if($pengaturan->tunai_instruksi)<div>{{ $pengaturan->tunai_instruksi }}</div>@endif
                            <div class="hint">Bayar ke kasir saat pesanan diambil/dikirim. Stok dipotong setelah kasir mengonfirmasi uang diterima.</div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <h2 class="card-title"><span class="icon">💬</span> Butuh bantuan?</h2>
                    <p class="hint" style="margin-top:0">Simpan nomor pesanan Anda, lalu hubungi kasir jika ada kendala dengan pembayaran atau pesanan.</p>
                    @if($transaksi->linkWhatsapp())
                        <a class="btn btn-wa" href="{{ $transaksi->linkWhatsapp() }}" target="_blank" rel="noopener">💬 Hubungi kasir via WhatsApp</a>
                    @endif
                </div>
            </div>
        </div>

        @if(($bisaUnggah && $pengaturan->qrisUrl()) || ($status === \App\Support\OrderStatus::MENUNGGU_VERIFIKASI && $transaksi->bukti_url))
            <div class="lightbox" id="imageLightbox" role="dialog" aria-modal="true" aria-label="Pratinjau gambar">
                <div class="lightbox-box">
                    <button class="lightbox-close" id="tutupImageLightbox" type="button" aria-label="Tutup">✕</button>
                    <img id="imageLightboxFoto" src="" alt="Pratinjau gambar">
                </div>
            </div>
        @endif
    @endif
</div>

@if($bisaUnggah ?? false)
<script>
(function () {
    const el = document.getElementById('sisaWaktu');
    const deadline = Number('{{ $transaksi->batas_bayar_at?->timestamp * 1000 }}');
    if (!el || !deadline) return;
    function tick() {
        const sisa = deadline - Date.now();
        if (sisa <= 0) { el.textContent = 'waktu habis'; return; }
        const m = Math.floor(sisa / 60000);
        const d = Math.floor(m / 1440);
        const j = Math.floor((m % 1440) / 60);
        const menit = m % 60;
        el.textContent = (d ? d + ' hari ' : '') + j + ' jam ' + menit + ' menit';
    }
    tick();
    setInterval(tick, 30000);
})();
</script>
@endif

<script>
(function () {
    const form = document.getElementById('formUnggahBukti');
    const input = document.getElementById('bukti_pembayaran');
    const button = document.getElementById('btnPilihBukti');
    const label = document.getElementById('namaBukti');
    if (form && input && button && label) {
        button.addEventListener('click', () => input.click());
        input.addEventListener('change', () => {
            label.classList.remove('error');
            label.textContent = input.files?.length ? input.files[0].name : 'Belum ada foto dipilih.';
        });
        form.addEventListener('submit', (event) => {
            if (!input.files?.length) {
                event.preventDefault();
                label.classList.add('error');
                label.textContent = '⚠️ Pilih foto bukti terlebih dahulu.';
                input.click();
            }
        });
    }

    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('imageLightboxFoto');
    const close = document.getElementById('tutupImageLightbox');
    if (lightbox && lightboxImage && close) {
        const tutup = () => {
            lightbox.style.display = 'none';
            lightboxImage.src = '';
        };
        document.querySelectorAll('[data-preview]').forEach((gambar) => {
            gambar.addEventListener('click', () => {
                lightboxImage.src = gambar.src;
                lightbox.style.display = 'flex';
            });
        });
        close.addEventListener('click', tutup);
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) tutup();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') tutup();
        });
    }
})();
</script>
</body>
</html>
