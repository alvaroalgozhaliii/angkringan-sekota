@extends('admin.layouts.app')
@section('page-title', 'Dashboard')
@section('title', 'Dashboard Owner')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Halo, {{ auth()->user()->name }}</div>
        <div class="page-sub">Ringkasan kondisi Angkringan Sekota hari ini.</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-white">Lihat website publik ↗</a>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Pesanan aktif</div>
        <div class="stat-value red">{{ $pesananAktif }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Menunggu verifikasi bayar</div>
        <div class="stat-value">{{ $menungguVerifikasi }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Omzet hari ini</div>
        <div class="stat-value">Rp{{ number_format($omzetHariIni, 0, ',', '.') }}</div>
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
            <a href="{{ route('admin.pesanan.index') }}" class="btn-sm btn-lg btn-red">Pantau pesanan</a>
            <a href="{{ route('admin.pengaturan.transaksi') }}" class="btn-sm btn-lg btn-white">Pengaturan transaksi</a>
            <a href="{{ route('admin.pelanggan.index') }}" class="btn-sm btn-lg btn-white">Data pelanggan</a>
        </div>
        <div class="divider"></div>
        <div class="hint">Data di halaman ini diambil langsung dari transaksi dan stok yang tersimpan.</div>
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
    </div>
</div>
@endsection
