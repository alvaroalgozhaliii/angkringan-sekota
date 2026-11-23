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
        body{font-family:'Poppins',sans-serif;min-height:100dvh;display:flex;overflow:hidden;background:#fff}

        /* ── PANEL KIRI (putih) ── */
        .panel-left {
            width: 480px;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.75rem;
            min-height: 100dvh;
            position: relative;
            overflow: hidden;
            z-index: 2;
            border-right: 1px solid #F3F4F6;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }
        .brand-logo {
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,.25);
            overflow: hidden;
            flex-shrink: 0;
        }
        .brand-logo img { width:100%;height:100%;object-fit:contain;border-radius:50% }
        .brand-info {}
        .brand-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
            text-transform: uppercase;
            letter-spacing: .05em;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: .78rem;
            color: #6B7280;
            margin-top: .15rem;
        }

        /* Heading login — di dalam card merah */
        .login-heading {
            margin-bottom: 1.5rem;
        }
        .login-heading h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: .35rem;
        }
        .login-heading p {
            font-size: .8rem;
            color: rgba(255,255,255,.75);
            line-height: 1.6;
        }

        /* Alert */
        .alert-error {
            background: rgba(0,0,0,.2);
            color: #fff;
            padding: .75rem 1rem;
            border-radius: 10px;
            font-size: .8rem;
            margin-bottom: 1.2rem;
            border: 1px solid rgba(255,255,255,.25);
        }

        /* Card MERAH pembungkus heading + form */
        .login-card {
            background: #C8102E;
            border-radius: 20px;
            padding: 2rem 2rem 1.75rem;
            position: relative;
            z-index: 1;
            box-shadow: 0 12px 40px rgba(200,16,46,.35);
            overflow: hidden;
        }
        .login-card::before {
            content:'';
            position:absolute;top:-30%;left:-30%;
            width:80%;height:80%;
            background:radial-gradient(ellipse,rgba(255,255,255,.1) 0%,transparent 70%);
            border-radius:50%;pointer-events:none;
        }
        .login-card::after {
            content:'';
            position:absolute;bottom:-25%;right:-25%;
            width:65%;height:65%;
            background:radial-gradient(ellipse,rgba(0,0,0,.12) 0%,transparent 70%);
            border-radius:50%;pointer-events:none;
        }

        /* Form */
        .login-form { position: relative; z-index: 1; }
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            margin-bottom: .4rem;
            position: relative;
            z-index: 1;
        }
        .form-control {
            width: 100%;
            min-height: 48px;
            padding: .75rem 1rem;
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.3);
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            transition: border .2s, background .2s;
            position: relative;
            z-index: 1;
        }
        .form-control::placeholder { color: rgba(255,255,255,.45) }
        .form-control:focus {
            outline: none;
            border-color: rgba(255,255,255,.85);
            background: rgba(255,255,255,.22);
        }
        .form-control.error { border-color: #FFB3BC }

        /* Remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 1rem 0 1.5rem;
            position: relative;
            z-index: 1;
        }
        .remember-row input {
            width: 18px;
            height: 18px;
            accent-color: #fff;
        }
        .remember-row label {
            font-size: .8rem;
            color: rgba(255,255,255,.8);
            cursor: pointer;
        }

        /* Submit */
        .btn-login {
            width: 100%;
            min-height: 48px;
            padding: .85rem;
            background: #fff;
            color: #C8102E;
            border: none;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background .2s, transform .15s;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
            position: relative;
            z-index: 1;
        }
        .btn-login:hover {
            background: #F3F4F6;
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0) }

        /* Back link */
        .login-back {
            margin-top: 1.1rem;
            font-size: .78rem;
            color: rgba(255,255,255,.65);
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .login-back a {
            color: rgba(255,255,255,.95);
            font-weight: 600;
            text-decoration: none;
        }
        .login-back a:hover { text-decoration: underline }

        /* ── PANEL KANAN (slideshow) ── */
        .panel-right {
            flex: 1;
            position: relative;
            overflow: hidden;
            min-height: 100dvh;
        }

        .slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: translateX(40px);
            transition: opacity .9s ease, transform .9s ease;
            will-change: opacity, transform;
        }
        .slide.active {
            opacity: 1;
            transform: translateX(0);
        }
        .slide.exit {
            opacity: 0;
            transform: translateX(-40px);
        }

        /* Overlay gelap tipis supaya terlihat foto */
        .slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,.35) 0%, transparent 60%);
        }

        /* Caption kecil di sudut */
        .slide-caption {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            color: rgba(255,255,255,.75);
            font-size: .75rem;
            font-family: 'Poppins', sans-serif;
            letter-spacing: .05em;
            text-transform: uppercase;
            z-index: 2;
        }

        /* Dots */
        .slide-dots {
            position: absolute;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: .5rem;
            z-index: 10;
        }
        .slide-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,.4);
            transition: background .3s, transform .3s;
        }
        .slide-dot.active {
            background: #fff;
            transform: scale(1.3);
        }

        /* ── RESPONSIVE ── */
        @media(max-width: 900px) {
            .panel-left { width: 380px; padding: 2.5rem 2rem }
        }
        @media(max-width: 700px) {
            body { flex-direction: column; overflow: auto }
            .panel-left {
                width: 100%;
                min-height: auto;
                padding: 2rem 1.5rem;
            }
            .panel-right {
                min-height: 240px;
                flex: none;
                height: 240px;
            }
        }
    </style>
