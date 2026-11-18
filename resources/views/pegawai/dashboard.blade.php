@extends('admin.layouts.app')
@section('page-title', 'Dashboard Pegawai')
@section('title', 'Dashboard Pegawai')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Halo, {{ auth()->user()->name }} 👋</div>
        <div class="page-sub">Ringkasan pekerjaan harian Angkringan Sekota.</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-white">Lihat website publik ↗</a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Menunggu verifikasi bayar</div>
        <div class="stat-value red">{{ $menungguVerifikasi }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Menunggu pembayaran</div>
        <div class="stat-value">{{ $menungguPembayaran }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pesanan diproses</div>
        <div class="stat-value">{{ $diproses }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Produk stok menipis</div>
        <div class="stat-value">{{ $stokMenipis }}</div>
    </div>
</div>

<div class="two-col">
    <div class="panel">
        <div class="panel-title">Aksi cepat</div>
        <div class="btn-row">
            <a href="{{ route('pegawai.pesanan.index') }}" class="btn-sm btn-lg btn-red">Periksa pesanan</a>
            <a href="{{ route('pegawai.kasir.index') }}" class="btn-sm btn-lg btn-white">Buka kasir</a>
            <a href="{{ route('pegawai.produk.index') }}" class="btn-sm btn-lg btn-white">Kelola produk &amp; stok</a>
            <a href="{{ route('pegawai.konten.index') }}" class="btn-sm btn-lg btn-white">Ubah tampilan web</a>
            <a href="{{ route('pegawai.transaksi.edit') }}" class="btn-sm btn-lg btn-white">Pengaturan transaksi</a>
        </div>
        <div class="divider"></div>
        <div class="hint">Setiap perubahan yang Anda simpan otomatis tercatat di halaman Log Aktivitas.</div>
    </div>

    <div class="panel">
        <div class="panel-title">Aktivitas terbaru</div>
        @forelse($logTerbaru as $log)
            <div style="padding:.55rem 0;border-bottom:1px solid var(--border)">
                <strong style="font-size:.83rem">{{ $log->aksi }}</strong>
                <div class="hint">{{ $log->user_nama }} • {{ $log->modul }} • {{ $log->created_at?->translatedFormat('d M Y, H:i') }}</div>
            </div>
        @empty
            <div class="hint">Belum ada aktivitas tercatat.</div>
        @endforelse
        <div style="margin-top:1rem"><a class="action-link" href="{{ route('pegawai.log.index') }}">Lihat semua log →</a></div>
    </div>
</div>
@endsection
