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
        body{font-family:'Poppins',sans-serif;min-height:100vh;display:flex;background:#f4f5f7}
        .split-left{width:420px;background:#C8102E;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 2.5rem;min-height:100vh;position:relative;overflow:hidden;flex-shrink:0}
        .split-left::before{content:'';position:absolute;top:-30%;left:-30%;width:80%;height:80%;background:radial-gradient(ellipse,rgba(255,255,255,.08) 0%,transparent 70%);border-radius:50%}
        .split-left::after{content:'';position:absolute;bottom:-20%;right:-20%;width:60%;height:60%;background:radial-gradient(ellipse,rgba(0,0,0,.12) 0%,transparent 70%);border-radius:50%}
        .brand-logo{width:90px;height:90px;background:#fff;border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,.2);position:relative;z-index:1}
        .brand-logo-emoji{font-size:2.2rem;line-height:1}
        .brand-name{font-size:1.6rem;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:.04em;line-height:1.1;text-align:center;position:relative;z-index:1;margin-bottom:.5rem}
        .brand-sub{font-size:.88rem;color:rgba(255,255,255,.75);text-align:center;position:relative;z-index:1}
        .brand-desc{margin-top:2rem;font-size:.8rem;color:rgba(255,255,255,.65);text-align:center;line-height:1.7;position:relative;z-index:1;max-width:280px}
        .split-right{flex:1;display:flex;align-items:center;justify-content:center;padding:2rem}
        .login-card{background:#fff;border-radius:16px;padding:2.5rem;width:100%;max-width:400px;box-shadow:0 4px 24px rgba(0,0,0,.08)}
        .login-title{font-size:1.4rem;font-weight:800;color:#1F2937;margin-bottom:.4rem}
        .login-sub{font-size:.85rem;color:#6B7280;margin-bottom:2rem}
        .form-group{margin-bottom:1.1rem}
        .form-label{display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-control{width:100%;padding:.7rem 1rem;border:1.5px solid #E5E7EB;border-radius:8px;font-size:.9rem;font-family:'Poppins',sans-serif;color:#1F2937;transition:border .2s}
        .form-control:focus{outline:none;border-color:#C8102E;box-shadow:0 0 0 3px rgba(200,16,46,.08)}
        .form-control.error{border-color:#DC2626}
        .error-msg{color:#DC2626;font-size:.78rem;margin-top:.35rem}
        .remember-row{display:flex;align-items:center;gap:.5rem;margin-bottom:1.4rem}
        .remember-row input{width:16px;height:16px;accent-color:#C8102E}
        .remember-row label{font-size:.82rem;color:#6B7280}
        .btn-login{width:100%;padding:.8rem;background:#C8102E;color:#fff;border:none;border-radius:8px;font-size:.95rem;font-weight:700;font-family:'Poppins',sans-serif;cursor:pointer;transition:background .2s}
        .btn-login:hover{background:#9E0C23}
        .login-back{text-align:center;margin-top:1.25rem;font-size:.8rem;color:#6B7280}
        .login-back a{color:#C8102E;font-weight:600;text-decoration:none}
        .login-back a:hover{text-decoration:underline}
        .alert-error{background:#FEE2E2;color:#991B1B;padding:.8rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1.2rem;border:1px solid #FECACA}
    </style>
</head>
<body>

<div class="split-left">
    <div class="brand-logo">
        <span class="brand-logo-emoji">🏠</span>
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
        @if($errors->has('email'))
            <div class="alert-error">{{ $errors->first('email') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                       placeholder="owner@sekota.id" value="{{ old('email') }}" required autofocus>
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
