@extends('admin.layouts.app')
@section('page-title', 'Dashboard')
@section('title', 'Dashboard Owner')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Halo, {{ auth()->user()->name }}</h2>
        <div class="page-sub">Ringkasan kondisi Angkringan Sekota hari ini.</div>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-white">Lihat website publik</a>
</div>

@include('partials.ringkasan-dashboard', ['ruteAntrian' => 'admin.pesanan.index'])

<div class="two-col">
    <div class="panel">
        <h2 class="panel-title">Aksi cepat</h2>
        <div class="btn-row">
            <a href="{{ route('admin.pesanan.index') }}" class="btn-sm btn-lg btn-red">Pantau pesanan</a>
            <a href="{{ route('admin.pengaturan.transaksi') }}" class="btn-sm btn-lg btn-white">Pengaturan transaksi</a>
            <a href="{{ route('admin.pelanggan.index') }}" class="btn-sm btn-lg btn-white">Data pelanggan</a>
        </div>
        <div class="divider"></div>
        <div class="hint">Stok menipis saat ini: {{ $stokMenipis }} produk. Data di halaman ini diambil langsung dari transaksi dan stok yang tersimpan.</div>
    </div>

    <div class="panel">
        <h2 class="panel-title">Aktivitas terbaru</h2>
        @forelse($logTerbaru as $log)
            <div style="padding:.55rem 0;border-bottom:1px solid var(--border)">
                <strong style="font-size:.83rem">{{ $log->aksi }}</strong>
                <div class="hint">{{ $log->user_nama }} • {{ $log->modul }} • {{ $log->created_at?->translatedFormat('d M Y, H:i') }}</div>
            </div>
        @empty
            <div class="hint">Belum ada aktivitas tercatat. Buka pesanan untuk melihat aktivitas.</div>
        @endforelse
    </div>
</div>
@endsection