</head>
<body>

{{-- ── PANEL KIRI: brand + form ── --}}
<div class="panel-left">

    <div class="brand">
        <div class="brand-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Angkringan Sekota">
        </div>
        <div class="brand-info">
            <div class="brand-name">Angkringan<br>Sekota</div>
            <div class="brand-sub">Tulungagung</div>
        </div>
    </div>

    <div class="login-card">
        <div class="login-heading">
            <h1>Selamat datang 👋</h1>
            <p>Login untuk masuk ke panel admin Angkringan Sekota.</p>
        </div>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->has('username'))
            <div class="alert-error">{{ $errors->first('username') }}</div>
        @endif

        <form class="login-form" method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="username">Username atau Email</label>
                <input type="text" id="username" name="username"
                       class="form-control {{ $errors->has('username') ? 'error' : '' }}"
                       placeholder="pegawai@sekota.id"
                       value="{{ old('username') }}"
                       required autofocus autocomplete="username">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control"
                       placeholder="••••••••"
                       required autocomplete="current-password">
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

{{-- ── PANEL KANAN: slideshow foto nuansa sekota ── --}}
<div class="panel-right" aria-hidden="true">
    <div class="slide active"
         style="background-image:url('{{ asset('images/hero/slide1.jpg') }}')"
         data-caption="Suasana malam di Sekota"></div>
    <div class="slide"
         style="background-image:url('{{ asset('images/hero/slide2.jpg') }}')"
         data-caption="Hidangan khas angkringan"></div>
    <div class="slide"
         style="background-image:url('{{ asset('images/hero/slide3.jpg') }}')"
         data-caption="Wedangan & camilan"></div>

    <div class="slide-caption" id="slideCaption">Suasana malam di Sekota</div>

    <div class="slide-dots" id="slideDots">
        <div class="slide-dot active"></div>
        <div class="slide-dot"></div>
        <div class="slide-dot"></div>
    </div>
</div>

<script>
    (function () {
        const slides   = document.querySelectorAll('.slide');
        const dots     = document.querySelectorAll('.slide-dot');
        const caption  = document.getElementById('slideCaption');
        let current    = 0;
        let timer;

        function goTo(next) {
            const prev = current;
            if (next === prev) return;

            slides[prev].classList.remove('active');
            slides[prev].classList.add('exit');

            setTimeout(() => slides[prev].classList.remove('exit'), 900);

            slides[next].classList.add('active');
            dots[prev].classList.remove('active');
            dots[next].classList.add('active');
            caption.textContent = slides[next].dataset.caption;
            current = next;
        }

        function next() {
            goTo((current + 1) % slides.length);
        }

        function start() {
            timer = setInterval(next, 4500);
        }

        // Pause on hover
        const panel = document.querySelector('.panel-right');
        panel.addEventListener('mouseenter', () => clearInterval(timer));
        panel.addEventListener('mouseleave', start);

        start();
    })();
</script>

</body>
</html>
