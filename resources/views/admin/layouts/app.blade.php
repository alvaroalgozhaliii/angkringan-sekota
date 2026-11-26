<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Admin Angkringan Sekota</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body>
<a class="skip-link" href="#main-content">Lewati ke konten utama</a>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebar" aria-label="Navigasi utama">
    <div class="sidebar-brand">
        <div class="sidebar-logo-title">Angkringan<br>Sekota</div>
        <div class="sidebar-logo-sub">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->isOwner() ? 'Admin sistem' : 'Panel operasional') }}</div>
    </div>
    <div class="sidebar-nav" id="sidebarNav">
        <div class="sidebar-indicator" id="sidebarIndicator"></div>
        @if(auth()->user()->isSuperAdmin())
        <div class="nav-group-label">Pengelolaan</div>
        <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pengguna.*')) aria-current="page" @endif>Manajemen akun dan role</a>
        <a href="{{ route('admin.pengaturan.transaksi') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pengaturan.*')) aria-current="page" @endif>Pengaturan transaksi</a>
        <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pesanan.*')) aria-current="page" @endif>Pantau pesanan</a>
        <a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pelanggan.*')) aria-current="page" @endif>Data pelanggan</a>
        <a href="{{ route('admin.profil.index') }}" class="{{ request()->routeIs('admin.profil.*') ? 'active' : '' }}" @if(request()->routeIs('admin.profil.*')) aria-current="page" @endif>Profil Usaha</a>
        <div class="nav-group-label">Lainnya</div>
        <a href="{{ route('admin.ulasan.index') }}" class="{{ request()->routeIs('admin.ulasan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.ulasan.*')) aria-current="page" @endif>Ulasan Pengunjung</a>
        @elseif(auth()->user()->isOwner())
        <div class="nav-group-label">Ringkasan</div>
        <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" @if(request()->routeIs('owner.dashboard')) aria-current="page" @endif>Dashboard</a>
        <div class="nav-group-label">Pengelolaan</div>
        <a href="{{ route('admin.pengaturan.transaksi') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pengaturan.*')) aria-current="page" @endif>Pengaturan transaksi</a>
        <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pesanan.*')) aria-current="page" @endif>Pantau pesanan</a>
        <a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.pelanggan.*')) aria-current="page" @endif>Data pelanggan</a>
        @else
        <div class="nav-group-label">Operasional</div>
        <a href="{{ route('pegawai.dashboard') }}" class="{{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}" @if(request()->routeIs('pegawai.dashboard')) aria-current="page" @endif>Dashboard</a>
        <a href="{{ route('pegawai.kasir.index') }}" class="{{ request()->routeIs('pegawai.kasir.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.kasir.*')) aria-current="page" @endif>Kasir</a>
        <a href="{{ route('pegawai.pesanan.index') }}" class="{{ request()->routeIs('pegawai.pesanan.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.pesanan.*')) aria-current="page" @endif>Pesanan dan Pembayaran
            @php
                $totalPending = \App\Models\Transaksi::whereIn('status', [
                    \App\Support\OrderStatus::MENUNGGU_PERSETUJUAN,
                    \App\Support\OrderStatus::MENUNGGU_VERIFIKASI,
                    \App\Support\OrderStatus::MENUNGGU_PEMBAYARAN,
                    \App\Support\OrderStatus::DIBAYAR,
                    \App\Support\OrderStatus::DITERIMA,
                    \App\Support\OrderStatus::DIPROSES,
                    \App\Support\OrderStatus::DIKIRIM,
                    \App\Support\OrderStatus::SIAP_DIAMBIL,
                ])->count();
            @endphp
            @if($totalPending > 0)
                <span class="badge-menu">{{ $totalPending }}</span>
            @endif
        </a>
        <a href="{{ route('pegawai.produk.index') }}" class="{{ request()->routeIs('pegawai.produk.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.produk.*')) aria-current="page" @endif>Produk dan Stok</a>
        <div class="nav-group-label">Pengaturan</div>
        <a href="{{ route('pegawai.konten.index') }}" class="{{ request()->routeIs('pegawai.konten.*', 'pegawai.banner.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.konten.*', 'pegawai.banner.*')) aria-current="page" @endif>Tampilan Website</a>
        <a href="{{ route('pegawai.transaksi.edit') }}" class="{{ request()->routeIs('pegawai.transaksi.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.transaksi.*')) aria-current="page" @endif>Metode Transaksi</a>
        <a href="{{ route('pegawai.log.index') }}" class="{{ request()->routeIs('pegawai.log.*') ? 'active' : '' }}" @if(request()->routeIs('pegawai.log.*')) aria-current="page" @endif>Log Aktivitas</a>
        <a href="{{ route('admin.ulasan.index') }}" class="{{ request()->routeIs('admin.ulasan.*') ? 'active' : '' }}" @if(request()->routeIs('admin.ulasan.*')) aria-current="page" @endif>Ulasan Pengunjung
            @php $pendingUlasan = \App\Models\Testimonial::where('is_active', 0)->count(); @endphp
            @if($pendingUlasan > 0)
                <span class="badge-menu">{{ $pendingUlasan }}</span>
            @endif
        </a>
        @endif

    </div>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <strong>{{ auth()->user()->name }}</strong>
            {{ auth()->user()->roleLabel() }}
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</nav>

<!-- MAIN -->
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" type="button" title="Buka menu" aria-label="Buka menu navigasi" aria-controls="sidebar" aria-expanded="false">
                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                <span class="hamburger-label">Menu</span>
            </button>
            <div class="topbar-title-wrap">
                <h1 class="topbar-title">
                    @yield('page-icon')
                    @yield('page-title', 'Dashboard')
                </h1>
                @hasSection('page-subtitle')
                    <div class="topbar-sub">@yield('page-subtitle')</div>
                @endif
            </div>
        </div>

        <div class="topbar-actions">
            <div class="clock-pill" id="digitalClock" title="Waktu operasional (WIB)">
                <span class="clock-time" id="clockTime">--:-- WIB</span>
                <span class="clock-sep">•</span>
                <span id="clockDate">-- --- ----</span>
            </div>

            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn-sm btn-outline-red" title="Buka website pengunjung di tab baru">Lihat web profil</a>

            <a href="{{ route('admin.profil-akun.edit') }}" class="user-pill" title="Buka profil pengguna">
                <span>{{ auth()->user()->name }}</span>
                <span class="user-pill-role">({{ auth()->user()->roleLabel() }})</span>
            </a>
        </div>
    </header>

    <main id="main-content" class="content">
        @if(session('success'))
            <div class="alert alert-success" role="status"><span class="alert-status">Berhasil</span> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" role="alert"><span class="alert-status">Gagal</span> {{ session('error') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="alert alert-error" role="alert">
                <div><span class="alert-status">Periksa kembali input Anda:</span></div>
                <ul style="margin:.35rem 0 0 1.1rem;padding:0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
