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
        .sidebar{width:var(--sidebar-w);background:var(--red);min-height:100vh;position:fixed;top:0;left:0;bottom:0;display:flex;flex-direction:column;z-index:50;transition:transform .3s}
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
        .main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;min-width:0;max-width:calc(100vw - var(--sidebar-w));overflow-x:hidden}
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
        .user-pill{display:inline-flex;align-items:center;gap:.4rem;background:#F9FAFB;border:1px solid var(--border);padding:.35rem .75rem;border-radius:20px;font-size:.78rem;font-weight:600;color:var(--text)}
        .user-pill-role{color:var(--muted);font-size:.72rem;font-weight:400}

        /* DB Badge */
        .badge-db{display:inline-flex;align-items:center;gap:.35rem;background:#D1FAE5;color:#065F46;padding:.3rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600}
        .badge-db-dot{width:7px;height:7px;background:#10B981;border-radius:50%;animation:pulse 2s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

        /* Notification Bell & Dropdown */
        .notif-wrapper{position:relative}
        .notif-btn{position:relative;background:#F4F5F7;border:1px solid var(--border);border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text);transition:all .2s}
        .notif-btn:hover{background:#FEE2E2;color:var(--red);border-color:#FECACA}
        .notif-badge{position:absolute;top:-4px;right:-4px;background:var(--red);color:#fff;font-size:.65rem;font-weight:700;min-width:18px;height:18px;border-radius:10px;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #fff;box-shadow:0 2px 4px rgba(200,16,46,.3)}
        
        .notif-dropdown{position:absolute;top:calc(100% + 10px);right:0;width:360px;background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:0 12px 30px rgba(0,0,0,.12);display:none;z-index:100;overflow:hidden;animation:notifFadeIn .2s ease-out}
        .notif-dropdown.show{display:block}
        @keyframes notifFadeIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
        
        .notif-header{padding:.9rem 1.1rem;background:#FAFAFA;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .notif-header-title{font-size:.85rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.4rem}
        .notif-count-pill{background:var(--red);color:#fff;font-size:.68rem;font-weight:700;padding:2px 7px;border-radius:12px}
        .notif-body{max-height:360px;overflow-y:auto}
        .notif-item{display:flex;align-items:flex-start;gap:.75rem;padding:.85rem 1.1rem;border-bottom:1px solid #F3F4F6;text-decoration:none;color:var(--text);transition:background .15s}
        .notif-item:hover{background:#F9FAFB}
        .notif-item.unread{background:#FEF2F2}
        .notif-item.unread:hover{background:#FEE2E2}
        .notif-avatar{width:34px;height:34px;border-radius:50%;background:#FEE2E2;color:var(--red);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0}
        .notif-content{flex:1;min-width:0}
        .notif-name{font-size:.82rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:.4rem}
        .notif-dot{width:7px;height:7px;border-radius:50%;background:var(--red);display:inline-block}
        .notif-text{font-size:.76rem;color:var(--muted);margin-top:.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.3}
        .notif-time{font-size:.7rem;color:#9CA3AF;margin-top:.3rem}
        .notif-empty{padding:2.5rem 1rem;text-align:center;color:var(--muted);font-size:.82rem}
        .notif-footer{padding:.75rem 1.1rem;background:#FAFAFA;border-top:1px solid var(--border);text-align:center}
        .notif-footer a{color:var(--red);font-size:.8rem;font-weight:600;text-decoration:none;transition:opacity .2s}
        .notif-footer a:hover{text-decoration:underline;opacity:.85}

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
        @media(max-width:1280px){
            .topbar-actions .badge-db{display:none}
        }
        @media(max-width:1024px){
            .topbar-actions .clock-pill,
            .topbar-actions .badge-db{display:none}
        }
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.open{transform:translateX(0)}
            .main-wrap{margin-left:0;max-width:100vw}
            .hamburger{display:block}
            .stat-grid{grid-template-columns:repeat(2,1fr)}
            .two-col{grid-template-columns:1fr}
            .content{padding:1rem}
            .topbar{padding:.75rem 1rem}
            .notif-dropdown{position:fixed;top:60px;left:10px;right:10px;width:auto}
        }
    </style>
    @stack('styles')
</head>
<body>

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
        <a href="{{ route('admin.profil.index') }}" class="{{ request()->routeIs('admin.profil.*') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Profil Usaha
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
            <button class="hamburger" id="hamburger" title="Buka Menu">☰</button>
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

            @if(auth()->user()->isPegawai())
            <!-- Ikon Lonceng Notifikasi Pesan Masuk -->
            <div class="notif-wrapper" id="notifWrapper">
                <button type="button" class="notif-btn" id="notifBtn" aria-label="Notifikasi Pesan Masuk" title="Pesan Masuk">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    @if(isset($notifUnreadCount) && $notifUnreadCount > 0)
                        <span class="notif-badge" id="notifBadge">{{ $notifUnreadCount }}</span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi -->
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <div class="notif-header-title">
                            <span>💬 Pesan Masuk</span>
                            @if(isset($notifUnreadCount) && $notifUnreadCount > 0)
                                <span class="notif-count-pill">{{ $notifUnreadCount }} belum dibaca</span>
                            @endif
                        </div>
                        @if(isset($notifUnreadCount) && $notifUnreadCount > 0)
                            <form method="POST" action="{{ route('admin.pesan.markAllRead') }}" style="display:inline">
                                @csrf
                                <button type="submit" style="background:none;border:none;color:var(--red);font-size:.72rem;font-weight:600;cursor:pointer">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="notif-body">
                        @if(isset($notifRecentPesan) && $notifRecentPesan->count() > 0)
                            @foreach($notifRecentPesan as $p)
                                <a href="{{ route('admin.pesan.show', $p->id) }}" class="notif-item {{ $p->status === 'belum_dibaca' ? 'unread' : '' }}">
                                    <div class="notif-avatar">
                                        {{ strtoupper(substr($p->nama_pengirim ?: 'T', 0, 1)) }}
                                    </div>
                                    <div class="notif-content">
                                        <div class="notif-name">
                                            <span>{{ $p->nama_pengirim }}</span>
                                            @if($p->status === 'belum_dibaca')
                                                <span class="notif-dot" title="Belum dibaca"></span>
                                            @endif
                                        </div>
                                        <div class="notif-text">
                                            {{ \Illuminate\Support\Str::limit($p->isi_pesan, 55) }}
                                        </div>
                                        <div class="notif-time">
                                            🕒 {{ $p->created_at ? $p->created_at->diffForHumans() : '-' }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="notif-empty">
                                <div style="font-size:1.8rem;margin-bottom:.4rem">📭</div>
                                <strong>Belum ada pesan masuk</strong>
                                <p style="font-size:.75rem;margin-top:.2rem">Pesan dari pelanggan lewat website profil akan muncul di sini.</p>
                            </div>
                        @endif
                    </div>

                    <div class="notif-footer">
                        <a href="{{ route('admin.pesan.index') }}">Lihat Semua Pesan &rarr;</a>
                    </div>
                </div>
            </div>
            @endif
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
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    // Sidebar Hamburger toggle
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    hamburger?.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });

    // Dropdown Notifikasi Lonceng
    const notifBtn      = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifWrapper  = document.getElementById('notifWrapper');

    notifBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        notifDropdown.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
        if (notifWrapper && !notifWrapper.contains(e.target)) {
            notifDropdown?.classList.remove('show');
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
