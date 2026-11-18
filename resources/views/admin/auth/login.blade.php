<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — Angkringan Sekota</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Poppins',sans-serif;min-height:100dvh;display:flex;background:#f4f5f7}
        .split-left{width:420px;background:#C8102E;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 2.5rem;min-height:100vh;position:relative;overflow:hidden;flex-shrink:0}
        .split-left::before{content:'';position:absolute;top:-30%;left:-30%;width:80%;height:80%;background:radial-gradient(ellipse,rgba(255,255,255,.08) 0%,transparent 70%);border-radius:50%}
        .split-left::after{content:'';position:absolute;bottom:-20%;right:-20%;width:60%;height:60%;background:radial-gradient(ellipse,rgba(0,0,0,.12) 0%,transparent 70%);border-radius:50%}
        .brand-logo{width:90px;height:90px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,.2);position:relative;z-index:1;overflow:hidden;padding:4px}
        .brand-logo img{width:100%;height:100%;object-fit:contain;border-radius:50%}
        .brand-name{font-size:1.6rem;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:.04em;line-height:1.1;text-align:center;position:relative;z-index:1;margin-bottom:.5rem}
        .brand-sub{font-size:.88rem;color:rgba(255,255,255,.75);text-align:center;position:relative;z-index:1}
        .brand-desc{margin-top:2rem;font-size:.8rem;color:rgba(255,255,255,.65);text-align:center;line-height:1.7;position:relative;z-index:1;max-width:280px}
        .split-right{flex:1;display:flex;align-items:center;justify-content:center;padding:2rem}
        .login-card{background:#fff;border-radius:16px;padding:2.5rem;width:100%;max-width:400px;box-shadow:0 4px 24px rgba(0,0,0,.08)}
        .login-title{font-size:1.4rem;font-weight:800;color:#1F2937;margin-bottom:.4rem}
        .login-sub{font-size:.85rem;color:#6B7280;margin-bottom:2rem}
        .form-group{margin-bottom:1.1rem}
        .form-label{display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-control{width:100%;min-height:46px;padding:.7rem 1rem;border:1.5px solid #E5E7EB;border-radius:8px;font-size:16px;font-family:'Poppins',sans-serif;color:#1F2937;transition:border .2s}
        .form-control:focus{outline:none;border-color:#C8102E;box-shadow:0 0 0 3px rgba(200,16,46,.08)}
        .form-control.error{border-color:#DC2626}
        .error-msg{color:#DC2626;font-size:.78rem;margin-top:.35rem}
        .remember-row{display:flex;align-items:center;gap:.5rem;margin-bottom:1.4rem;min-height:44px}
        .remember-row input{width:22px;height:22px;accent-color:#C8102E}
        .remember-row label{font-size:.82rem;color:#6B7280;min-height:44px;display:inline-flex;align-items:center;cursor:pointer}
        .btn-login{width:100%;min-height:46px;padding:.8rem;background:#C8102E;color:#fff;border:none;border-radius:8px;font-size:.95rem;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;transition:background .2s}
        .btn-login:hover{background:#9E0C23}
        .login-back{text-align:center;margin-top:1.25rem;font-size:.8rem;color:#6B7280}
        .login-back a{color:#C8102E;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 .25rem}
        .login-back a:hover{text-decoration:underline}
        .alert-error{background:#FEE2E2;color:#991B1B;padding:.8rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1.2rem;border:1px solid #FECACA}
        @media(max-width:900px){
            .split-left{width:320px;padding:2rem 1.5rem}
            .split-right{padding:1.5rem}
            .login-card{padding:2rem}
        }
        @media(max-width:768px){
            body{display:block;min-height:100dvh}
            .split-left{width:100%;min-height:0;padding:1.25rem 1rem}
            .brand-logo{width:64px;height:64px;margin-bottom:.75rem}
            .brand-name{font-size:1.25rem;margin-bottom:.25rem}
            .brand-sub{font-size:.78rem}
            .brand-desc{display:none}
            .split-right{min-height:calc(100dvh - 160px);padding:1rem;align-items:flex-start}
            .login-card{padding:1.5rem 1.25rem;border-radius:12px}
            .login-title{font-size:1.25rem}
            .login-sub{font-size:.8rem;margin-bottom:1.5rem}
        }
        @media(max-width:380px){
            .split-left{padding:.9rem}
            .brand-logo{width:54px;height:54px}
            .split-right{padding:.75rem}
            .login-card{padding:1.2rem 1rem}
        }
    </style>
</head>
<body>

<div class="split-left">
    <div class="brand-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Angkringan Sekota">
    </div>
    <div class="brand-name">Angkringan<br>Sekota</div>
    <div class="brand-sub">Tulungagung</div>
    <div class="brand-desc">
        Panel admin untuk mengelola produk, profil usaha, dan pesan dari pengunjung.
    </div>
</div>

<div class="split-right">
    <div class="login-card">
        <div class="login-title">Selamat datang 👋</div>
        <div class="login-sub">Login untuk masuk ke panel admin Angkringan Sekota.</div>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->has('username'))
            <div class="alert-error">{{ $errors->first('username') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="username">Username atau Email</label>
                <input type="text" id="username" name="username" class="form-control {{ $errors->has('username') ? 'error' : '' }}"
                       placeholder="pegawai@sekota.id" value="{{ old('username') }}" required autofocus autocomplete="username">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk ke Dashboard</button>
        </form>

        <div class="login-back">
            <a href="{{ route('home') }}">← Kembali ke Web Profil</a>
        </div>
    </div>
</div>

</body>
</html>
