<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Admin Angkringan Sekota</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--red:#C8102E;--red-dark:#9E0C23;--red-light:#E8203A;--sidebar-w:220px;--bg:#F4F5F7;--white:#fff;--border:#E5E7EB;--text:#1F2937;--muted:#6B7280}
        body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);display:flex;min-height:100vh}

        /* ── SIDEBAR ── */
        .sidebar{width:var(--sidebar-w);background:var(--red);min-height:100vh;position:fixed;top:0;left:0;bottom:0;display:flex;flex-direction:column;z-index:50;transition:transform .3s}
        .sidebar-brand{padding:1.5rem 1.25rem 1rem;border-bottom:1px solid rgba(255,255,255,.15)}
        .sidebar-logo-title{font-size:1.1rem;font-weight:800;color:#fff;text-transform:uppercase;line-height:1.1;letter-spacing:.03em}
        .sidebar-logo-sub{font-size:.7rem;font-weight:400;color:rgba(255,255,255,.7);margin-top:.15rem}
        .sidebar-nav{flex:1;padding:.75rem 0;overflow-y:auto}
        .sidebar-nav a{display:flex;align-items:center;gap:.7rem;padding:.65rem 1.25rem;color:rgba(255,255,255,.8);text-decoration:none;font-size:.85rem;font-weight:500;transition:all .2s;border-left:3px solid transparent}
        .sidebar-nav a:hover{background:rgba(255,255,255,.12);color:#fff;border-left-color:rgba(255,255,255,.5)}
        .sidebar-nav a.active{background:rgba(255,255,255,.18);color:#fff;border-left-color:#fff;font-weight:600}
        .sidebar-nav .nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.15)}
        .sidebar-user{font-size:.8rem;color:rgba(255,255,255,.75);margin-bottom:.6rem;line-height:1.4}
        .sidebar-user strong{display:block;color:#fff;font-weight:600}
        .btn-logout{display:block;width:100%;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:.45rem .75rem;border-radius:6px;font-size:.8rem;font-weight:500;cursor:pointer;text-align:center;transition:background .2s}
        .btn-logout:hover{background:rgba(255,255,255,.25)}

        /* ── MAIN ── */
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:var(--white);border-bottom:1px solid var(--border);padding:.85rem 1.75rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;position:sticky;top:0;z-index:40}
        .topbar-title{font-size:1.4rem;font-weight:800;color:var(--red);letter-spacing:.02em}
        .topbar-actions{display:flex;align-items:center;gap:.75rem}
        .badge-db{display:inline-flex;align-items:center;gap:.35rem;background:#D1FAE5;color:#065F46;padding:.3rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600}
        .badge-db-dot{width:7px;height:7px;background:#10B981;border-radius:50%;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
        .btn-sm{padding:.4rem .9rem;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:.35rem;transition:all .2s}
        .btn-outline-red{background:transparent;border:1.5px solid var(--red);color:var(--red)}
        .btn-outline-red:hover{background:var(--red);color:#fff}
        .content{padding:1.75rem;flex:1}

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
        .table-wrap{overflow-x:auto}
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

        /* ── DB INFO TABLE ── */
        .db-table{width:100%;font-size:.82rem;border-collapse:collapse}
        .db-table td{padding:.5rem .25rem;border-bottom:1px solid var(--border)}
        .db-table td:first-child{font-weight:600;color:var(--text);font-family:monospace}
        .db-table td:last-child{color:var(--muted)}
        .db-table tr:last-child td{border-bottom:none}

        /* ── ALERTS ── */
        .alert{padding:.85rem 1.1rem;border-radius:8px;font-size:.85rem;margin-bottom:1rem;display:flex;align-items:center;gap:.6rem}
        .alert-success{background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0}
        .alert-error{background:#FEE2E2;color:#991B1B;border:1px solid #FECACA}

        /* ── PAGE HEADER ── */
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
        .page-title{font-size:1.1rem;font-weight:700;color:var(--text)}
        .page-sub{font-size:.8rem;color:var(--muted);margin-top:.1rem}

        /* ── MOBILE ── */
        .hamburger{display:none;background:none;border:none;font-size:1.3rem;cursor:pointer;padding:4px}
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.open{transform:translateX(0)}
            .main-wrap{margin-left:0}
            .hamburger{display:block}
            .stat-grid{grid-template-columns:repeat(2,1fr)}
            .two-col{grid-template-columns:1fr}
            .content{padding:1rem}
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo-title">Angkringan<br>Sekota</div>
        <div class="sidebar-logo-sub">Panel Admin</div>
    </div>
    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.profil.index') }}" class="{{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Beranda &amp; Banner
        </a>
        <a href="{{ route('admin.profil.index') }}" class="{{ false ? 'active' : '' }}">
            <span class="nav-icon">ℹ️</span> Tentang Kami
        </a>
        <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
            <span class="nav-icon">🍽️</span> Menu &amp; Produk
        </a>
        <a href="{{ route('admin.profil.index') }}" class="{{ false ? 'active' : '' }}">
            <span class="nav-icon">📍</span> Lokasi &amp; Kontak
        </a>
        <a href="{{ route('admin.pesan.index') }}" class="{{ request()->routeIs('admin.pesan.*') ? 'active' : '' }}">
            <span class="nav-icon">💬</span> Pesan Masuk
            @php $unread = \App\Models\Pesan::where('status','belum_dibaca')->count(); @endphp
            @if($unread > 0)
                <span style="margin-left:auto;background:rgba(255,255,255,.25);border-radius:20px;padding:1px 8px;font-size:.7rem;font-weight:700;">{{ $unread }}</span>
            @endif
        </a>
        @if(auth()->user()->isOwner())
        <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Pengguna Admin
        </a>
        @endif
    </div>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <strong>{{ auth()->user()->name }}</strong>
            {{ ucfirst(auth()->user()->role) }}
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
        <div style="display:flex;align-items:center;gap:.75rem">
            <button class="hamburger" id="hamburger">☰</button>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="topbar-actions">
            <span class="badge-db">
                <span class="badge-db-dot"></span>
                Database bersama: tersambung
            </span>
            <a href="{{ route('home') }}" target="_blank" class="btn-sm btn-outline-red">
                👁 Lihat Web Profil
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
        @if($errors->any())
            <div class="alert alert-error">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    hamburger?.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });
</script>
@stack('scripts')
</body>
</html>
