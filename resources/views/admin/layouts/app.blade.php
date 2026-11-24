<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Angkringan Sekota</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --red:#C8102E;
            --red-dark:#9E0C23;
            --red-light:#E8203A;
            --sidebar-w:220px;
            --bg:#F4F5F7;
            --white:#fff;
            --border:#E5E7EB;
            --text:#1F2937;
            --muted:#6B7280;
            --gray-100:#F3F4F6;
            --gray-200:#E5E7EB;
        }
        body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}

        /* ── SIDEBAR ── */
        .sidebar{width:var(--sidebar-w);background:var(--red);min-height:100dvh;position:fixed;top:0;left:0;bottom:0;display:flex;flex-direction:column;z-index:50;transition:transform .3s;box-shadow:4px 0 24px rgba(0,0,0,.12)}
        .sidebar-backdrop{position:fixed;inset:0;background:rgba(17,17,20,.55);backdrop-filter:blur(2px);z-index:45;opacity:0;visibility:hidden;transition:opacity .3s,visibility .3s}
        .sidebar-backdrop.show{opacity:1;visibility:visible}
        .sidebar-brand{padding:1.5rem 1.25rem 1rem;border-bottom:1px solid rgba(255,255,255,.15)}
        .sidebar-logo-title{font-size:1.1rem;font-weight:800;color:#fff;text-transform:uppercase;line-height:1.1;letter-spacing:.03em}
        .sidebar-logo-sub{font-size:.7rem;font-weight:400;color:rgba(255,255,255,.7);margin-top:.15rem}
        .sidebar-nav{flex:1;padding:.75rem 0;overflow-y:auto}
        .sidebar-nav a{display:flex;align-items:center;gap:.7rem;padding:.65rem 1.25rem;color:rgba(255,255,255,.8);text-decoration:none;font-size:.85rem;font-weight:500;transition:all .2s;border-left:3px solid transparent}
        .sidebar-nav a:hover{background:rgba(255,255,255,.12);color:#fff;border-left-color:rgba(255,255,255,.5)}
        .sidebar-nav a.active{background:rgba(255,255,255,.18);color:#fff;border-left-color:#fff;font-weight:600}
        .sidebar-nav .nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}
        .sidebar-nav .badge-menu{margin-left:auto;background:rgba(255,255,255,.25);border-radius:20px;padding:1px 8px;font-size:.7rem;font-weight:700}
        .sidebar-footer{padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.15)}
        .sidebar-user{font-size:.8rem;color:rgba(255,255,255,.75);margin-bottom:.6rem;line-height:1.4}
        .sidebar-user strong{display:block;color:#fff;font-weight:600}
        .btn-logout{display:block;width:100%;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:.45rem .75rem;border-radius:6px;font-size:.8rem;font-weight:500;cursor:pointer;text-align:center;transition:background .2s}
        .btn-logout:hover{background:rgba(255,255,255,.25)}

        /* ── MAIN ── */
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;width:calc(100% - var(--sidebar-w));max-width:calc(100vw - var(--sidebar-w));overflow-x:hidden}
        .topbar{background:var(--white);border-bottom:1px solid var(--border);padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;position:sticky;top:0;z-index:40;flex-wrap:wrap}
        .topbar-left{display:flex;align-items:center;gap:.75rem}
        .topbar-title-wrap{display:flex;flex-direction:column}
        .topbar-title{font-size:1.3rem;font-weight:800;color:var(--red);letter-spacing:.02em;line-height:1.2;display:flex;align-items:center;gap:.4rem}
        .topbar-sub{font-size:.75rem;color:var(--muted);font-weight:400;margin-top:.1rem}
        .topbar-actions{display:flex;align-items:center;gap:.85rem;margin-left:auto}

        /* Clock */
        .clock-pill{display:flex;align-items:center;gap:.45rem;background:#F9FAFB;border:1px solid var(--border);padding:.35rem .75rem;border-radius:20px;font-size:.78rem;color:var(--text);font-weight:500}
        .clock-time{font-weight:700;color:var(--red)}
        .clock-sep{color:var(--border)}

        /* User Pill */
        .user-pill{display:inline-flex;align-items:center;gap:.4rem;background:#F9FAFB;border:1px solid var(--border);padding:.35rem .75rem;border-radius:20px;font-size:.78rem;font-weight:600;color:var(--text);min-width:0;max-width:100%}
        .user-pill span:nth-child(2){min-width:0;overflow:hidden;text-overflow:ellipsis}
        .user-pill-role{color:var(--muted);font-size:.72rem;font-weight:400}

        /* DB Badge */
        .badge-db{display:inline-flex;align-items:center;gap:.35rem;background:#D1FAE5;color:#065F46;padding:.3rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600}
        .badge-db-dot{width:7px;height:7px;background:#10B981;border-radius:50%;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

        .btn-sm{padding:.4rem .9rem;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:.35rem;transition:all .2s}
        .btn-outline-red{background:transparent;border:1.5px solid var(--red);color:var(--red)}
        .btn-outline-red:hover{background:var(--red);color:#fff}
        .content{padding:1.5rem;flex:1}

        /* ── CARDS ── */
        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.75rem}
        .stat-card{background:var(--white);border-radius:12px;padding:1.25rem 1.4rem;border:1px solid var(--border);box-shadow:0 1px 4px rgba(0,0,0,.04)}
        .stat-label{font-size:.75rem;color:var(--muted);font-weight:500;margin-bottom:.4rem}
        .stat-value{font-size:1.6rem;font-weight:800;color:var(--text);line-height:1}
        .stat-value.red{color:var(--red)}

        /* ── PANELS ── */
        .panel{background:var(--white);border-radius:14px;padding:1.5rem;border:1px solid var(--border);box-shadow:0 1px 4px rgba(0,0,0,.04);margin-bottom:1.5rem}
        .panel-title{font-size:1rem;font-weight:700;color:var(--red);margin-bottom:1.2rem;padding-bottom:.6rem;border-bottom:2px solid #FEE2E2}
        .panel-title-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.2rem;padding-bottom:.6rem;border-bottom:2px solid #FEE2E2}
        .panel-title-row .panel-title{margin-bottom:0;padding-bottom:0;border-bottom:none}
        .two-col{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem}

        /* ── FORMS ── */
        .form-group{margin-bottom:1rem}
        .form-label{display:block;font-size:.8rem;font-weight:600;color:var(--red);margin-bottom:.35rem}
        .form-control{width:100%;padding:.6rem .85rem;border:1.5px solid var(--border);border-radius:8px;font-size:.88rem;font-family:'Poppins',sans-serif;color:var(--text);transition:border .2s;background:#fff}
        .form-control:focus{outline:none;border-color:var(--red);box-shadow:0 0 0 3px rgba(200,16,46,.08)}
        textarea.form-control{resize:vertical;min-height:100px}
        .btn-red{background:var(--red);color:#fff;border:none}
        .btn-red:hover{background:var(--red-dark)}
        .btn-white{background:#fff;border:1.5px solid var(--border);color:var(--text)}
        .btn-white:hover{border-color:#9CA3AF}
        .btn-green{background:#059669;color:#fff;border:none}
        .btn-green:hover{background:#047857}
        .btn-row{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.5rem}
        .btn-lg{padding:.7rem 1.5rem;font-size:.9rem}

        /* ── TABLE ── */
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;max-width:100%}
        table{width:100%;border-collapse:collapse;font-size:.85rem}
        thead th{text-align:left;padding:.7rem 1rem;font-weight:600;color:var(--muted);font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid var(--border)}
        tbody td{padding:.8rem 1rem;border-bottom:1px solid var(--border);vertical-align:middle}
        tbody tr:last-child td{border-bottom:none}
        tbody tr:hover{background:#FAFAFA}
        .badge{display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:20px;font-size:.73rem;font-weight:600}
        .badge-green{background:#D1FAE5;color:#065F46}
        .badge-gray{background:#F3F4F6;color:#6B7280}
        .badge-red{background:#FEE2E2;color:#991B1B}
        .badge-yellow{background:#FEF3C7;color:#92400E}
        .price-pill{display:inline-flex;align-items:center;padding:.2rem .7rem;border-radius:20px;background:var(--red);color:#fff;font-size:.75rem;font-weight:700}
        .action-link{color:var(--red);font-weight:600;font-size:.82rem;text-decoration:none;transition:opacity .2s}
        .action-link:hover{opacity:.7;text-decoration:underline}
        .action-link.del{color:#DC2626}
        .badge-blue{background:#DBEAFE;color:#1E40AF}
        .badge-purple{background:#EDE9FE;color:#5B21B6}
        .badge-warning{background:#FEF3C7;color:#92400E}
        .badge-info{background:#DBEAFE;color:#1E40AF}
        .badge-primary{background:#EDE9FE;color:#5B21B6}
        .badge-danger{background:#FEE2E2;color:#991B1B}
        .badge-teal{background:#CCFBF1;color:#115E59}
        .filter-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:.75rem;align-items:end}
        .filter-grid .form-group{margin-bottom:0}
        .thumb{width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--border);background:#F3F4F6}
        .thumb-lg{width:100%;max-width:420px;border-radius:10px;border:1px solid var(--border)}
        .hint{font-size:.75rem;color:var(--muted);margin-top:.3rem;line-height:1.5}
        .log-filter-panel{margin-bottom:1rem;padding:1rem 1.1rem}
        .log-filters{display:grid;grid-template-columns:minmax(220px,2fr) repeat(4,minmax(120px,1fr)) auto;gap:.75rem;align-items:end}
        .log-filter-actions{display:flex;align-items:center;gap:.5rem}
        .log-filter-actions .btn-sm{min-height:38px}
        .log-actor{font-weight:600;color:var(--text)}
        .log-role{font-size:.75rem;color:var(--muted);white-space:nowrap}
        .log-summary{max-width:420px;font-size:.78rem;line-height:1.5;color:var(--text);overflow-wrap:anywhere}
        .log-detail{margin-top:.35rem;max-width:420px}
        .log-detail summary{cursor:pointer;color:var(--red);font-size:.75rem;font-weight:600;list-style:none;min-height:24px;display:inline-flex;align-items:center}
        .log-detail summary::-webkit-details-marker{display:none}
        .log-detail summary::before{content:'+';font-weight:700;margin-right:.35rem}
        .log-detail[open] summary::before{content:'-'}
        .log-detail summary:focus-visible{outline:2px solid var(--red);outline-offset:2px;border-radius:4px}
        .log-box{background:var(--gray-100);border:1px solid var(--border);border-left:3px solid var(--red);border-radius:8px;padding:.55rem .7rem;margin-top:.35rem;max-width:100%}
        .log-box-label{display:block;font-size:.68rem;font-weight:700;color:var(--red);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem}
        .log-box-row + .log-box-row{margin-top:.25rem}
        .log-box-value{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.72rem;color:var(--text);line-height:1.55;white-space:pre-wrap;word-break:break-word}
        .log-box-value strong{font-weight:700;color:var(--muted)}
        .pagination-bar{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border)}
        .pagination-info{font-size:.78rem;color:var(--muted)}
        .pagination-info strong{color:var(--text);font-weight:700}
        .pagination-pages{display:flex;align-items:center;gap:.35rem;flex-wrap:wrap}
        .pagination-btn{min-width:36px;height:36px;padding:0 .6rem;border:1px solid var(--border);border-radius:8px;background:var(--white);color:var(--text);display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .2s}
        .pagination-btn:hover:not(.active):not(.disabled){border-color:var(--red);color:var(--red);background:#FEF2F2}
        .pagination-btn.active{background:var(--red);border-color:var(--red);color:var(--white)}
        .pagination-btn.disabled{color:#9CA3AF;background:var(--gray-100);cursor:not-allowed}
        .pagination-btn:focus-visible{outline:2px solid var(--red);outline-offset:2px}
        .pagination-ellipsis{padding:0 .3rem;color:var(--muted);font-size:.78rem}
        .field-error{font-size:.75rem;color:#991B1B;margin-top:.3rem}
        .divider{height:1px;background:var(--border);margin:1.25rem 0}
        .link-wa{color:#059669;font-weight:600;text-decoration:none}

        /* ── ALERTS ── */
        .alert{padding:.85rem 1.1rem;border-radius:8px;font-size:.85rem;margin-bottom:1rem;display:flex;align-items:center;gap:.6rem}
        .alert-success{background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0}
        .alert-error{background:#FEE2E2;color:#991B1B;border:1px solid #FECACA}

        /* ── PAGE HEADER ── */
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
        .page-title{font-size:1.1rem;font-weight:700;color:var(--text)}
        .page-sub{font-size:.8rem;color:var(--muted);margin-top:.1rem}

        /* ── MOBILE ── */
        .hamburger{display:none;background:none;border:none;font-size:1.3rem;cursor:pointer;padding:4px;min-width:44px;min-height:44px;place-items:center;color:var(--text);border-radius:8px}
        .hamburger:focus-visible{outline:2px solid var(--red);outline-offset:2px}
        @media(max-width:1280px){
            .topbar-actions .badge-db{display:none}
        }
        @media(max-width:1024px){
            .topbar-actions .clock-pill,
            .topbar-actions .badge-db{display:none}
        }
        @media(max-width:900px){
            .filter-grid{grid-template-columns:1fr 1fr}
            .sidebar{transform:translateX(-100%);height:100dvh}
            .sidebar.open{transform:translateX(0)}
            .sidebar-backdrop.show{visibility:visible;opacity:1}
            body{display:block;width:100%;overflow-x:hidden}
            body.sidebar-open{overflow:hidden}
            .main-wrap{margin-left:0;width:100%;max-width:100%;min-width:0}
            .hamburger{display:grid}
            .stat-grid{grid-template-columns:repeat(2,minmax(0,1fr)) !important;gap:.75rem}
            .two-col{grid-template-columns:1fr}
            .content{padding:1rem;min-width:0}
            .topbar{padding:.75rem 1rem;align-items:flex-start;gap:.65rem}
            .topbar-left{min-width:0;width:100%;align-items:flex-start}
            .topbar-title-wrap{min-width:0;max-width:calc(100% - 2.5rem)}
            .topbar-title{font-size:1.08rem;overflow-wrap:anywhere}
            .topbar-actions{width:100%;margin:0;display:flex;flex-wrap:wrap;justify-content:flex-start;gap:.5rem}
            .topbar-actions .btn-sm{min-height:38px}
            .user-pill{max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
            .pagination-bar{justify-content:center}
            .pagination-info{width:100%;text-align:center}
            .page-header{align-items:flex-start;gap:.75rem;flex-wrap:wrap}
            .page-header > :first-child{min-width:0;flex:1 1 100%}
            .page-header .btn-sm{max-width:100%}
            .panel{padding:1rem;max-width:100% !important}
            .order-filters{grid-template-columns:repeat(2,minmax(0,1fr)) !important}
            .order-filters > div:first-child{grid-column:1 / -1}
            .order-filters > button{min-height:42px;justify-content:center}
            .log-filters{grid-template-columns:repeat(2,minmax(0,1fr))}
            .log-filter-search,.log-filter-actions{grid-column:1 / -1}
            .log-filter-actions .btn-sm{flex:1 1 auto;justify-content:center;min-height:44px}
            .log-detail summary{min-height:44px}
            .message-meta-grid{grid-template-columns:1fr !important}
            input,select,textarea,button{max-width:100%}
            .alert{align-items:flex-start;overflow-wrap:anywhere}
        }
        @media(max-width:480px){
            .filter-grid{grid-template-columns:1fr}
            .content{padding:.75rem}
            .topbar{padding:.65rem .75rem}
            .topbar-actions{gap:.4rem}
            .topbar-actions .btn-sm{font-size:.72rem;padding:.4rem .6rem}
            .user-pill{font-size:.72rem;padding:.35rem .55rem;flex:1 1 auto;width:100%}
            .pagination-btn{min-width:38px}
            .stat-grid{grid-template-columns:1fr 1fr !important}
            .stat-card{padding:.9rem}
            .stat-value{font-size:1.35rem}
            .panel{padding:.85rem;border-radius:10px}
            .order-filters{grid-template-columns:1fr !important}
            .order-filters > div:first-child{grid-column:auto}
            .log-filters{grid-template-columns:1fr}
            .log-filter-search,.log-filter-actions{grid-column:auto}
            .btn-row .btn-sm{flex:1 1 auto;justify-content:center}
            .form-control,input,select,textarea{font-size:16px;max-width:100%}
            .form-group label{overflow-wrap:anywhere}
        }
        @media(max-width:360px){
            .stat-grid{grid-template-columns:1fr !important}
            .topbar-title{font-size:1rem}
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo-title">Angkringan<br>Sekota</div>
        <div class="sidebar-logo-sub">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : (auth()->user()->isOwner() ? 'Admin sistem' : 'Panel operasional') }}</div>
    </div>
    <div class="sidebar-nav">
        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Manajemen akun &amp; role
        </a>
        <a href="{{ route('admin.pengaturan.transaksi') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
            <span class="nav-icon">💳</span> Pengaturan transaksi
        </a>
        <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
            <span class="nav-icon">🧾</span> Pantau pesanan
        </a>
        <a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
            <span class="nav-icon">🧑</span> Data pelanggan
        </a>
        <a href="{{ route('admin.profil.index') }}" class="{{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Profil Usaha
        </a>
        <a href="{{ route('admin.ulasan.index') }}" class="{{ request()->routeIs('admin.ulasan.*') ? 'active' : '' }}">
            <span class="nav-icon">⭐</span> Ulasan Pengunjung
        </a>
        @elseif(auth()->user()->isOwner())
        <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">▦</span> Dashboard
        </a>
        <a href="{{ route('admin.pengaturan.transaksi') }}" class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
            <span class="nav-icon">💳</span> Pengaturan transaksi
        </a>
        <a href="{{ route('admin.pesanan.index') }}" class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
            <span class="nav-icon">🧾</span> Pantau pesanan
        </a>
        <a href="{{ route('admin.pelanggan.index') }}" class="{{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
            <span class="nav-icon">🧑</span> Data pelanggan
        </a>
        @else
        <a href="{{ route('pegawai.kasir.index') }}" class="{{ request()->routeIs('pegawai.kasir.*') ? 'active' : '' }}">
            <span class="nav-icon">🧮</span> Kasir
        </a>
        <a href="{{ route('pegawai.pesanan.index') }}" class="{{ request()->routeIs('pegawai.pesanan.*') ? 'active' : '' }}">
            <span class="nav-icon">🧾</span> Pesanan &amp; Pembayaran
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
        <a href="{{ route('pegawai.produk.index') }}" class="{{ request()->routeIs('pegawai.produk.*') ? 'active' : '' }}">
            <span class="nav-icon">🍜</span> Produk &amp; Stok
        </a>
        <a href="{{ route('pegawai.konten.index') }}" class="{{ request()->routeIs('pegawai.konten.*', 'pegawai.banner.*') ? 'active' : '' }}">
            <span class="nav-icon">🎨</span> Tampilan Website
        </a>
        <a href="{{ route('pegawai.transaksi.edit') }}" class="{{ request()->routeIs('pegawai.transaksi.*') ? 'active' : '' }}">
            <span class="nav-icon">💳</span> Metode Transaksi
        </a>
        <a href="{{ route('pegawai.log.index') }}" class="{{ request()->routeIs('pegawai.log.*') ? 'active' : '' }}">
            <span class="nav-icon">📜</span> Log Aktivitas
        </a>
        <a href="{{ route('admin.ulasan.index') }}" class="{{ request()->routeIs('admin.ulasan.*') ? 'active' : '' }}">
            <span class="nav-icon">⭐</span> Ulasan Pengunjung
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
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger" type="button" title="Buka Menu" aria-label="Buka menu navigasi" aria-controls="sidebar" aria-expanded="false">☰</button>
            <div class="topbar-title-wrap">
                <div class="topbar-title">
                    @yield('page-icon')
                    @yield('page-title', 'Dashboard')
                </div>
                @hasSection('page-subtitle')
                    <div class="topbar-sub">@yield('page-subtitle')</div>
                @endif
            </div>
        </div>

        <div class="topbar-actions">
            <!-- Digital Clock Live -->
            <div class="clock-pill" id="digitalClock" title="Waktu Operasional">
                <span style="font-size:.9rem">🕒</span>
                <span class="clock-time" id="clockTime">--:-- WIB</span>
                <span class="clock-sep">•</span>
                <span id="clockDate">-- --- ----</span>
            </div>

            <!-- Database Indicator -->
            <span class="badge-db" title="Status koneksi basis data">
                <span class="badge-db-dot"></span>
                Database bersama: tersambung
            </span>

            <!-- Tombol Lihat Web Profil -->
            <a href="{{ route('home') }}" target="_blank" class="btn-sm btn-outline-red" title="Buka website pengunjung di tab baru">
                👁 Lihat Web Profil
            </a>

            <!-- User Badge / Pill -->
            <a href="{{ route('admin.profil-akun.edit') }}" class="user-pill" title="Buka profil pengguna" style="text-decoration:none">
                <span>👤</span>
                <span>{{ auth()->user()->name }}</span>
                <span class="user-pill-role">({{ auth()->user()->roleLabel() }})</span>
            </a>

        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="alert alert-error">
                <div>❌ Periksa kembali input Anda:</div>
                <ul style="margin:.35rem 0 0 1.1rem;padding:0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const backdrop  = document.getElementById('sidebarBackdrop');

    const setSidebar = (open) => {
        if (!sidebar) return;
        sidebar.classList.toggle('open', open);
        backdrop?.classList.toggle('show', open);
        document.body.classList.toggle('sidebar-open', open);
        hamburger?.setAttribute('aria-expanded', String(open));
        if (open && window.matchMedia('(max-width: 900px)').matches) {
            sidebar.querySelector('a')?.focus();
        }
    };

    hamburger?.addEventListener('click', (e) => {
        e.stopPropagation();
        setSidebar(!sidebar.classList.contains('open'));
    });
    backdrop?.addEventListener('click', () => setSidebar(false));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar?.classList.contains('open')) {
            setSidebar(false);
            hamburger?.focus();
        }
    });
    document.addEventListener('click', (e) => {
        if (sidebar?.classList.contains('open') && !sidebar.contains(e.target) && !hamburger?.contains(e.target)) {
            setSidebar(false);
        }
    });

    // Jam Digital Real-time
    function updateClock() {
        const timeEl = document.getElementById('clockTime');
        const dateEl = document.getElementById('clockDate');
        if (!timeEl || !dateEl) return;

        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeEl.textContent = `${hours}:${minutes} WIB`;

        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const dayName = days[now.getDay()];
        const day = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();

        dateEl.textContent = `${dayName}, ${day} ${month} ${year}`;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@stack('scripts')
</body>
</html>
