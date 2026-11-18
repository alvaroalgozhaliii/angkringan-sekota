<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Angkringan Sekota Tulungagung</title>
    <meta name="description" content="Angkringan Sekota Tulungagung - Nikmati cita rasa khas angkringan dengan menu lezat dan harga terjangkau.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (dengan preflight false untuk menjaga kompatibilitas tema yang ada) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false,
            },
            theme: {
                extend: {
                    colors: {
                        brand: {
                            red: '#C8102E',
                            'red-dark': '#9E0C23',
                            cream: '#FDF5F0',
                            'cream-dark': '#F5E8E0',
                        }
                    }
                }
            }
        };
    </script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            caret-color: transparent !important;
        }

        input, textarea, select, [contenteditable="true"] {
            caret-color: auto !important;
        }

        :root {
            --red:        {{ $profil->warnaTema() }};
            --red-dark:   {{ $profil->warnaTema() }};
            --cream:      #FDF5F0;
            --cream-dark: #F5E8E0;
            --white:      #FFFFFF;
            --text-dark:  #1A1A1A;
            --text-gray:  #666666;
            --nav-h:      68px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow: hidden; /* pages scroll individually */
        }
        button, .filter-btn, .btn-primary, .btn-outline { -webkit-user-select: none; user-select: none; }

        input, textarea, select {
            -webkit-user-select: auto;
            user-select: auto;
        }

        /* ===================================================
           NAVBAR — always fixed on top, z-index highest
           =================================================== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: var(--red);
            height: var(--nav-h);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 16px rgba(0,0,0,0.18);
            user-select: none;
            -webkit-user-select: none;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            cursor: pointer;
        }
        .navbar-logo-circle {
            width: 56px;
            height: 56px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255,255,255,0.4);
            overflow: hidden;
            flex-shrink: 0;
            padding: 2px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .navbar-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }
        .navbar-brand-text {
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.2rem;
            list-style: none;
        }
        .navbar-nav a {
            color: rgba(255,255,255,0.88);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            transition: all 0.2s;
            white-space: nowrap;
            cursor: pointer;
            border: none;
            background: none;
            font-family: 'Poppins', sans-serif;
            user-select: none;
            -webkit-user-select: none;
        }
        .navbar-nav a:hover { background: rgba(255,255,255,0.15); color: var(--white); }
        .navbar-nav a.active  { background: rgba(255,255,255,0.22); color: var(--white); font-weight: 600; }

        /* Hamburger */
        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }
        .hamburger span {
            display: block;
            width: 24px; height: 2px;
            background: white;
            margin: 5px 0;
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* ===================================================
           MOBILE MENU
           =================================================== */
        .mobile-menu {
            display: none;
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--red-dark);
            z-index: 999;
            padding: 0.75rem 1rem 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }
        .mobile-menu.open { display: block; }
        .mobile-menu ul { list-style: none; display: flex; flex-direction: column; gap: 0.2rem; }
        .mobile-menu ul a {
            display: block;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            transition: background 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            font-family: 'Poppins', sans-serif;
            width: 100%;
            text-align: left;
            user-select: none;
            -webkit-user-select: none;
        }
        .mobile-menu ul a:hover, .mobile-menu ul a.active { background: rgba(255,255,255,0.18); }

        /* ===================================================
           PAGE SYSTEM (Smooth Bidirectional Slider)
           =================================================== */
        .page {
            position: fixed;
            top: var(--nav-h);
            left: 0; right: 0; bottom: 0;
            overflow-y: auto;
            overflow-x: hidden;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateX(100%);
            transition: transform 0.48s cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 0.42s cubic-bezier(0.22, 1, 0.36, 1),
                        visibility 0.48s;
            will-change: transform, opacity;
        }
        .page.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            pointer-events: auto;
        }
        /* Forward movement: current exits left, new enters from right */
        .page.exit-left {
            opacity: 0;
            visibility: visible;
            transform: translateX(-15%);
            pointer-events: none;
        }
        .page.enter-right {
            opacity: 0;
            visibility: visible;
            transform: translateX(20%);
            pointer-events: none;
        }
        /* Backward movement: current exits right, new enters from left */
        .page.exit-right {
            opacity: 0;
            visibility: visible;
            transform: translateX(15%);
            pointer-events: none;
        }
        .page.enter-left {
            opacity: 0;
            visibility: visible;
            transform: translateX(-20%);
            pointer-events: none;
        }
        .page.no-anim {
            transition: none !important;
        }

        /* ===================================================
           PAGE HEADER BANNER (merah, like the screenshot)
           Used by Menu, Tentang, Kontak pages
           =================================================== */
        .page-banner {
            background: var(--red);
            padding: 2rem 1.75rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .page-banner::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .page-banner::after {
            content: '';
            position: absolute;
            bottom: -70px; left: -30px;
            width: 240px; height: 240px;
            background: rgba(0,0,0,0.07);
            border-radius: 50%;
        }
        .page-banner-text { position: relative; z-index: 1; }
        .page-banner-text h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 0.35rem;
        }
        .page-banner-text p {
            color: rgba(255,255,255,0.78);
            font-size: 0.85rem;
            line-height: 1.5;
        }
        .page-banner-logo {
            position: relative;
            z-index: 1;
            width: 72px; height: 72px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(0,0,0,0.22);
            border: 3px solid rgba(255,255,255,0.3);
            overflow: hidden;
        }
        .page-banner-logo svg { width: 46px; height: 46px; }

        /* ===================================================
           PAGE: BERANDA (HERO)
           Dial: ENERGY 2 / RHYTHM 2 / MOTION 2
           Motif: lingkaran logo putih + aksen merah brand
           =================================================== */
        #page-beranda { background: var(--cream); }
        .hero {
            min-height: calc(100dvh - var(--nav-h));
            min-height: calc(100vh - var(--nav-h));
            display: flex;
            align-items: flex-end;
            position: relative;
            overflow: hidden;
            background: #1a0a0c;
        }
        .hero-slideshow {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            opacity: 0;
            transition: opacity 1.1s ease-in-out;
        }
        .hero-slide.active { opacity: 1; }
        /* Scrim: kontras teks putih di atas foto malam (bukan dekorasi) */
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background:
                linear-gradient(180deg,
                    rgba(20,8,10,0.28) 0%,
                    rgba(20,8,10,0.18) 28%,
                    rgba(20,8,10,0.72) 68%,
                    rgba(10,4,5,0.92) 100%);
            pointer-events: none;
        }
        .hero-dots {
            position: absolute;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.45rem;
            z-index: 3;
        }
        .hero-dot {
            width: 10px;
            height: 10px;
            min-width: 44px;
            min-height: 44px;
            margin: -17px;
            padding: 17px;
            border-radius: 999px;
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
        }
        .hero-dot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            transform: translate(-50%, -50%);
            border-radius: 999px;
            background: rgba(255,255,255,0.45);
            transition: width 0.25s ease, background 0.25s ease, border-radius 0.25s ease;
        }
        .hero-dot.active::after {
            width: 22px;
            border-radius: 4px;
            background: #fff;
        }
        .hero-dot:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 2px;
        }
        .hero-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 3.5rem 1.5rem 4.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.35rem;
        }
        .hero-brand-mark {
            width: 72px;
            height: 72px;
            background: var(--white);
            border-radius: 50%;
            padding: 4px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(0,0,0,0.28);
            flex-shrink: 0; margin-bottom: 1rem;
        }
        .hero-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }
        .hero-title {
    font-size: clamp(2.35rem, 7vw, 4.1rem);
    font-weight: 800;
    color: var(--white);
    line-height: 0.98;
    letter-spacing: -0.02em;
    text-transform: uppercase;
    text-align: center;
    max-width: none;
    text-shadow: 0 2px 18px rgba(0,0,0,0.35);
    margin: 1rem 0 0 0;
}

        }
        .hero-subtitle {
            font-size: clamp(0.95rem, 2.2vw, 1.1rem);
            color: rgba(255,255,255,0.92);
            line-height: 1.55;
            max-width: 34rem;
            margin: 0;
            font-weight: 400;
        }
        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.35rem;
        }
        .btn-primary {
            background: var(--white);
            color: var(--red);
            padding: 0.85rem 1.5rem;
            min-height: 44px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: 2px solid var(--white);
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            box-shadow: 0 6px 18px rgba(0,0,0,0.22);
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary:hover { background: #FFF5F5; color: var(--red-dark); }
        .btn-primary:active { transform: translateY(1px); }
        .btn-primary:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
        .btn-outline {
            background: transparent;
            color: var(--white);
            padding: 0.85rem 1.5rem;
            min-height: 44px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border: 2px solid rgba(255,255,255,0.75);
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.12); border-color: var(--white); }
        .btn-outline:active { transform: translateY(1px); }
        .btn-outline:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }

        /* Strip info buka: langsung di bawah hero */
        .hero-info-strip {
            background: var(--red-dark);
            color: rgba(255,255,255,0.95);
            padding: 0.65rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.75rem;
            flex-wrap: wrap;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }
        .hero-info-strip .strip-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }
        .hero-info-strip .strip-item svg {
            width: 14px;
            height: 14px;
            opacity: 0.85;
            flex-shrink: 0;
        }
        .hero-info-strip .strip-sep {
            width: 1px;
            height: 14px;
            background: rgba(255,255,255,0.3);
            flex-shrink: 0;
        }

        /* Produk favorit di beranda */
        .bestsellers {
            background: var(--white);
            padding: 4rem 1.5rem;
        }
        .bestsellers-container { max-width: 1100px; margin: 0 auto; }
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        .section-line { width: 60px; height: 2px; background: var(--red); border-radius: 2px; }
        .section-title { font-size: 1.5rem; font-weight: 700; color: var(--text-dark); white-space: nowrap; }
        .section-sublabel { text-align: center; font-size: 0.8rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.4rem; }
        .section-title-red { text-align: center; font-size: 1.3rem; font-weight: 800; color: var(--red); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 1.5rem; }

        /* Menu grid shared */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.28s;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 14px 36px rgba(0,0,0,0.14); }
        .product-card-img {
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            background: linear-gradient(145deg, #FAF4EF 0%, #F5ECE4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem;
        }
        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.35s ease;
            filter: drop-shadow(0 6px 14px rgba(0,0,0,0.12));
        }
        .product-card:hover .product-card-img img {
            transform: scale(1.08) translateY(-2px);
        }
        .product-card-body { padding: 0.9rem 1rem; }
        .product-card-name { font-size: 0.88rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.4rem; line-height: 1.3; }
        .product-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: 0.6rem;
        }
        .product-card-price {
            display: inline-block;
            background: var(--red);
            color: var(--white);
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            white-space: nowrap;
        }
        .btn-pesan-item {
            background: #FFF0F2;
            color: var(--red);
            border: 1.5px solid var(--red);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            white-space: nowrap;
        }
        .btn-pesan-item:hover {
            background: var(--red);
            color: var(--white);
            transform: scale(1.04);
            box-shadow: 0 4px 12px rgba(200,16,46,0.25);
        }
        .btn-pesan-item:active { transform: scale(0.96); }
        .btn-pesan-item.added {
            background: #059669;
            color: #fff;
            border-color: #059669;
        }

        /* ── FLOATING CART BAR ── */
        .cart-bar {
            position: fixed;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            background: var(--red);
            color: var(--white);
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            box-shadow: 0 10px 32px rgba(0,0,0,0.35);
            z-index: 1050;
            width: calc(100% - 2.5rem);
            max-width: 480px;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            animation: cartPop 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        }
        @keyframes cartPop {
            from { transform: translate(-50%, 40px); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
        .cart-bar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            width: 100%;
        }
        .cart-bar-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }
        .cart-icon {
            font-size: 1.3rem;
            background: rgba(255,255,255,0.2);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .cart-bar-info strong {
            font-size: 0.88rem;
            font-weight: 700;
            display: block;
            line-height: 1.2;
        }
        .cart-total-price {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.85);
        }
        .btn-cart-view {
            background: var(--white);
            color: var(--red);
            border: none;
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
        .btn-cart-view:hover {
            background: #FFF0F2;
            transform: scale(1.03);
        }

        /* ── ORDER MODAL ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }
        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }
        .modal-box {
            background: var(--white);
            border-radius: 20px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            overflow: hidden;
            transform: translateY(20px) scale(0.97);
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .modal-overlay.open .modal-box {
            transform: translateY(0) scale(1);
        }
        .modal-header {
            padding: 1rem 1.25rem;
            background: #FFF5F6;
            border-bottom: 1.5px solid #FEE2E2;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--red);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-close-modal {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #888;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-close-modal:hover { background: #FEE2E2; color: var(--red); }
        .modal-body {
            padding: 1.25rem;
            overflow-y: auto;
            flex: 1;
        }
        .order-items-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            margin-bottom: 1.15rem;
        }
        .order-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.85rem;
            background: #FAFAFA;
            border: 1px solid #EBEBEB;
            border-radius: 12px;
            gap: 0.75rem;
        }
        .order-item-info {
            flex: 1;
            min-width: 0;
        }
        .order-item-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .order-item-price {
            font-size: 0.75rem;
            color: var(--text-gray);
        }
        .order-qty-control {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-qty {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 1px solid #DDD;
            background: #fff;
            color: var(--text-dark);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: all 0.15s;
        }
        .btn-qty:hover {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }
        .order-qty-val {
            font-size: 0.85rem;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }
        .order-item-subtotal {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--red);
            min-width: 70px;
            text-align: right;
        }
        .btn-del-item {
            background: none;
            border: none;
            color: #DC2626;
            cursor: pointer;
            font-size: 0.95rem;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .btn-del-item:hover {
            background: #FEE2E2;
        }
        .order-empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-gray);
            font-size: 0.88rem;
        }
        .order-summary-box {
            background: #FFF7F8;
            border: 1.5px solid #FEE2E2;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            color: var(--text-gray);
            margin-bottom: 0.35rem;
        }
        .summary-row.total-highlight {
            font-size: 0.95rem;
            color: var(--text-dark);
            font-weight: 800;
            border-top: 1px dashed #FECACA;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 0;
        }
        .summary-row.total-highlight strong {
            color: var(--red);
            font-size: 1.15rem;
        }
        .order-form-box {
            border-top: 1px solid #ECECEC;
            padding-top: 1rem;
        }
        .order-form-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--red);
            margin-bottom: 0.75rem;
        }
        .order-form-group {
            margin-bottom: 0.75rem;
        }
        .order-form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
        }
        .order-input {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-size: 0.86rem;
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background: #fff;
            transition: all 0.2s;
        }
        .order-input:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.1);
        }
        .order-input.is-invalid {
            border-color: #DC2626 !important;
            background: #FFF5F5 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
            animation: shake 0.4s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .input-error-msg {
            color: #DC2626;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.35rem;
            display: none;
            align-items: center;
            gap: 0.3rem;
        }

        /* Modern Selectable Tabs for Tipe Pemesanan */
        .order-type-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-top: 0.25rem;
        }
        .type-tab-card {
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 0.65rem 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s ease;
            background: #fff;
            user-select: none;
        }
        .type-tab-card:hover {
            border-color: #CBD5E1;
            background: #F8FAFC;
        }
        .type-tab-card.active {
            border-color: var(--red);
            background: #FFF5F6;
        }
        .type-tab-card .tab-icon {
            font-size: 1.25rem;
        }
        .type-tab-card .tab-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }
        .type-tab-card.active .tab-title {
            color: var(--red);
        }
        .type-tab-card .tab-sub {
            font-size: 0.68rem;
            color: var(--text-gray);
            margin-top: 1px;
        }
        .modal-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #ECECEC;
            background: #FAFAFA;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .btn-order-system {
            flex: 1;
            background: var(--red);
            color: #fff;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.86rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 14px rgba(200, 16, 46, 0.25);
        }
        .btn-order-system:hover {
            background: var(--red-dark);
            transform: translateY(-2px);
        }
        .btn-order-system:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .order-success-panel {
            padding: 2rem 1.25rem 1.5rem;
            text-align: center;
        }
        .order-success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 0.85rem;
            border-radius: 50%;
            background: #D1FAE5;
            color: #065F46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .order-success-title {
            margin: 0 0 0.35rem;
            font-size: 1.05rem;
            font-weight: 800;
            color: #065F46;
        }
        .order-success-desc {
            margin: 0 0 0.35rem;
            font-size: 0.86rem;
            color: var(--text-gray);
        }
        .order-success-note {
            margin: 0 0 1.25rem;
            font-size: 0.78rem;
            color: #666;
            line-height: 1.5;
        }
        .order-success-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .order-success-actions .btn-order-system {
            text-decoration: none;
        }
        .btn-order-secondary {
            flex: 1;
            background: #fff;
            color: #444;
            border: 1px solid #E5E7EB;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.86rem;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }
        .btn-order-secondary:hover {
            background: #F5F5F5;
            border-color: #D1D5DB;
        }

        /* ── CUSTOM CONFIRMATION MODAL ── */
        .confirm-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            z-index: 2500;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }
        .confirm-overlay.open {
            opacity: 1;
            visibility: visible;
        }
        .confirm-box {
            background: #fff;
            border-radius: 18px;
            max-width: 380px;
            width: 100%;
            padding: 1.75rem 1.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
            transform: scale(0.92);
            transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .confirm-overlay.open .confirm-box {
            transform: scale(1);
        }
        .confirm-icon-wrap {
            width: 54px;
            height: 54px;
            background: #FEE2E2;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.85rem;
        }
        .confirm-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
        }
        .confirm-desc {
            font-size: 0.82rem;
            color: var(--text-gray);
            line-height: 1.5;
            margin-bottom: 1.4rem;
        }
        .confirm-actions {
            display: flex;
            gap: 0.75rem;
        }
        .btn-confirm-cancel {
            flex: 1;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #fff;
            font-size: 0.84rem;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-confirm-cancel:hover {
            background: #F8FAFC;
            color: var(--text-dark);
        }
        .btn-confirm-ok {
            flex: 1;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            border: none;
            background: var(--red);
            font-size: 0.84rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 12px rgba(200, 16, 46, 0.3);
        }
        .btn-confirm-ok:hover {
            background: var(--red-dark);
        }

        .toast-notif {
            position: fixed;
            top: 5rem;
            left: 50%;
            transform: translateX(-50%);
            background: #1E293B;
            color: #fff;
            padding: 0.75rem 1.4rem;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            font-size: 0.84rem;
            font-weight: 600;
            z-index: 3000;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            pointer-events: none;
            animation: toastSlideDown 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        @keyframes toastSlideDown {
            from { transform: translate(-50%, -20px); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }

        .btn-cta-full {
            display: block;
            width: 100%;
            max-width: 420px;
            margin: 2rem auto 0;
            background: var(--red);
            color: var(--white);
            padding: 0.9rem 2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.25s;
            box-shadow: 0 4px 18px rgba(200,16,46,0.3);
            text-align: center;
        }
        .btn-cta-full:hover { background: var(--red-dark); transform: translateY(-2px); box-shadow: 0 8px 28px rgba(200,16,46,0.4); }
        .btn-cta-full:focus-visible { outline: 2px solid var(--red); outline-offset: 3px; }
        .btn-pesan-item:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }

        /* ===================================================
           PAGE: MENU
           =================================================== */
        #page-menu { background: var(--cream); }
        .menu-content {
            max-width: 920px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }
        .menu-header-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }
        .menu-header-wrap .menu-intro {
            text-align: left;
            margin-bottom: 0;
            flex: 1;
            min-width: 280px;
        }
        .menu-intro h3 { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.03em; }
        .menu-intro .city { font-size: 0.95rem; font-weight: 700; color: var(--red); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.35rem; }
        .menu-intro p { font-size: 0.84rem; color: var(--text-gray); line-height: 1.65; max-width: 460px; margin: 0; }

        .menu-search-container {
            width: 320px;
            max-width: 100%;
        }
        .menu-search-wrap {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .menu-search-wrap .search-icon {
            position: absolute;
            left: 14px;
            font-size: 0.95rem;
            color: #9CA3AF;
            pointer-events: none;
        }
        .menu-search-input {
            width: 100%;
            padding: 0.65rem 2.4rem 0.65rem 2.5rem;
            border-radius: 50px;
            border: 2px solid rgba(200,16,46,0.2);
            background: var(--white);
            font-family: 'Poppins', sans-serif;
            font-size: 0.86rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.25s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        .menu-search-input:focus {
            border-color: var(--red);
            box-shadow: 0 4px 16px rgba(200,16,46,0.15);
        }
        .btn-search-clear {
            position: absolute;
            right: 12px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #E5E7EB;
            color: #4B5563;
            border: none;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .btn-search-clear:hover {
            background: var(--red);
            color: var(--white);
        }
        .search-result-note {
            font-size: 0.78rem;
            color: var(--red);
            margin-top: 0.4rem;
            padding-left: 0.6rem;
            font-weight: 600;
        }

        .filter-row { display: flex; justify-content: flex-start; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .filter-btn {
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            border: 2px solid var(--red);
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.22s;
            background: var(--white);
            color: var(--red);
            font-family: 'Poppins', sans-serif;
            display: inline-flex;
            align-items: center;
        }
        .filter-btn:hover, .filter-btn.active { background: var(--red); color: var(--white); }
        .filter-count {
            display: inline-block;
            font-size: 0.72rem;
            padding: 1px 7px;
            border-radius: 12px;
            background: rgba(200,16,46,0.1);
            color: var(--red);
            margin-left: 6px;
            font-weight: 700;
            transition: all 0.2s;
        }
        .filter-btn:hover .filter-count, .filter-btn.active .filter-count {
            background: rgba(255,255,255,0.28);
            color: var(--white);
        }

        .menu-empty-state {
            text-align: center;
            padding: 3.5rem 1.5rem;
            background: var(--white);
            border-radius: 16px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px dashed rgba(200,16,46,0.2);
        }
        .menu-empty-state .empty-icon {
            font-size: 2.8rem;
            margin-bottom: 0.75rem;
        }
        .menu-empty-state h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
        }
        .menu-empty-state p {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 1.25rem;
        }
        .btn-reset-filter {
            background: var(--red);
            color: var(--white);
            border: none;
            padding: 0.55rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-reset-filter:hover {
            background: var(--red-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(200,16,46,0.25);
        }

        .menu-cat-label {
            text-align: center;
            margin-bottom: 1.2rem;
        }
        .menu-cat-label .sub { font-size: 0.78rem; color: var(--text-gray); text-transform: uppercase; letter-spacing: 0.08em; }
        .menu-cat-label h4 { font-size: 1.25rem; font-weight: 800; text-transform: uppercase; color: var(--text-dark); }
        .menu-cat-label h4 span { color: var(--red); }

        .menu-cat-block { margin-bottom: 0.5rem; }
        .menu-cat-block + .menu-cat-block { margin-top: 2.5rem; }
        .menu-empty { text-align: center; padding: 3rem 1rem; color: var(--text-gray); font-size: 0.9rem; }

        /* ===================================================
           PAGE: TENTANG KAMI
           =================================================== */
        #page-tentang { background: var(--cream); }
        .about-content-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        .about-img-wrap {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            aspect-ratio: 4/3;
        }
        .about-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .about-img-wrap:hover img { transform: scale(1.05); }
        .about-text h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--red);
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--red);
            display: inline-block;
        }
        .about-text p { font-size: 0.9rem; color: var(--text-gray); line-height: 1.8; margin-bottom: 1rem; }
        .about-text strong { color: var(--red); font-weight: 600; }
        .about-typewriter-box {
            min-height: 230px;
        }
        .type-cursor {
            display: inline-block;
            width: 2.5px;
            height: 1.15em;
            background-color: var(--red);
            margin-left: 2px;
            vertical-align: -2px;
            animation: blinkCursor 0.75s infinite;
            border-radius: 1px;
        }
        @keyframes blinkCursor {
            0%, 49% { opacity: 1; }
            50%, 100% { opacity: 0; }
        }
        .type-cursor.done {
            animation: blinkCursor 0.75s 3 forwards;
        }
        .btn-red {
            background: var(--red);
            color: var(--white);
            padding: 0.7rem 1.6rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid var(--red);
            transition: all 0.25s;
            margin-top: 0.5rem;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .btn-red:hover { background: var(--red-dark); border-color: var(--red-dark); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(200,16,46,0.3); }

        /* Stats strip */
        .about-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2.5rem;
        }
        .stat-card {
            background: var(--white);
            border-radius: 14px;
            padding: 1.5rem 1rem;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            border: 1px solid rgba(0,0,0,0.04);
        }
        .stat-card .num { font-size: 1.8rem; font-weight: 800; color: var(--red); line-height: 1; margin-bottom: 0.4rem; }
        .stat-card .lbl { font-size: 0.8rem; color: var(--text-gray); font-weight: 500; }

        /* ===================================================
           PAGE: LOKASI & KONTAK & ULASAN
           =================================================== */
        #page-kontak { background: var(--cream); }
        .kontak-content-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.2rem 1.5rem 4.5rem;
        }
        .page-banner-chips {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-top: 0.85rem;
        }
        .banner-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            padding: 0.35rem 0.9rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.28);
        }
        .kontak-subtitle {
            text-align: center;
            color: var(--text-gray);
            font-size: 0.92rem;
            margin-bottom: 2rem;
            max-width: 620px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.75rem;
            align-items: stretch;
            margin-bottom: 2.5rem;
        }
        .contact-col {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .contact-card {
            background: var(--white);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 4px 22px rgba(0,0,0,0.06);
            border: 1px solid rgba(200, 16, 46, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .contact-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .contact-card.highlight {
            border-left: 4px solid var(--red);
        }
        .contact-card h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .contact-card h4 .ico {
            font-size: 1.25rem;
            line-height: 1;
        }
        .contact-wa-wrap {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }
        .contact-wa-num {
            background: var(--cream);
            border: 1px solid var(--cream-dark);
            border-radius: 10px;
            padding: 0.75rem 1.1rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            font-family: monospace;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-wa {
            background: #25D366;
            color: var(--white);
            padding: 0.75rem 1.4rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.25s;
            box-shadow: 0 4px 14px rgba(37,211,102,0.3);
        }
        .btn-wa:hover {
            background: #1ebe5d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,211,102,0.4);
            color: var(--white);
        }
        .wa-status-hint {
            font-size: 0.78rem;
            color: var(--text-gray);
            margin-top: 0.65rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .info-list { display: flex; flex-direction: column; gap: 0.85rem; }
        .info-item { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.88rem; color: var(--text-dark); line-height: 1.55; }
        .info-item .ico {
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-top: 2px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(200, 16, 46, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-copy-address {
            background: none;
            border: none;
            color: var(--red);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 6px;
            text-decoration: underline;
            transition: opacity 0.2s;
        }
        .btn-copy-address:hover { opacity: 0.75; }

        /* Fasilitas warung chips */
        .facility-title {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-gray);
            margin-top: 1.1rem;
            margin-bottom: 0.6rem;
        }
        .facility-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .facility-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--cream);
            color: var(--text-dark);
            border: 1px solid rgba(200, 16, 46, 0.12);
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* Map box */
        .map-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .map-box {
            background: #e5e3df;
            border-radius: 14px;
            height: 330px;
            width: 100%;
            margin-top: 0.75rem;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
            flex: 1;
        }
        .map-box iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        .btn-open-maps {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--red);
            color: var(--white);
            border: 1px solid var(--red);
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(200, 16, 46, 0.25);
        }
        .btn-open-maps:hover {
            background: var(--red-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(200, 16, 46, 0.35);
        }

        /* ===================================================
           BAGIAN ULASAN PENGUNJUNG
           =================================================== */
        .reviews-section {
            background: var(--white);
            border-radius: 22px;
            padding: 2.5rem 2rem;
            box-shadow: 0 6px 28px rgba(0,0,0,0.07);
            border: 1px solid rgba(200, 16, 46, 0.08);
            margin-top: 1rem;
        }
        .reviews-header-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            border-bottom: 2px solid var(--cream);
            padding-bottom: 1.25rem;
        }
        .reviews-title-block h3 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .reviews-title-block p {
            font-size: 0.86rem;
            color: var(--text-gray);
            margin: 0;
            line-height: 1.5;
        }
        .reviews-summary-badge {
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
            border: 1px solid #FDE68A;
            border-radius: 14px;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.12);
        }
        .summary-score {
            font-size: 1.8rem;
            font-weight: 800;
            color: #B45309;
            line-height: 1;
        }
        .summary-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .summary-stars {
            color: #F59E0B;
            font-size: 0.95rem;
            letter-spacing: 2px;
        }
        .summary-count {
            font-size: 0.74rem;
            color: #92400E;
            font-weight: 600;
        }

        /* Sub-grid ulasan: Kiri Form, Kanan Feed List */
        .reviews-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 2rem;
            align-items: start;
        }
        .review-form-card {
            background: var(--cream);
            border: 1px solid var(--cream-dark);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        }
        .review-form-card h4 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .review-form-card .form-desc {
            font-size: 0.82rem;
            color: var(--text-gray);
            margin-bottom: 1.25rem;
            line-height: 1.55;
        }

        .review-field-group {
            margin-bottom: 1.15rem;
        }
        .review-label {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.45rem;
        }
        .review-label span.req { color: var(--red); }
        .review-input, .review-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1.5px solid rgba(0,0,0,0.12);
            background: var(--white);
            font-family: 'Poppins', sans-serif;
            font-size: 0.86rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.25s;
            box-sizing: border-box;
        }
        .review-input:focus, .review-textarea:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(200, 16, 46, 0.12);
        }

        /* Interactive Star Rating Picker */
        .star-picker-wrap {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
            background: var(--white);
            padding: 0.65rem 1rem;
            border-radius: 10px;
            border: 1.5px solid rgba(0,0,0,0.1);
        }
        .star-picker {
            display: flex;
            gap: 0.35rem;
        }
        .star-btn {
            background: none;
            border: none;
            font-size: 1.7rem;
            color: #D1D5DB;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: transform 0.15s ease, color 0.15s ease;
            user-select: none;
        }
        .star-btn:hover {
            transform: scale(1.25);
        }
        .star-btn.active, .star-btn.hover {
            color: #F59E0B;
            text-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
        }
        .star-label-text {
            font-size: 0.82rem;
            font-weight: 700;
            color: #B45309;
        }

        .btn-submit-review {
            width: 100%;
            background: var(--red);
            color: var(--white);
            border: none;
            padding: 0.85rem 1.5rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.25s;
            box-shadow: 0 4px 16px rgba(200, 16, 46, 0.3);
            margin-top: 0.5rem;
        }
        .btn-submit-review:hover {
            background: var(--red-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(200, 16, 46, 0.4);
        }
        .btn-submit-review:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Review Feed Container & Cards */
        .reviews-feed-wrap {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .reviews-feed-scroll {
            max-height: 540px;
            overflow-y: auto;
            padding-right: 0.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .reviews-feed-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .reviews-feed-scroll::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.03);
            border-radius: 10px;
        }
        .reviews-feed-scroll::-webkit-scrollbar-thumb {
            background: rgba(200, 16, 46, 0.2);
            border-radius: 10px;
        }
        .reviews-feed-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--red);
        }

        .review-item-card {
            background: var(--cream);
            border: 1px solid rgba(200, 16, 46, 0.08);
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
        }
        .review-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            background: #FFFBF8;
        }
        .review-item-card.is-new {
            animation: highlightNewReview 1.5s ease-out;
            border-left: 4px solid var(--red);
        }
        @keyframes highlightNewReview {
            0% { transform: scale(0.96); opacity: 0; background: #FEF2F2; }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); opacity: 1; }
        }

        .review-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
        }
        .review-author-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .review-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red) 0%, #E11D48 100%);
            color: var(--white);
            font-weight: 700;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(200, 16, 46, 0.25);
        }
        .review-author-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }
        .review-verified-badge {
            font-size: 0.7rem;
            color: #059669;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            margin-top: 2px;
        }
        .review-date {
            font-size: 0.74rem;
            color: var(--text-gray);
            white-space: nowrap;
        }
        .review-stars-row {
            color: #F59E0B;
            font-size: 0.88rem;
            margin-bottom: 0.65rem;
            letter-spacing: 2px;
        }
        .review-text-content {
            font-size: 0.86rem;
            color: #374151;
            line-height: 1.6;
            margin: 0;
            font-style: italic;
        }
        .review-empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* ===================================================
           FOOTER (shared in every page)
           =================================================== */
        .footer {
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, #D41234 0%, var(--red) 42%, var(--red-dark) 100%);
            padding: 2.75rem 1.5rem 1.5rem;
            color: rgba(255,255,255,0.9);
        }
        .footer-glow {
            position: absolute;
            inset: auto -10% -40% auto;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            pointer-events: none;
        }
        .footer-container { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.45fr 1.05fr 1fr;
            gap: 2.25rem;
            margin-bottom: 2rem;
        }
        .footer-brand { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem; }
        .footer-logo-circle {
            width: 48px; height: 48px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            padding: 2px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        }
        .footer-logo-circle img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; display: block; }
        .footer-brand-text { font-size: 0.85rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.3; }
        .footer-brand-sub { font-size: 0.7rem; font-weight: 400; color: rgba(255,255,255,0.78); }
        .footer-desc { font-size: 0.82rem; line-height: 1.7; color: rgba(255,255,255,0.82); }
        .footer-col h5 {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .footer-social-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            margin: 0;
            padding: 0;
        }
        .footer-social-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            min-height: 44px;
            padding: 0.3rem 0;
            text-decoration: none;
            color: rgba(255,255,255,0.88);
            transition: color 0.2s ease, transform 0.2s ease;
            cursor: pointer;
        }
        .footer-social-link:hover {
            color: var(--white);
            transform: translateX(3px);
        }
        .footer-social-link:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 2px;
            border-radius: 4px;
        }
        .footer-social-icon {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: rgba(255,255,255,0.72);
            opacity: 0.9;
        }
        .footer-social-link:hover .footer-social-icon {
            color: var(--white);
            opacity: 1;
        }
        .footer-social-text {
            display: grid;
            gap: 0.05rem;
            min-width: 0;
        }
        .footer-social-text strong {
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .footer-social-text span {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.78);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .footer-contact-item {
            display: grid;
            gap: 0.15rem;
            font-size: 0.84rem;
            color: rgba(255,255,255,0.88);
            margin-bottom: 0.85rem;
        }
        .footer-contact-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: rgba(255,255,255,0.7);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.18);
            padding-top: 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .footer-bottom p { font-size: 0.78rem; color: rgba(255,255,255,0.72); margin: 0; }

        /* ===================================================
           BERANDA: intro, menu favorit, lokasi
           =================================================== */
        a.btn-outline, a.btn-primary { text-decoration: none; }

        /* ===================================================
           SECTION: PROMO BANNER CAROUSEL (STYLE SEPERTI TAKAPEDIA)
           =================================================== */
        .promo-banner-section {
            background: var(--cream);
            padding: 2.25rem 0 1.5rem;
            position: relative;
        }
        .promo-banner-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .promo-banner-wrapper {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            background: #141416;
            box-shadow: 0 14px 34px rgba(0,0,0,0.14), 0 2px 6px rgba(0,0,0,0.06);
            border: 1px solid rgba(200,16,46,0.18);
            aspect-ratio: 21 / 8;
            min-height: 240px;
            max-height: 380px;
        }
        .promo-banner-track {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .promo-banner-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transform: scale(1.02);
            transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                        visibility 0.6s;
            pointer-events: none;
            z-index: 1;
        }
        .promo-banner-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            pointer-events: auto;
            z-index: 2;
        }
        .promo-banner-card {
            display: block;
            width: 100%;
            height: 100%;
            position: relative;
            text-decoration: none;
            color: inherit;
            overflow: hidden;
        }
        .promo-banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .promo-banner-card:hover .promo-banner-img {
            transform: scale(1.03);
        }
        .promo-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15,15,18,0.02) 0%, rgba(15,15,18,0.3) 40%, rgba(15,15,18,0.85) 100%);
            display: flex;
            align-items: flex-end;
            padding: 2rem 2.25rem;
            z-index: 3;
            pointer-events: none;
        }
        .promo-banner-content {
            max-width: 720px;
            pointer-events: auto;
        }
        .promo-banner-title {
            color: #FFFFFF;
            font-size: clamp(1.15rem, 2.6vw, 1.85rem);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.01em;
            text-transform: uppercase;
            text-shadow: 0 2px 10px rgba(0,0,0,0.85);
            margin: 0;
        }
        .promo-banner-desc {
            color: rgba(255,255,255,0.92);
            font-size: clamp(0.8rem, 1.4vw, 0.95rem);
            line-height: 1.5;
            margin: 0.45rem 0 0;
            text-shadow: 0 1px 6px rgba(0,0,0,0.85);
        }
        .promo-banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-top: 0.85rem;
            background: var(--red);
            color: #FFFFFF;
            padding: 0.45rem 1.15rem;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 14px rgba(200,16,46,0.45);
            transition: all 0.2s ease;
        }
        .promo-banner-btn svg {
            width: 15px;
            height: 15px;
            transition: transform 0.2s ease;
        }
        .promo-banner-card:hover .promo-banner-btn {
            background: #E8203A;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(200,16,46,0.6);
        }
        .promo-banner-card:hover .promo-banner-btn svg {
            transform: translateX(3px);
        }

        /* Navigasi Panah Kiri Kanan */
        .promo-banner-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(18, 18, 22, 0.65);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255,255,255,0.25);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0,0,0,0.3);
        }
        .promo-banner-arrow:hover {
            background: var(--red);
            border-color: var(--red);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(200,16,46,0.5);
        }
        .promo-banner-arrow.prev { left: 16px; }
        .promo-banner-arrow.next { right: 16px; }
        .promo-banner-arrow svg {
            width: 20px;
            height: 20px;
        }

        /* Dot Indicators */
        .promo-banner-dots {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 7px;
            z-index: 10;
            padding: 4px 10px;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(6px);
            border-radius: 99px;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .banner-dot {
            width: 8px;
            height: 8px;
            border-radius: 99px;
            background: rgba(255,255,255,0.45);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .banner-dot.active {
            width: 24px;
            background: #FFFFFF;
            box-shadow: 0 0 10px rgba(255,255,255,0.85);
        }

        @media (max-width: 768px) {
            .promo-banner-section {
                padding: 1.25rem 0 0.75rem;
            }
            .promo-banner-wrapper {
                border-radius: 16px;
                aspect-ratio: 16 / 9;
                min-height: 200px;
            }
            .promo-banner-overlay {
                padding: 1.25rem 1.25rem;
            }
            .promo-banner-arrow {
                width: 34px;
                height: 34px;
            }
            .promo-banner-arrow.prev { left: 8px; }
            .promo-banner-arrow.next { right: 8px; }
            .promo-banner-arrow svg {
                width: 16px;
                height: 16px;
            }
        }

        /* ===================================================
           SECTION: CAROUSEL ULASAN
           =================================================== */
        .reviews-carousel-section {
            background: var(--cream);
            padding: 3.25rem 0 3.5rem;
            border-top: 1px solid rgba(200,16,46,0.1);
            overflow: hidden;
        }
        .reviews-carousel-head {
            max-width: 1100px;
            margin: 0 auto 1.5rem;
            padding: 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .reviews-carousel-head h2 {
            font-size: clamp(1.2rem, 3vw, 1.6rem);
            font-weight: 800;
            color: var(--text-dark);
            margin: 0;
            letter-spacing: -0.01em;
        }
        .reviews-carousel-head h2 span {
            color: var(--red);
        }
        .reviews-carousel-nav {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        .btn-carousel-nav {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid rgba(200,16,46,0.3);
            background: var(--white);
            color: var(--red);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, border-color 0.2s ease;
            flex-shrink: 0;
        }
        .btn-carousel-nav:hover {
            background: var(--red);
            border-color: var(--red);
            color: var(--white);
        }
        .btn-carousel-nav:focus-visible {
            outline: 2px solid var(--red);
            outline-offset: 2px;
        }
        .btn-carousel-nav svg { width: 16px; height: 16px; }
        /* Track bergulir */
        .reviews-carousel-track-wrap {
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 0.5rem 1.5rem 1rem;
            /* Snap per kartu */
            scroll-snap-type: x mandatory;
        }
        .reviews-carousel-track-wrap::-webkit-scrollbar { display: none; }
        .reviews-carousel-track {
            display: flex;
            gap: 1rem;
            width: max-content;
            align-items: stretch;
        }
        /* Kartu ulasan */
        .rc-card {
            background: var(--white);
            border: 1px solid rgba(200,16,46,0.09);
            border-radius: 16px;
            padding: 1.4rem 1.5rem;
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            scroll-snap-align: start;
            transition: box-shadow 0.2s ease;
        }
        .rc-card:hover {
            box-shadow: 0 6px 22px rgba(158,12,35,0.1);
        }
        .rc-card-top {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .rc-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red) 0%, #E11D48 100%);
            color: var(--white);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .rc-meta {
            min-width: 0;
            flex: 1;
        }
        .rc-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .rc-time {
            font-size: 0.72rem;
            color: var(--text-gray);
            margin-top: 1px;
        }
        .rc-stars {
            color: #F59E0B;
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            flex-shrink: 0;
        }
        .rc-text {
            font-size: 0.86rem;
            color: #374151;
            line-height: 1.65;
            margin: 0;
            font-style: italic;
            flex: 1;
            /* Batasi 4 baris */
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .rc-empty {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        /* Dot indikator */
        .reviews-carousel-dots {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
            margin-top: 1rem;
            padding: 0 1.5rem;
        }
        .rc-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: rgba(200,16,46,0.2);
            transition: width 0.25s ease, background 0.25s ease;
        }
        .rc-dot.active {
            width: 18px;
            background: var(--red);
        }
        .home-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.74rem;
            font-weight: 700;
            color: var(--red);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.7rem;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
            background: rgba(200,16,46,0.1);
            border: 1px solid rgba(200,16,46,0.14);
        }
        .home-heading {
            font-size: clamp(1.45rem, 3vw, 1.85rem);
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
            margin: 0 0 0.85rem;
            letter-spacing: -0.01em;
        }
        .home-lead {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.7;
            margin: 0;
            max-width: 36rem;
        }
        .home-facts {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0;
            margin: 0;
            padding: 0;
            background: var(--white);
            border: 1.5px solid rgba(200,16,46,0.14);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(158,12,35,0.07);
        }
        .home-facts li {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 1rem 1.15rem;
            border-bottom: 1px solid rgba(200,16,46,0.08);
        }
        .home-facts li:last-child { border-bottom: none; }
        .home-facts .fact-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(200,16,46,0.09);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .home-facts .fact-icon svg {
            width: 16px;
            height: 16px;
            color: var(--red);
        }
        .home-facts .fact-body { display: flex; flex-direction: column; gap: 0.15rem; }
        .home-facts dt,
        .home-facts .fact-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .home-facts .fact-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.45;
        }

        .bestsellers {
            background:
                linear-gradient(180deg, rgba(253,245,240,0.95) 0%, #fff 38%, #FFF7F4 100%);
        }
        .home-menu-head {
            text-align: left;
            margin-bottom: 1.75rem;
            max-width: 36rem;
            padding: 1.1rem 1.2rem;
            border-radius: 14px;
            background: rgba(200,16,46,0.05);
            border: 1px solid rgba(200,16,46,0.1);
        }
        .home-menu-head .home-heading { margin-bottom: 0.45rem; }
        .home-menu-head .home-lead { max-width: none; }

        #page-beranda .product-card {
            background: rgba(255,255,255,0.88);
            border: 1px solid rgba(200,16,46,0.1);
            box-shadow: 0 8px 22px rgba(158,12,35,0.07);
        }
        #page-beranda .product-card:hover {
            border-color: rgba(200,16,46,0.22);
            box-shadow: 0 14px 30px rgba(158,12,35,0.14);
        }
        #page-beranda .product-card-img {
            background: linear-gradient(145deg, #FFF8F4 0%, #F8E4DE 100%);
        }

        .home-empty-menu {
            grid-column: 1 / -1;
            text-align: center;
            color: var(--text-gray);
            padding: 2.5rem 1rem;
            font-size: 0.95rem;
            line-height: 1.6;
            border: 1px dashed rgba(200,16,46,0.28);
            border-radius: 12px;
            background: rgba(200,16,46,0.04);
        }

        .home-visit {
            position: relative;
            overflow: hidden;
            background: var(--red-dark);
            color: var(--white);
            padding: 3rem 1.5rem;
        }
        /* Hiasan strip diagonal — arah gerak, bukan dekorasi acak */
        .home-visit::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                -55deg,
                rgba(255,255,255,0.03) 0px,
                rgba(255,255,255,0.03) 1px,
                transparent 1px,
                transparent 28px
            );
            pointer-events: none;
        }
        .home-visit-inner {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr auto;
            gap: 1.75rem 2.5rem;
            align-items: center;
        }
        .home-visit h2 {
            font-size: clamp(1.4rem, 3vw, 1.85rem);
            font-weight: 800;
            margin: 0 0 0.55rem;
            letter-spacing: -0.01em;
        }
        .home-visit p {
            font-size: 0.9rem;
            line-height: 1.65;
            color: rgba(255,255,255,0.88);
            margin: 0;
        }
        .home-visit-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1.25rem;
            margin-top: 0.75rem;
        }
        .home-visit-detail .vd-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.78);
        }
        .home-visit-detail .vd-item svg {
            width: 13px;
            height: 13px;
            opacity: 0.72;
            flex-shrink: 0;
        }
        .home-visit-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
            justify-content: flex-end;
        }
        .home-visit .btn-primary {
            box-shadow: 0 6px 18px rgba(0,0,0,0.22);
        }
        .home-visit .btn-outline {
            border-color: rgba(255,255,255,0.75);
            background: rgba(255,255,255,0.08);
        }

        .quote-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        .quote-item { background: var(--white); border-radius: 12px; padding: 1.4rem 1.25rem; border: 1px solid rgba(26,26,26,0.08); }
        .quote-item p { font-size: 0.88rem; line-height: 1.7; margin-bottom: 0.9rem; }
        .quote-item cite { font-size: 0.8rem; font-style: normal; font-weight: 700; color: var(--red); }

        @media (prefers-reduced-motion: reduce) {
            .hero-slide { transition: none; }
            .hero-dot::after { transition: none; }
            .btn-primary, .btn-outline, .btn-cta-full, .btn-pesan-item, .product-card, .product-card-img img {
                transition: none !important;
            }
        }

        /* ===================================================
           SPLASH PEMBUKA: logo + nama, cincin loading, lalu tirai naik
           =================================================== */
        .splash {
            position: fixed; inset: 0; z-index: 5000;
            background: var(--red);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 1.6rem; cursor: pointer;
            transition: transform 0.75s cubic-bezier(0.76, 0, 0.24, 1);
        }
        .splash--out { transform: translateY(-100%); pointer-events: none; }
        .splash-mark { position: relative; width: 168px; height: 168px; display: flex; align-items: center; justify-content: center; }
        .splash-ring { position: absolute; inset: 0; width: 100%; height: 100%; transform: rotate(-90deg); }
        .splash-ring circle { fill: none; stroke-width: 4; stroke-linecap: round; }
        .splash-ring .track { stroke: rgba(255,255,255,0.22); }
        .splash-ring .bar {
            stroke: #fff; stroke-dasharray: 503; stroke-dashoffset: 503;
            animation: splashRing 1.9s 0.3s cubic-bezier(0.65, 0, 0.35, 1) forwards;
        }
        .splash-logo {
            width: 128px; height: 128px; border-radius: 50%;
            background: #fff; padding: 5px; overflow: hidden;
            box-shadow: 0 14px 40px rgba(0,0,0,0.28);
            transform: scale(0.6); opacity: 0;
            animation: splashPop 0.6s 0.05s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .splash-logo img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; display: block; }
        .splash-name {
            font-size: clamp(1.5rem, 5vw, 2.2rem); font-weight: 800; color: #fff;
            text-transform: uppercase; letter-spacing: 0.06em; line-height: 1.15; text-align: center;
        }
        .splash-name span { display: block; overflow: hidden; }
        .splash-name span i {
            display: block; font-style: normal; transform: translateY(110%);
            animation: splashRise 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .splash-name span:nth-child(1) i { animation-delay: 0.7s; }
        .splash-name span:nth-child(2) i { animation-delay: 0.85s; }
        @keyframes splashRing { to { stroke-dashoffset: 0; } }
        @keyframes splashPop  { to { transform: scale(1); opacity: 1; } }
        @keyframes splashRise { to { transform: translateY(0); } }
        @media (prefers-reduced-motion: reduce) {
            .splash, .splash-logo, .splash-ring .bar, .splash-name span i { animation: none !important; }
            .splash { transition: opacity 0.3s; }
            .splash-logo { transform: none; opacity: 1; }
            .splash-ring .bar { stroke-dashoffset: 0; }
            .splash-name span i { transform: none; }
            .splash--out { transform: none; opacity: 0; }
        }

        /* ===================================================
           RESPONSIVE
           =================================================== */
        @media (max-width: 900px) {
            .navbar-nav { display: none; }
            .hamburger  { display: block; }
            .about-grid { grid-template-columns: 1fr; gap: 2rem; }
            .cards-grid { grid-template-columns: repeat(2, 1fr); }
            .about-stats { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid > *:first-child { grid-column: 1 / -1; }
            .menu-header-wrap { flex-direction: column; align-items: stretch; text-align: center; gap: 1.25rem; }
            .menu-header-wrap .menu-intro { text-align: center; }
            .menu-header-wrap .menu-intro p { margin: 0 auto; }
            .menu-search-container { width: 100%; max-width: 100%; }
            .filter-row { justify-content: center; }
            .home-intro-grid { grid-template-columns: 1fr; gap: 1.75rem; }
            .home-visit-inner { grid-template-columns: 1fr; }
            .home-visit-actions { justify-content: flex-start; }
            .reviews-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .contact-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .section-title { white-space: normal; }
        }
        @media (max-width: 600px) {
            .hero {
                min-height: calc(100dvh - var(--nav-h));
                align-items: flex-end;
            }
            .hero-container {
                padding: 2.25rem 1.15rem 3.75rem;
                gap: 1rem;
            }
            .hero-brand-mark { width: 58px; height: 58px; }
            .hero-title { max-width: none; font-size: clamp(2rem, 9vw, 2.6rem); }
            .hero-subtitle { font-size: 0.92rem; line-height: 1.55; }
            .hero-actions { width: 100%; }
            .hero-actions .btn-primary,
            .hero-actions .btn-outline {
                flex: 1 1 100%;
                width: 100%;
            }
            .hero-info-strip { gap: 0.9rem; padding: 0.6rem 1rem; font-size: 0.75rem; }
            .hero-info-strip .strip-sep { display: none; }
            .cards-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .contact-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .reviews-grid { grid-template-columns: 1fr; gap: 1.75rem; }
            .reviews-section { padding: 1.6rem 1.25rem; }
            .reviews-header-wrap { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .reviews-summary-badge { width: 100%; justify-content: center; }
            .map-box { height: 260px; }
            .contact-wa-wrap { flex-direction: column; align-items: stretch; }
            .contact-wa-num { width: 100%; justify-content: center; }
            .btn-wa { width: 100%; justify-content: center; }
            .about-stats { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .page-banner-text h2 { font-size: 1.5rem; }
            .page-banner-logo { width: 70px; height: 70px; margin-top: -5px; }
            .bestsellers { padding: 2.5rem 1.15rem; }
            .home-intro { padding: 2.5rem 1.15rem; }
            .home-visit { padding: 2.25rem 1.15rem; }
            .home-visit-inner { grid-template-columns: 1fr; }
            .home-visit-actions { justify-content: flex-start; width: 100%; }
            .home-visit-actions .btn-primary,
            .home-visit-actions .btn-outline { flex: 1 1 100%; }
            .quote-grid { grid-template-columns: 1fr; }
            .menu-content, .about-content-wrap, .kontak-content-wrap { padding: 1.5rem 1rem 3rem; }
        }
        @media (max-width: 480px) {
            .hamburger { min-width: 44px; min-height: 44px; padding: 10px 8px; }
            .hamburger span { margin: 4px 0; }
            .mobile-menu ul a { min-height: 44px; display: flex; align-items: center; font-size: 1rem; }
            .hero-info-strip { flex-direction: column; align-items: flex-start; gap: 0.4rem; text-align: left; }
            .hero-info-strip .strip-item { white-space: normal; min-width: 0; }
            .section-title { font-size: 1.3rem; }
            .cards-grid { grid-template-columns: 1fr; gap: 1rem; }
            .product-card-footer { flex-wrap: wrap; }
            .product-card-price, .btn-pesan-item { min-height: 44px; display: inline-flex; align-items: center; }
            .filter-row { gap: 0.5rem; margin-bottom: 1.5rem; }
            .filter-btn { min-height: 44px; flex: 1 1 auto; justify-content: center; padding: 0.5rem 0.75rem; }
            .menu-search-input { font-size: 16px; min-height: 46px; }
            .btn-search-clear { width: 28px; height: 28px; right: 10px; }
            .promo-banner-wrapper { aspect-ratio: auto; min-height: 190px; max-height: none; }
            .promo-banner-overlay { padding: 1rem; }
            .promo-banner-title { font-size: 1.1rem; }
            .promo-banner-desc { font-size: 0.76rem; max-width: 100%; }
            .promo-banner-btn { min-height: 40px; padding: 0.5rem 1rem; }
            .modal-overlay { align-items: flex-end; padding: 0; }
            .modal-box { max-width: 100%; max-height: min(94dvh, 760px); border-radius: 20px 20px 0 0; padding-bottom: env(safe-area-inset-bottom); }
            .modal-header { padding: 0.85rem 1rem; position: sticky; top: 0; z-index: 2; }
            .modal-body { padding: 1rem; }
            .modal-footer { padding: 0.85rem 1rem calc(0.85rem + env(safe-area-inset-bottom)); }
            .btn-close-modal { width: 44px; height: 44px; }
            .btn-qty { width: 36px; height: 36px; }
            .btn-del-item { min-width: 44px; min-height: 44px; }
            .order-item-row { align-items: flex-start; flex-wrap: wrap; }
            .order-item-info { flex: 1 1 100%; }
            .order-qty-control { margin-left: auto; }
            .order-item-subtotal { min-width: 0; }
            .btn-order-system, .btn-order-secondary, .btn-order-clear { min-height: 46px; }
            .order-success-actions .btn-order-system, .order-success-actions .btn-order-secondary { flex: 1 1 100%; }
            .toast-notif { max-width: calc(100vw - 24px); border-radius: 14px; white-space: normal; text-align: center; padding: 0.75rem 1rem; }
        }
    </style>
</head>
<body>

{{-- SPLASH PEMBUKA --}}
<div class="splash" id="splash" aria-label="Memuat Angkringan Sekota">
    <div class="splash-mark">
        <svg class="splash-ring" viewBox="0 0 168 168" aria-hidden="true">
            <circle class="track" cx="84" cy="84" r="80"/>
            <circle class="bar" cx="84" cy="84" r="80"/>
        </svg>
        <div class="splash-logo">
            <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $profil->nama_usaha ?? 'Angkringan Sekota' }}">
        </div>
    </div>
    <div class="splash-name"><span><i>Angkringan</i></span><span><i>Sekota</i></span></div>
</div>
<noscript><style>.splash { display: none; }</style></noscript>

{{-- ============================================================
     NAVBAR — always fixed, z-index 1000
     ============================================================ --}}
<nav class="navbar" id="navbar">
    <a class="navbar-brand" onclick="goTo('beranda')">
        <div class="navbar-logo-circle">
            <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $profil->nama_usaha ?? 'Angkringan Sekota' }}">
        </div>
        <div class="navbar-brand-text">Angkringan<br><span style="font-size:.95em">Sekota</span></div>
    </a>
    <ul class="navbar-nav" id="navLinks">
        <li><a class="active" onclick="goTo('beranda')">Beranda</a></li>
        <li><a onclick="goTo('menu')">Menu</a></li>
        <li><a onclick="goTo('tentang')">Tentang Kami</a></li>
        <li><a onclick="goTo('kontak')">Lokasi &amp; Kontak</a></li>
    </ul>
    <button class="hamburger" id="hamburgerBtn" type="button" aria-label="Buka menu" aria-controls="mobileMenu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- Mobile dropdown --}}
<div class="mobile-menu" id="mobileMenu">
    <ul id="mobileNavLinks">
        <li><a class="active" onclick="goTo('beranda'); closeMob()">Beranda</a></li>
        <li><a onclick="goTo('menu'); closeMob()">Menu</a></li>
        <li><a onclick="goTo('tentang'); closeMob()">Tentang Kami</a></li>
        <li><a onclick="goTo('kontak'); closeMob()">Lokasi &amp; Kontak</a></li>
    </ul>
</div>

{{-- ==============================================================
     PAGE: BERANDA
     ============================================================== --}}
<div id="page-beranda" class="page active">
    @php
        // Ulasan asli saja. Kosong = bagian tidak ditampilkan.
        $testimoni = [];
        $namaUsaha = $profil->nama_usaha ?? 'Angkringan Sekota';
        $alamatUsaha = $profil->alamat ?? 'Tulungagung, Jawa Timur';
        $jamBuka = $profil->jam_buka ?? '17.00 - 23.00 WIB';
    @endphp

    <section class="hero" aria-label="Beranda {{ $namaUsaha }}">
        <div class="hero-slideshow" id="heroSlideshow">
            <img
                class="hero-slide active"
                src="{{ asset('images/hero/slide1.jpg') }}"
                alt="Suasana malam {{ $namaUsaha }}"
                width="1920"
                height="1080"
                fetchpriority="high"
                decoding="async">
            <img
                class="hero-slide"
                src="{{ asset('images/hero/slide2.jpg') }}"
                alt="Hidangan angkringan di meja lesehan"
                width="1920"
                height="1080"
                loading="lazy"
                decoding="async">
            <img
                class="hero-slide"
                src="{{ asset('images/hero/slide3.jpg') }}"
                alt="Wedangan dan camilan khas angkringan"
                width="1920"
                height="1080"
                loading="lazy"
                decoding="async">
        </div>
        <div class="hero-overlay" aria-hidden="true"></div>

        <div class="hero-container">
            <div class="hero-brand-mark">
                <img
                    src="{{ $profil->logo_url ?? asset('images/logo.png') }}"
                    alt=""
                    width="72"
                    height="72"
                    decoding="async">
            </div>
            <h1 class="hero-title">{{ $profil->judul_beranda ?: $namaUsaha }}</h1>
            @if($profil->deskripsi_beranda)
                <p class="hero-subtitle">{{ $profil->deskripsi_beranda }}</p>
            @endif

        </div>

        <div class="hero-dots" id="heroDots" role="group" aria-label="Pilih foto hero">
            <button type="button" class="hero-dot active" onclick="heroGoTo(0)" aria-label="Foto 1" aria-current="true"></button>
            <button type="button" class="hero-dot" onclick="heroGoTo(1)" aria-label="Foto 2"></button>
            <button type="button" class="hero-dot" onclick="heroGoTo(2)" aria-label="Foto 3"></button>
        </div>
    </section>

    {{-- Strip info langsung di bawah hero: data konkret, bukan klaim --}}
    <div class="hero-info-strip" aria-label="Info singkat">
        <span class="strip-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Buka tiap hari, {{ $jamBuka }}
        </span>
        <span class="strip-sep" aria-hidden="true"></span>
        <span class="strip-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $alamatUsaha }}
        </span>
        <span class="strip-sep" aria-hidden="true"></span>
        <span class="strip-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Mulai Rp 2.000,-
        </span>
    </div>

    {{-- ============================================================
         BANNER PROMO CAROUSEL (MENGGANTIKAN BEKAS BAGIAN ULASAN)
         Dapat diganti oleh pegawai melalui web admin
         ============================================================ --}}
    <section class="promo-banner-section" aria-label="Banner Promo Angkringan Sekota">
        <div class="promo-banner-container">
            <div class="promo-banner-wrapper" id="promoBannerWrap">
                @php
                    $activeBanners = isset($banners) ? $banners : collect();
                @endphp

                @if($profil->tampilkan_banner && $activeBanners->count() > 0)
                    <div class="promo-banner-track" id="promoBannerTrack">
                        @foreach($activeBanners as $index => $banner)
                            <div class="promo-banner-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" class="promo-banner-card" {!! str_starts_with($banner->link, 'http') ? 'target="_blank" rel="noopener"' : '' !!}>
                                @else
                                    <div class="promo-banner-card">
                                @endif

                                    <img src="{{ $banner->gambar_url }}" alt="{{ $banner->judul ?? 'Banner Promo' }}" class="promo-banner-img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">

                                    @if($banner->judul || $banner->deskripsi)
                                        <div class="promo-banner-overlay">
                                            <div class="promo-banner-content">
                                                @if($banner->judul)
                                                    <h3 class="promo-banner-title">{{ $banner->judul }}</h3>
                                                @endif
                                                @if($banner->deskripsi)
                                                    <p class="promo-banner-desc">{{ $banner->deskripsi }}</p>
                                                @endif
                                                @if($banner->link)
                                                    <span class="promo-banner-btn">
                                                        <span>Lihat Detail</span>
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                @if($banner->link)
                                    </a>
                                @else
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($activeBanners->count() > 1)
                        <button type="button" class="promo-banner-arrow prev" id="bannerPrevBtn" aria-label="Banner Sebelumnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="promo-banner-arrow next" id="bannerNextBtn" aria-label="Banner Berikutnya">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg>
                        </button>

                        <div class="promo-banner-dots" id="bannerDotsWrap" role="tablist">
                            @foreach($activeBanners as $index => $banner)
                                <button type="button" class="banner-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToBannerSlide({{ $index }})" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="promo-banner-track">
                        <div class="promo-banner-slide active">
                            <div class="promo-banner-card">
                                <img src="{{ asset('images/hero/slide1.jpg') }}" alt="Angkringan Sekota" class="promo-banner-img">
                                <div class="promo-banner-overlay">
                                    <div class="promo-banner-content">
                                        <h3 class="promo-banner-title">Angkringan Sekota Tulungagung</h3>
                                        <p class="promo-banner-desc">Buka tiap hari 17.00 - 23.00 WIB • Makanan &amp; Wedangan Khas Angkringan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bestsellers" aria-labelledby="home-menu-heading">
        <div class="bestsellers-container">
            <div class="home-menu-head">
                <span class="home-kicker">Dari dapur</span>
                <h2 id="home-menu-heading" class="home-heading">Menu favorit untuk mulai</h2>
                <p class="home-lead">Pilih beberapa item, lalu lanjut ke halaman menu kalau mau lihat semuanya.</p>
            </div>

            <div class="cards-grid">
                @forelse($produk->take(6) as $item)
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             width="400"
                             height="400"
                             loading="lazy"
                             decoding="async">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }} @if($item->is_best_seller)<span title="Best seller">⭐</span>@endif</div>
                        @if($item->stok <= 0)<div class="hint" style="color:#B91C1C">Stok habis</div>@endif
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" @if($item->stok <= 0) disabled style="opacity:.5;cursor:not-allowed" @else onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)" @endif>
                                {{ $item->stok <= 0 ? 'Habis' : '+ Pesan' }}
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="home-empty-menu">
                    Menu sedang dipersiapkan. Silakan cek lagi nanti, atau langsung mampir ke warung.
                </div>
                @endforelse
            </div>

            @if($produk->isNotEmpty())
            <button type="button" class="btn-cta-full" onclick="goTo('menu')">Lihat Semua Menu</button>
            @endif
        </div>
    </section>

    @if(count($testimoni))
    <section class="home-intro" aria-labelledby="home-quotes-heading">
        <div class="bestsellers-container">
            <h2 id="home-quotes-heading" class="home-heading">Kata pelanggan</h2>
            <div class="quote-grid" style="margin-top:1.5rem">
                @foreach($testimoni as $t)
                <blockquote class="quote-item"><p>{{ $t['isi'] }}</p><cite>{{ $t['nama'] }}</cite></blockquote>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         CAROUSEL ULASAN PENGUNJUNG (DIPINDAH PAS DI ATAS FOOTER)
         Data dari: $ulasan (Testimonial::aktif()->terbaru()->get())
         ============================================================ --}}
    <section class="reviews-carousel-section" aria-labelledby="rc-heading" id="home-reviews-carousel">
        @php $totalUlasan = $ulasan->count(); @endphp

        <div class="reviews-carousel-head">
            <h2 id="rc-heading">Kata pengunjung <span>Angkringan Sekota</span></h2>
            @if($totalUlasan > 0)
            <div class="reviews-carousel-nav" role="group" aria-label="Navigasi ulasan">
                <button type="button" class="btn-carousel-nav" id="rcPrev" aria-label="Ulasan sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" class="btn-carousel-nav" id="rcNext" aria-label="Ulasan berikutnya">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
            @endif
        </div>

        @if($totalUlasan > 0)
        <div class="reviews-carousel-track-wrap" id="rcTrack" tabindex="0" aria-label="Daftar ulasan pengunjung">
            <div class="reviews-carousel-track">
                @foreach($ulasan as $u)
                @php
                    $bintang = str_repeat('★', $u->rating) . str_repeat('☆', 5 - $u->rating);
                @endphp
                <article class="rc-card">
                    <div class="rc-card-top">
                        <div class="rc-avatar" aria-hidden="true">{{ $u->inisial }}</div>
                        <div class="rc-meta">
                            <div class="rc-name">{{ $u->customer_name }}</div>
                            <div class="rc-time">{{ $u->waktu_format }}</div>
                        </div>
                        <div class="rc-stars" aria-label="Bintang {{ $u->rating }} dari 5">{{ $bintang }}</div>
                    </div>
                    <p class="rc-text">&ldquo;{{ $u->review }}&rdquo;</p>
                </article>
                @endforeach
            </div>
        </div>
        <div class="reviews-carousel-dots" id="rcDots" aria-hidden="true"></div>
        @else
        <p class="rc-empty">Belum ada ulasan. <button type="button" onclick="goTo('kontak')" style="background:none;border:none;color:var(--red);font-weight:700;cursor:pointer;font-family:inherit;font-size:inherit;">Jadilah yang pertama memberi ulasan.</button></p>
        @endif
    </section>

    @include('partials._footer')
</div>

{{-- ==============================================================
     PAGE: MENU
     ============================================================== --}}
<div id="page-menu" class="page">

    {{-- Banner merah --}}
    <div class="page-banner">
        <div class="page-banner-text">
            <h2>Menu Kami</h2>
            <p>Pilihan lauk, sate, dan minuman khas angkringan.</p>
        </div>
    </div>

    <div class="menu-content">
        {{-- Intro & Search Bar --}}
        <div class="menu-header-wrap">
            <div class="menu-intro">
                <h3>Angkringan Sekota</h3>
                <div class="city">Tulungagung</div>
                <p>Dari obrolan santai sampai cerita panjang, semua hidangan favorit siap menemani.</p>
            </div>
            <div class="menu-search-container">
                <div class="menu-search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" 
                           id="menuSearchInput" 
                           class="menu-search-input" 
                           placeholder="Cari menu (pecel, es teh, dll)..." 
                           autocomplete="off" 
                           oninput="handleMenuSearch(this.value)">
                    <button type="button" 
                            id="btnSearchClear" 
                            class="btn-search-clear" 
                            onclick="clearMenuSearch()" 
                            style="display:none;" 
                            title="Hapus pencarian">✕</button>
                </div>
                <div id="searchResultNote" class="search-result-note" style="display:none;"></div>
            </div>
        </div>

        @php
            $pMakanan = $produk->filter(fn($p) => strtolower($p->kategori ?? '') === 'makanan');
            $pMinuman = $produk->filter(fn($p) => strtolower($p->kategori ?? '') === 'minuman');
            $pLainnya = $produk->filter(fn($p) => !in_array(strtolower($p->kategori ?? ''), ['makanan','minuman']));
        @endphp

        {{-- Filter Row --}}
        <div class="filter-row">
            <button type="button" class="filter-btn active" onclick="pilihKategori(this,'semua')">
                Semua <span class="filter-count">{{ $produk->count() }}</span>
            </button>
            <button type="button" class="filter-btn" onclick="pilihKategori(this,'makanan')">
                Makanan <span class="filter-count">{{ $pMakanan->count() }}</span>
            </button>
            <button type="button" class="filter-btn" onclick="pilihKategori(this,'minuman')">
                Minuman <span class="filter-count">{{ $pMinuman->count() }}</span>
            </button>
            @if($pLainnya->count())
            <button type="button" class="filter-btn" onclick="pilihKategori(this,'lainnya')">
                Lainnya <span class="filter-count">{{ $pLainnya->count() }}</span>
            </button>
            @endif
        </div>

        {{-- Empty State untuk Search --}}
        <div id="menuNoResults" class="menu-empty-state" style="display:none;">
            <div class="empty-icon">🍽️🔍</div>
            <h4>Menu Tidak Ditemukan</h4>
            <p>Tidak ada hidangan yang cocok dengan pencarian "<strong id="searchQueryEcho"></strong>".</p>
            <button type="button" class="btn-reset-filter" onclick="resetMenuFilters()">Tampilkan Semua Menu</button>
        </div>

        @if($produk->isEmpty())
        <div class="menu-empty">🍽️ Menu sedang dipersiapkan. Silakan kunjungi kembali.</div>
        @endif

        {{-- Makanan --}}
        @if($pMakanan->count())
        <div class="menu-cat-block" data-kat="makanan">
            <div class="menu-cat-label">
                <div class="sub">Menu Paling Diburu</div>
                <h4><span>Makanan</span></h4>
            </div>
            <div class="cards-grid">
                @foreach($pMakanan as $item)
                <div class="product-card" data-nama="{{ strtolower($item->nama) }}" data-kategori="makanan">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }} @if($item->is_best_seller)<span title="Best seller">⭐</span>@endif</div>
                        @if($item->stok <= 0)<div class="hint" style="color:#B91C1C">Stok habis</div>@endif
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" @if($item->stok <= 0) disabled style="opacity:.5;cursor:not-allowed" @else onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)" @endif>
                                {{ $item->stok <= 0 ? 'Habis' : '+ Pesan' }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Minuman --}}
        @if($pMinuman->count())
        <div class="menu-cat-block" data-kat="minuman">
            <div class="menu-cat-label">
                <div class="sub">Segar &amp; Menyegarkan</div>
                <h4><span>Minuman</span></h4>
            </div>
            <div class="cards-grid">
                @foreach($pMinuman as $item)
                <div class="product-card" data-nama="{{ strtolower($item->nama) }}" data-kategori="minuman">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }} @if($item->is_best_seller)<span title="Best seller">⭐</span>@endif</div>
                        @if($item->stok <= 0)<div class="hint" style="color:#B91C1C">Stok habis</div>@endif
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" @if($item->stok <= 0) disabled style="opacity:.5;cursor:not-allowed" @else onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)" @endif>
                                {{ $item->stok <= 0 ? 'Habis' : '+ Pesan' }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Lainnya --}}
        @if($pLainnya->count())
        <div class="menu-cat-block" data-kat="lainnya">
            <div class="menu-cat-label">
                <div class="sub">Tersedia Juga</div>
                <h4><span>Lainnya</span></h4>
            </div>
            <div class="cards-grid">
                @foreach($pLainnya as $item)
                <div class="product-card" data-nama="{{ strtolower($item->nama) }}" data-kategori="lainnya">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }} @if($item->is_best_seller)<span title="Best seller">⭐</span>@endif</div>
                        @if($item->stok <= 0)<div class="hint" style="color:#B91C1C">Stok habis</div>@endif
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" @if($item->stok <= 0) disabled style="opacity:.5;cursor:not-allowed" @else onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)" @endif>
                                {{ $item->stok <= 0 ? 'Habis' : '+ Pesan' }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @include('partials._footer')
</div>

{{-- ==============================================================
     PAGE: TENTANG KAMI
     ============================================================== --}}
<div id="page-tentang" class="page">

    <div class="page-banner">
        <div class="page-banner-text">
            <h2>Tentang Kami</h2>
            <p>Warung angkringan khas Jawa Timur sejak bertahun-tahun.</p>
        </div>
    </div>

    <div class="about-content-wrap">
        <div class="about-grid">
            <div class="about-img-wrap">
                <img src="{{ asset('images/about.jpg') }}"
                     alt="Angkringan Sekota"
                     onerror="this.src='https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&q=80'">
            </div>
            <div class="about-text" onclick="finishTypewriter()" title="Klik untuk menampilkan seluruh teks langsung">
                <h3>Siapa Kami</h3>
                <div class="about-typewriter-box" id="aboutTypewriterBox">
                    <p>{!! nl2br(e($profil->teks_tentang_kami ?: 'Angkringan Sekota adalah warung makan khas angkringan di Tulungagung, Jawa Timur.')) !!}</p>
                    @if($profil->visi)
                        <p><strong>Visi:</strong> {{ $profil->visi }}</p>
                    @endif
                    @if($profil->misi)
                        <p><strong>Misi:</strong> {{ $profil->misi }}</p>
                    @endif
                    <p>Buka setiap hari mulai pukul <strong>{{ $profil->jam_buka ?? '17.00 - 23.00 WIB' }}</strong>. Kami selalu menyambut Anda dengan hangat dan ramah.</p>
                </div>
                <button class="btn-red" onclick="event.stopPropagation(); goTo('menu')">Lihat Menu Kami →</button>
            </div>
        </div>

        <div class="about-stats">
            <div class="stat-card">
                <div class="num">{{ $produk->count() }}+</div>
                <div class="lbl">Pilihan Menu</div>
            </div>
            <div class="stat-card">
                <div class="num">7</div>
                <div class="lbl">Hari Buka</div>
            </div>
            <div class="stat-card">
                <div class="num">⭐</div>
                <div class="lbl">Favorit Warga Tulungagung</div>
            </div>
        </div>
    </div>

    @include('partials._footer')
</div>

{{-- ==============================================================
     PAGE: LOKASI & KONTAK
     ============================================================== --}}
@include('partials._kontak')
{{-- ==============================================================
     FLOATING CART BAR & MODAL PESANAN
     ============================================================== --}}
<div id="cartBar" class="cart-bar" style="display: none;">
    <div class="cart-bar-content">
        <div class="cart-bar-info" onclick="bukaModalPesanan()">
            <span class="cart-icon">🛒</span>
            <div>
                <strong id="cartItemCount">0 Menu Dipilih</strong>
                <div id="cartTotalPrice" class="cart-total-price">Rp 0</div>
            </div>
        </div>
        <button type="button" class="btn-cart-view" onclick="bukaModalPesanan()">
            Lihat Pesanan (<span id="cartBadgeCount">0</span>) →
        </button>
    </div>
</div>

<!-- Modal Rincian Pesanan -->
<div id="orderModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title" id="orderModalTitle">
                <span>🛒</span> Pesanan Makanan &amp; Minuman
            </div>
            <button type="button" class="btn-close-modal" id="btnCloseOrderModalHeader" onclick="tutupModalPesanan()" aria-label="Tutup">✕</button>
        </div>

        <div class="modal-body">
            <!-- List Item yang dipesan -->
            <div id="orderItemsContainer" class="order-items-list">
                <!-- Diisi via JS -->
            </div>

            <!-- Ringkasan Total -->
            <div class="order-summary-box">
                <div class="summary-row">
                    <span>Total Item:</span>
                    <strong id="modalTotalItems">0 porsi</strong>
                </div>
                <div class="summary-row total-highlight">
                    <span>Total Pembayaran:</span>
                    <strong id="modalTotalPrice">Rp 0</strong>
                </div>
            </div>

            <!-- Form Pemesan -->
            <div class="order-form-box">
                <h5 class="order-form-title">Data Pemesan</h5>
                <div class="order-form-group">
                    <label>Nama Pemesan <span style="color:#DC2626">*</span></label>
                    <input type="text" id="orderNama" class="order-input" placeholder="Masukkan nama Anda..." autocomplete="off">
                    <div id="orderNamaError" class="input-error-msg">⚠️ Mohon masukkan nama pemesan terlebih dahulu.</div>
                </div>

                <div class="order-form-group">
                    <label>Tipe Pemesanan</label>
                    <div class="order-type-tabs">
                        <div id="typeTabDineIn" class="type-tab-card active" onclick="pilihTipePemesanan('dine_in')">
                            <span class="tab-icon">🪑</span>
                            <div>
                                <div class="tab-title">Makan di Tempat</div>
                                <div class="tab-sub">Dine-in di angkringan</div>
                            </div>
                        </div>
                        <div id="typeTabTakeaway" class="type-tab-card" onclick="pilihTipePemesanan('takeaway')">
                            <span class="tab-icon">🥡</span>
                            <div>
                                <div class="tab-title">Bawa Pulang</div>
                                <div class="tab-sub">Takeaway / bungkus</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="orderTypeValue" value="dine_in">
                </div>

                <div class="order-form-group" id="tableGroup">
                    <label>Nomor Meja</label>
                    <input type="text" id="orderMeja" class="order-input" placeholder="Contoh: Meja 03 / Lesehan Depan">
                </div>

                @if($pengaturanTransaksi->pengiriman_aktif)
                <div class="order-type-tabs" style="margin-top:.75rem">
                    <div id="typeTabKirim" class="type-tab-card" onclick="pilihTipePemesanan('pengiriman')">
                        <span class="tab-icon">🛵</span>
                        <div>
                            <div class="tab-title">Dikirim</div>
                            <div class="tab-sub">Area: {{ $pengaturanTransaksi->area_pengiriman ?: 'Hubungi kami' }}</div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="order-form-group" id="orderAlamatGroup" style="display:none">
                    <label>Alamat Pengiriman <span style="color:#DC2626">*</span></label>
                    <textarea id="orderAlamat" class="order-input" rows="2" placeholder="Nama jalan, nomor rumah, patokan, dan kota..."></textarea>
                </div>

                <div class="order-form-group">
                    <label>Metode Pembayaran <span style="color:#DC2626">*</span></label>
                    <select id="orderMetodeBayar" class="order-input">
                        @if($pengaturanTransaksi->tunai_aktif)<option value="tunai">Cash / Tunai (bayar di kasir)</option>@endif
                        @if($pengaturanTransaksi->qris_aktif)<option value="qris">QRIS (unggah bukti bayar)</option>@endif
                    </select>
                    @if($pengaturanTransaksi->qris_aktif)
                        <div id="qrisPanel" style="display:none;margin-top:12px;padding:12px;border:1px solid #F3D2D8;border-radius:12px;background:#FFF8F8">
                            <div style="display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap">
                                @if($pengaturanTransaksi->qrisUrl())
                                    <img id="gambarQrisMenu" src="{{ $pengaturanTransaksi->qrisUrl() }}" alt="Kode QRIS {{ $pengaturanTransaksi->qris_nama_merchant ?: 'ANGKRINGAN SEKOTA' }}" title="Klik untuk memperbesar QRIS" style="width:132px;height:132px;object-fit:contain;background:#fff;border:1px solid #eee;border-radius:8px;padding:6px;cursor:zoom-in">
                                @else
                                    <div style="font-size:.78rem;color:#991B1B">Kode QRIS belum tersedia. Silakan hubungi kasir.</div>
                                @endif
                                <div style="flex:1;min-width:190px">
                                    <strong style="font-size:.86rem">QRIS {{ $pengaturanTransaksi->qris_nama_merchant ?: 'ANGKRINGAN SEKOTA' }}</strong>
                                    <div class="hint" style="font-size:.75rem;color:#666;margin-top:3px">Nominal: <span id="qrisTotal">Rp 0</span></div>
                                    <div class="hint" style="font-size:.75rem;color:#666;margin-top:3px">Transfer sesuai nominal, lalu unggah bukti bayar di sini. Setelah divalidasi, pesanan langsung diproses.</div>
                                    <input type="file" id="buktiQris" accept="image/jpeg,image/png,image/webp" class="order-input" style="display:none;margin-top:8px">
                                    <button type="button" id="btnPilihBuktiQris" style="margin-top:8px;border:1px solid #C8102E;background:#fff;color:#C8102E;border-radius:8px;padding:8px 12px;font-weight:700;font-size:.8rem;cursor:pointer">📷 Pilih Foto Bukti</button>
                                    <div id="namaBuktiQris" class="hint" style="font-size:.72rem;color:#666;margin-top:5px">Belum ada foto dipilih.</div>
                                </div>
                            </div>
                        </div>
                        <div id="qrisLightbox" style="display:none;position:fixed;inset:0;z-index:6000;background:rgba(0,0,0,.78);align-items:center;justify-content:center;padding:20px" role="dialog" aria-modal="true" aria-label="Kode QRIS">
                            <div style="position:relative;max-width:min(92vw,520px);width:100%;background:#fff;border-radius:16px;padding:18px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.35)">
                                <button type="button" id="tutupQrisLightbox" aria-label="Tutup" style="position:absolute;top:8px;right:8px;width:34px;height:34px;border:1px solid #E5E7EB;border-radius:50%;background:#fff;color:#111;font-size:1rem;font-weight:700;cursor:pointer">✕</button>
                                <img id="gambarQrisLightbox" src="" alt="Kode QRIS diperbesar" style="width:100%;max-height:72vh;object-fit:contain">
                                <div style="font-size:.78rem;color:#666;margin-top:8px">Klik di luar gambar atau tekan Esc untuk menutup.</div>
                            </div>
                        </div>
                    @endif
                    @if($pengaturanTransaksi->tunai_aktif)
                        <div class="hint" style="font-size:.75rem;color:#666;margin-top:.4rem">Cash / tunai dibayarkan di kasir saat pengambilan atau pengiriman.</div>
                    @endif
                    @if($pengaturanTransaksi->tunai_aktif && $pengaturanTransaksi->tunai_syarat)
                        <div class="hint" style="font-size:.75rem;color:#666;margin-top:.4rem">Tunai: {{ $pengaturanTransaksi->tunai_syarat }}</div>
                    @endif
                </div>

                <div class="order-form-group">
                    <label>Nomor WhatsApp <span style="color:#DC2626">*</span></label>
                    <input type="tel" id="orderWa" class="order-input" placeholder="Contoh: 0812-3456-7890" required>
                    <div class="hint" style="font-size:.75rem;color:#666;margin-top:.4rem">Dipakai untuk verifikasi pembayaran QRIS dan mengecek status pesanan.</div>
                </div>

                <div class="order-form-group">
                    <label>Catatan Tambahan (Opsional)</label>
                    <textarea id="orderCatatan" class="order-input" rows="2" placeholder="Contoh: Es manis sedikit, sambal dipisah..."></textarea>
                </div>

                <div id="orderStatusMsg" style="display:none;margin-top:0.75rem;padding:0.75rem;border-radius:8px;font-size:0.83rem;font-weight:600;"></div>
            </div>
        </div>

        <div class="modal-footer" id="orderFormFooter">
            <button type="button" class="btn-order-system" id="btnOrderSystem" onclick="kirimPesananSistem()">
                <span>📩</span> Simpan ke Kasir
            </button>
            <button type="button" class="btn-order-clear" onclick="kosongkanPesanan()" title="Kosongkan Keranjang">
                🗑️
            </button>
        </div>

        <div id="orderSuccessPanel" class="order-success-panel" style="display:none">
            <div class="order-success-icon">✅</div>
            <h4 class="order-success-title">Pesanan berhasil dikirim</h4>
            <p class="order-success-desc">Nomor pesanan Anda: <strong id="orderSuccessNomor">-</strong></p>
            <p class="order-success-note" id="orderSuccessNote"></p>
            <div class="order-success-actions">
                <a id="btnCekStatusPesanan" class="btn-order-system" href="#" target="_blank" rel="noopener">🔎 Cek Status Pesanan</a>
                <button type="button" class="btn-order-secondary" onclick="tutupModalPesanan()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmDialog" class="confirm-overlay">
    <div class="confirm-box">
        <div class="confirm-icon-wrap">
            <span>🗑️</span>
        </div>
        <h4 class="confirm-title">Kosongkan Keranjang?</h4>
        <p class="confirm-desc">Semua daftar menu yang sudah Anda pilih akan dihapus dari pesanan.</p>
        <div class="confirm-actions">
            <button type="button" class="btn-confirm-cancel" onclick="tutupConfirmDialog()">Batal</button>
            <button type="button" class="btn-confirm-ok" onclick="eksekusiKosongkanPesanan()">Ya, Kosongkan</button>
        </div>
    </div>
</div>

<script>
// =============================================
//  SISTEM KERANJANG & PEMESANAN MENU
// =============================================
let keranjang = {};

function formatRupiah(angka) {
    return 'Rp' + Number(angka).toLocaleString('id-ID');
}

function tambahPesanan(id, nama, harga, kategori, btnEl) {
    if (!keranjang[id]) {
        keranjang[id] = { id, nama, harga, kategori, qty: 1 };
    } else {
        keranjang[id].qty++;
    }

    // Feedback tombol
    if (btnEl) {
        const origText = btnEl.innerHTML;
        btnEl.classList.add('added');
        btnEl.innerHTML = '✓ ' + keranjang[id].qty + 'x';
        setTimeout(() => {
            btnEl.classList.remove('added');
            btnEl.innerHTML = '+ Pesan';
        }, 1000);
    }

    renderKeranjang();
    showToast('✓ ' + nama + ' dimasukkan ke daftar pesanan!');
}

function ubahQty(id, delta) {
    if (!keranjang[id]) return;
    keranjang[id].qty += delta;
    if (keranjang[id].qty <= 0) {
        delete keranjang[id];
    }
    renderKeranjang();
}

function hapusItem(id) {
    if (keranjang[id]) {
        delete keranjang[id];
        renderKeranjang();
    }
}

function kosongkanPesanan() {
    if (Object.keys(keranjang).length === 0) return;
    document.getElementById('confirmDialog').classList.add('open');
}

function tutupConfirmDialog() {
    document.getElementById('confirmDialog').classList.remove('open');
}

function eksekusiKosongkanPesanan() {
    tutupConfirmDialog();
    keranjang = {};
    renderKeranjang();
    tutupModalPesanan();
    showToast('🗑️ Keranjang pesanan telah dikosongkan.');
}

function hitungTotal() {
    let totalQty = 0;
    let totalPrice = 0;
    for (const key in keranjang) {
        totalQty += keranjang[key].qty;
        totalPrice += (keranjang[key].harga * keranjang[key].qty);
    }
    return { totalQty, totalPrice };
}

function renderKeranjang() {
    const { totalQty, totalPrice } = hitungTotal();
    const cartBar = document.getElementById('cartBar');
    const badgeCount = document.getElementById('cartBadgeCount');
    const itemCount = document.getElementById('cartItemCount');
    const totalPriceEl = document.getElementById('cartTotalPrice');

    if (totalQty > 0) {
        cartBar.style.display = 'flex';
        badgeCount.textContent = totalQty;
        itemCount.textContent = totalQty + ' Menu Dipilih';
        totalPriceEl.textContent = formatRupiah(totalPrice);
    } else {
        cartBar.style.display = 'none';
    }

    // Render di dalam Modal
    const container = document.getElementById('orderItemsContainer');
    const modalTotalItems = document.getElementById('modalTotalItems');
    const modalTotalPrice = document.getElementById('modalTotalPrice');
    const qrisTotal = document.getElementById('qrisTotal');
    const btnOrderSystem = document.getElementById('btnOrderSystem');

    modalTotalItems.textContent = totalQty + ' porsi';
    modalTotalPrice.textContent = formatRupiah(totalPrice);
    if (qrisTotal) qrisTotal.textContent = formatRupiah(totalPrice);

    if (totalQty === 0) {
        container.innerHTML = '<div class="order-empty-state">🍽️ Belum ada menu yang dipilih.<br><span style="font-size:0.8rem;color:#888;">Silakan klik "+ Pesan" pada menu makanan atau minuman.</span></div>';
        btnOrderSystem.disabled = true;
        return;
    }

    btnOrderSystem.disabled = false;

    let html = '';
    for (const id in keranjang) {
        const item = keranjang[id];
        const subtotal = item.harga * item.qty;
        html += `
            <div class="order-item-row">
                <div class="order-item-info">
                    <div class="order-item-name">${item.nama}</div>
                    <div class="order-item-price">${formatRupiah(item.harga)}</div>
                </div>
                <div class="order-qty-control">
                    <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, -1)">−</button>
                    <span class="order-qty-val">${item.qty}</span>
                    <button type="button" class="btn-qty" onclick="ubahQty(${item.id}, 1)">+</button>
                </div>
                <div class="order-item-subtotal">${formatRupiah(subtotal)}</div>
                <button type="button" class="btn-del-item" onclick="hapusItem(${item.id})" title="Hapus">✕</button>
            </div>
        `;
    }
    container.innerHTML = html;
}

function resetModalPesanan() {
    const body = document.querySelector('#orderModal .modal-body');
    const footer = document.getElementById('orderFormFooter');
    const panel = document.getElementById('orderSuccessPanel');
    const headerClose = document.getElementById('btnCloseOrderModalHeader');
    const title = document.getElementById('orderModalTitle');
    const nomor = document.getElementById('orderSuccessNomor');
    const note = document.getElementById('orderSuccessNote');
    const cekStatus = document.getElementById('btnCekStatusPesanan');
    const btnOrder = document.getElementById('btnOrderSystem');
    const msg = document.getElementById('orderStatusMsg');

    if (btnOrder) {
        btnOrder.disabled = false;
        btnOrder.innerHTML = '<span>📩</span> Simpan ke Kasir';
    }
    if (msg) msg.style.display = 'none';
    if (body) body.style.display = '';
    if (footer) footer.style.display = '';
    if (panel) panel.style.display = 'none';
    if (headerClose) headerClose.style.display = '';
    if (title) title.innerHTML = '<span>🛒</span> Pesanan Makanan &amp; Minuman';
    if (nomor) nomor.textContent = '-';
    if (note) note.textContent = '';
    if (cekStatus) cekStatus.href = '#';
}

function bukaModalPesanan() {
    resetModalPesanan();
    renderKeranjang();
    const modal = document.getElementById('orderModal');
    modal.classList.add('open');
    document.getElementById('orderStatusMsg').style.display = 'none';
    const err = document.getElementById('orderNamaError');
    if (err) err.style.display = 'none';
    const namaInput = document.getElementById('orderNama');
    if (namaInput) namaInput.classList.remove('is-invalid');
}

function tutupModalPesanan() {
    const modal = document.getElementById('orderModal');
    modal.classList.remove('open');
    window.setTimeout(() => {
        if (!modal.classList.contains('open')) resetModalPesanan();
    }, 260);
}

function pilihTipePemesanan(type) {
    document.getElementById('orderTypeValue').value = type;
    const tabId = type === 'dine_in' ? 'typeTabDineIn' : type === 'takeaway' ? 'typeTabTakeaway' : 'typeTabKirim';
    ['typeTabDineIn', 'typeTabTakeaway', 'typeTabKirim'].forEach(id => document.getElementById(id)?.classList.remove('active'));
    document.getElementById(tabId)?.classList.add('active');

    const tableGroup = document.getElementById('tableGroup');
    const alamatGroup = document.getElementById('orderAlamatGroup');
    if (tableGroup) tableGroup.style.display = type === 'dine_in' ? 'block' : 'none';
    if (alamatGroup) alamatGroup.style.display = type === 'pengiriman' ? 'block' : 'none';
    document.getElementById('orderAlamat')?.toggleAttribute('required', type === 'pengiriman');
}

function buatTeksPesanan() {
    const namaInput = document.getElementById('orderNama');
    const namaError = document.getElementById('orderNamaError');
    const nama = namaInput ? namaInput.value.trim() : '';

    if (!nama) {
        if (namaInput) {
            namaInput.classList.add('is-invalid');
            namaInput.focus();
        }
        if (namaError) namaError.style.display = 'flex';
        showToast('⚠️ Silakan masukkan nama pemesan terlebih dahulu.');
        return null;
    }

    const { totalQty, totalPrice } = hitungTotal();
    if (totalQty === 0) {
        showToast('⚠️ Keranjang pesanan masih kosong.');
        return null;
    }

    const orderType = document.getElementById('orderTypeValue')?.value || 'dine_in';
    const isDineIn = orderType === 'dine_in';
    const meja = document.getElementById('orderMeja').value.trim();
    const wa = document.getElementById('orderWa').value.trim();
    const alamat = document.getElementById('orderAlamat')?.value.trim() || '';
    const metodePembayaran = document.getElementById('orderMetodeBayar')?.value || 'tunai';
    const buktiQris = document.getElementById('buktiQris')?.files?.[0] || null;
    const catatan = document.getElementById('orderCatatan').value.trim();

    if (metodePembayaran === 'qris' && !buktiQris) {
        showToast('⚠️ Foto bukti pembayaran QRIS wajib diunggah.');
        document.getElementById('btnPilihBuktiQris')?.focus();
        return null;
    }

    if (!wa) {
        showToast('⚠️ Nomor WhatsApp wajib diisi untuk verifikasi pesanan.');
        document.getElementById('orderWa')?.focus();
        return null;
    }
    if (orderType === 'pengiriman' && !alamat) {
        showToast('⚠️ Alamat pengiriman wajib diisi.');
        document.getElementById('orderAlamat')?.focus();
        return null;
    }

    let text = "*PESANAN ANGKRINGAN SEKOTA*\n";
    text += "──────────────────────────\n";
    text += "👤 *Nama:* " + nama + "\n";
    text += "🍽️ *Tipe:* " + (isDineIn ? 'Makan di Tempat (Dine-in)' : 'Bawa Pulang (Takeaway)') + "\n";
    if (isDineIn && meja) {
        text += "📍 *Meja:* " + meja + "\n";
    }
    if (orderType === 'pengiriman') {
        text += "🛵 *Alamat:* " + alamat + "\n";
    }
    text += "📱 *No. WA:* " + wa + "\n";
    text += "💳 *Bayar:* " + (metodePembayaran === 'qris' ? 'QRIS' : 'Tunai') + "\n";
    text += "──────────────────────────\n";
    text += "📋 *Daftar Menu:*\n";

    for (const id in keranjang) {
        const item = keranjang[id];
        const subtotal = item.harga * item.qty;
        text += `• ${item.nama} (${item.qty}x) = ${formatRupiah(subtotal)}\n`;
    }

    text += "──────────────────────────\n";
    text += "💰 *TOTAL TAGIHAN:* " + formatRupiah(totalPrice) + "\n";
    if (catatan) {
        text += "📝 *Catatan:* " + catatan + "\n";
    }
    text += "──────────────────────────\n";
    text += "Mohon segera diproses ya, terima kasih!";

    const items = Object.keys(keranjang).map(id => ({
        id: Number(id),
        qty: keranjang[id].qty
    }));

    return {
        nama,
        wa,
        text,
        totalPrice,
        items,
        metodePembayaran,
        buktiQris,
        metodePengambilan: orderType === 'pengiriman' ? 'pengiriman' : 'ambil_di_toko',
        alamat
    };
}

(function initQrisCheckout() {
    const select = document.getElementById('orderMetodeBayar');
    const panel = document.getElementById('qrisPanel');
    const fileInput = document.getElementById('buktiQris');
    const pickButton = document.getElementById('btnPilihBuktiQris');
    const fileName = document.getElementById('namaBuktiQris');
    const qrisImage = document.getElementById('gambarQrisMenu');
    const lightbox = document.getElementById('qrisLightbox');
    const lightboxImage = document.getElementById('gambarQrisLightbox');
    const closeLightbox = document.getElementById('tutupQrisLightbox');
    if (!select || !panel || !fileInput || !pickButton) return;

    if (qrisImage && lightbox && lightboxImage && closeLightbox) {
        const tutupPreview = () => {
            lightbox.style.display = 'none';
            lightboxImage.src = '';
        };

        qrisImage.addEventListener('click', () => {
            lightboxImage.src = qrisImage.src;
            lightbox.style.display = 'flex';
        });
        closeLightbox.addEventListener('click', tutupPreview);
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) tutupPreview();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') tutupPreview();
        });
    }

    function togglePanel() {
        const aktif = select.value === 'qris';
        panel.style.display = aktif ? 'block' : 'none';
        fileInput.required = aktif;
    }

    select.addEventListener('change', togglePanel);
    pickButton.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', () => {
        fileName.textContent = fileInput.files?.length ? fileInput.files[0].name : 'Belum ada foto dipilih.';
    });
    togglePanel();
})();

function kirimPesananSistem() {
    const data = buatTeksPesanan();
    if (!data) return;

    const btn = document.getElementById('btnOrderSystem');
    const msgEl = document.getElementById('orderStatusMsg');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Mengirim...';
    msgEl.style.display = 'none';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('nama_pengirim', data.nama);
    formData.append('nomor_wa', data.wa);
    formData.append('catatan', data.text);
    formData.append('items', JSON.stringify(data.items));
    formData.append('metode_pembayaran', data.metodePembayaran);
    if (data.buktiQris) formData.append('bukti_pembayaran', data.buktiQris);
    formData.append('metode_pengambilan', data.metodePengambilan);
    formData.append('alamat_pengiriman', data.alamat);

    fetch('{{ route("pesanan.store") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async res => {
        const body = await res.json().catch(() => ({}));
        if (!res.ok || !body.success) {
            const errors = body.errors ? Object.values(body.errors).flat().join(' ') : null;
            throw new Error(errors || body.message || 'Terjadi kendala saat mengirim ke server.');
        }
        return body;
    })
    .then(res => {
        const isQris = res.metode_pembayaran === 'qris';
        const info = isQris
            ? 'Bukti pembayaran QRIS diterima. Pegawai sedang mengecek bukti transaksi Anda.'
            : 'Pesanan tunai sudah masuk. Silakan bayar di kasir saat pengambilan atau pengiriman.';
        const body = document.querySelector('#orderModal .modal-body');
        const footer = document.getElementById('orderFormFooter');
        const panel = document.getElementById('orderSuccessPanel');
        const headerClose = document.getElementById('btnCloseOrderModalHeader');
        const title = document.getElementById('orderModalTitle');

        if (body) body.style.display = 'none';
        if (footer) footer.style.display = 'none';
        if (headerClose) headerClose.style.display = 'none';
        if (title) title.innerHTML = '<span>✅</span> Pesanan Berhasil';
        document.getElementById('orderSuccessNomor').textContent = res.nomor_transaksi;
        document.getElementById('orderSuccessNote').textContent = info;
        document.getElementById('btnCekStatusPesanan').href = res.cek_url;
        if (panel) panel.style.display = 'block';

        keranjang = {};
        renderKeranjang();
        showToast('📦 Pesanan ' + res.nomor_transaksi + ' berhasil dibuat.');
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = origText;
        msgEl.style.display = 'block';
        msgEl.style.background = '#FEE2E2';
        msgEl.style.color = '#991B1B';
        msgEl.textContent = '⚠️ ' + (err.message || 'Terjadi kendala saat mengirim ke server. Silakan coba lagi.');
    });
}

function showToast(msg) {
    const existing = document.querySelector('.toast-notif');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast-notif';
    toast.innerHTML = msg;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2200);
}

document.getElementById('orderModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'orderModal') tutupModalPesanan();
});
document.getElementById('confirmDialog')?.addEventListener('click', (e) => {
    if (e.target.id === 'confirmDialog') tutupConfirmDialog();
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        tutupModalPesanan();
        tutupConfirmDialog();
    }
});
document.getElementById('orderNama')?.addEventListener('input', function() {
    this.classList.remove('is-invalid');
    const err = document.getElementById('orderNamaError');
    if (err) err.style.display = 'none';
});
</script>

<script>
// =============================================
//  PROMO BANNER SLIDER (SEKOTA BANNER)
// =============================================
let currentBannerIdx = 0;
let bannerTimer = null;

function showBannerSlide(idx) {
    const slides = document.querySelectorAll('.promo-banner-slide');
    const dots   = document.querySelectorAll('.promo-banner-dots .banner-dot');
    if (!slides.length) return;

    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));

    currentBannerIdx = (idx + slides.length) % slides.length;
    slides[currentBannerIdx].classList.add('active');
    if (dots[currentBannerIdx]) {
        dots[currentBannerIdx].classList.add('active');
    }
}

function nextBannerSlide() {
    showBannerSlide(currentBannerIdx + 1);
}

function prevBannerSlide() {
    showBannerSlide(currentBannerIdx - 1);
}

window.goToBannerSlide = function(idx) {
    showBannerSlide(idx);
    resetBannerTimer();
};

function resetBannerTimer() {
    clearInterval(bannerTimer);
    const slides = document.querySelectorAll('.promo-banner-slide');
    if (slides.length > 1) {
        bannerTimer = setInterval(nextBannerSlide, 5000);
    }
}

(function initPromoBanner() {
    const wrap = document.getElementById('promoBannerWrap');
    const prev = document.getElementById('bannerPrevBtn');
    const next = document.getElementById('bannerNextBtn');
    if (!wrap) return;

    if (prev) {
        prev.addEventListener('click', (e) => {
            e.stopPropagation();
            prevBannerSlide();
            resetBannerTimer();
        });
    }
    if (next) {
        next.addEventListener('click', (e) => {
            e.stopPropagation();
            nextBannerSlide();
            resetBannerTimer();
        });
    }

    // Pause on hover
    wrap.addEventListener('mouseenter', () => clearInterval(bannerTimer));
    wrap.addEventListener('mouseleave', () => resetBannerTimer());

    // Swipe touch support
    let touchStartX = 0;
    let touchEndX = 0;
    wrap.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        clearInterval(bannerTimer);
    }, { passive: true });
    wrap.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchEndX < touchStartX - 50) {
            nextBannerSlide();
        } else if (touchEndX > touchStartX + 50) {
            prevBannerSlide();
        }
        resetBannerTimer();
    }, { passive: true });

    resetBannerTimer();
})();

// =============================================
//  CAROUSEL ULASAN PENGUNJUNG
// =============================================
(function initReviewsCarousel() {
    const track = document.getElementById('rcTrack');
    const prev = document.getElementById('rcPrev');
    const next = document.getElementById('rcNext');
    if (!track) return;

    const scrollAmount = 320;
    if (prev) {
        prev.addEventListener('click', () => {
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    }
    if (next) {
        next.addEventListener('click', () => {
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }
})();

// =============================================
//  PAGE NAVIGATION SYSTEM
// =============================================
// HERO SLIDESHOW
// =============================================
(function() {
    const slides = document.querySelectorAll('#heroSlideshow .hero-slide');
    const dots   = document.querySelectorAll('#heroDots .hero-dot');
    if (!slides.length) return;

    let current = 0;
    let timer = null;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function heroGoToSlide(idx) {
        slides[current].classList.remove('active');
        if (dots[current]) {
            dots[current].classList.remove('active');
            dots[current].removeAttribute('aria-current');
        }
        current = (idx + slides.length) % slides.length;
        slides[current].classList.add('active');
        if (dots[current]) {
            dots[current].classList.add('active');
            dots[current].setAttribute('aria-current', 'true');
        }
    }

    function startAuto() {
        if (reduceMotion || slides.length < 2) return;
        clearInterval(timer);
        timer = setInterval(() => heroGoToSlide(current + 1), 5500);
    }

    window.heroGoTo = function(idx) {
        clearInterval(timer);
        heroGoToSlide(idx);
        startAuto();
    };

    startAuto();
})();

// =============================================

let currentPage = 'beranda';
let busy = false;
const PAGE_ORDER = ['beranda', 'menu', 'tentang', 'kontak'];

function goTo(pageId) {
    if (pageId === currentPage || busy) return;

    const fromIdx = PAGE_ORDER.indexOf(currentPage);
    const toIdx   = PAGE_ORDER.indexOf(pageId);
    if (toIdx === -1) return;

    busy = true;

    const fromEl    = document.getElementById('page-' + currentPage);
    const toEl      = document.getElementById('page-' + pageId);
    const isForward = toIdx > fromIdx;

    // 1. Siapkan posisi awal toEl tanpa transisi
    toEl.classList.add('no-anim');
    toEl.classList.remove('active', 'exit-left', 'exit-right', 'enter-left', 'enter-right');
    toEl.classList.add(isForward ? 'enter-right' : 'enter-left');
    toEl.scrollTop = 0; // reset scroll ke atas

    // Force reflow agar posisi awal terdaftar di browser
    void toEl.offsetWidth;

    // 2. Aktifkan kembali animasi dan luncurkan transisi
    toEl.classList.remove('no-anim');

    fromEl.classList.remove('active', 'enter-left', 'enter-right');
    fromEl.classList.add(isForward ? 'exit-left' : 'exit-right');

    requestAnimationFrame(() => {
        toEl.classList.remove('enter-right', 'enter-left');
        toEl.classList.add('active');
    });

    currentPage = pageId;
    updateNav(pageId);

    // Jalankan efek ketikan jika membuka halaman Tentang Kami
    if (pageId === 'tentang') {
        setTimeout(startAboutTypewriter, 350);
    }

    // 3. Bersihkan state setelah animasi selesai
    setTimeout(() => {
        fromEl.classList.remove('exit-left', 'exit-right', 'active');
        fromEl.classList.add('no-anim');
        void fromEl.offsetWidth;
        fromEl.classList.remove('no-anim');
        busy = false;
    }, 500);
}

function updateNav(pageId) {
    const idx = PAGE_ORDER.indexOf(pageId);

    // Desktop nav
    document.querySelectorAll('#navLinks a').forEach((a, i) => {
        a.classList.toggle('active', i === idx);
    });
    // Mobile nav
    document.querySelectorAll('#mobileNavLinks a').forEach((a, i) => {
        a.classList.toggle('active', i === idx);
    });
}

// =============================================
//  HAMBURGER
// =============================================
const hamburgerBtn = document.getElementById('hamburgerBtn');
const mobileMenu   = document.getElementById('mobileMenu');

function closeMob() {
    mobileMenu?.classList.remove('open');
    hamburgerBtn?.setAttribute('aria-expanded', 'false');
}

hamburgerBtn?.addEventListener('click', () => {
    const open = !mobileMenu.classList.contains('open');
    mobileMenu.classList.toggle('open', open);
    hamburgerBtn.setAttribute('aria-expanded', String(open));
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileMenu?.classList.contains('open')) {
        closeMob();
        hamburgerBtn?.focus();
    }
});

// =============================================
//  FILTER & SEARCH MENU
// =============================================
let currentCategoryFilter = 'semua';

function pilihKategori(btn, kat) {
    document.querySelectorAll('#page-menu .filter-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    currentCategoryFilter = kat;
    applyMenuFilters();
}

// Alias untuk backward compatibility
function filterMenu(btn, kat) {
    pilihKategori(btn, kat);
}

function handleMenuSearch(val) {
    const clearBtn = document.getElementById('btnSearchClear');
    if (clearBtn) {
        clearBtn.style.display = val.trim().length > 0 ? 'flex' : 'none';
    }
    applyMenuFilters();
}

function clearMenuSearch() {
    const input = document.getElementById('menuSearchInput');
    if (input) {
        input.value = '';
        input.focus();
    }
    const clearBtn = document.getElementById('btnSearchClear');
    if (clearBtn) clearBtn.style.display = 'none';
    applyMenuFilters();
}

function resetMenuFilters() {
    clearMenuSearch();
    const allBtn = document.querySelector('#page-menu .filter-btn');
    pilihKategori(allBtn, 'semua');
}

function applyMenuFilters() {
    const searchInput = document.getElementById('menuSearchInput');
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const kat = currentCategoryFilter;

    let totalVisible = 0;
    const catBlocks = document.querySelectorAll('#page-menu .menu-cat-block');

    catBlocks.forEach(block => {
        const blockKat = block.dataset.kat;
        const matchesCategory = (kat === 'semua' || blockKat === kat);

        let visibleInBlock = 0;
        const cards = block.querySelectorAll('.product-card');

        cards.forEach(card => {
            const nama = (card.dataset.nama || '').toLowerCase();
            const matchesQuery = !query || nama.includes(query);

            if (matchesCategory && matchesQuery) {
                card.style.display = '';
                visibleInBlock++;
                totalVisible++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleInBlock > 0 && matchesCategory) {
            block.style.display = '';
        } else {
            block.style.display = 'none';
        }
    });

    const noteEl = document.getElementById('searchResultNote');
    if (noteEl) {
        if (query.length > 0) {
            noteEl.style.display = 'block';
            noteEl.textContent = `Menampilkan ${totalVisible} menu untuk "${query}"`;
        } else {
            noteEl.style.display = 'none';
        }
    }

    const emptyState = document.getElementById('menuNoResults');
    const queryEcho = document.getElementById('searchQueryEcho');
    if (emptyState) {
        if (totalVisible === 0) {
            emptyState.style.display = 'block';
            if (queryEcho) queryEcho.textContent = query || `Kategori ${kat}`;
        } else {
            emptyState.style.display = 'none';
        }
    }
}

// =============================================
//  EFEK KETIKAN (TYPEWRITER) - TENTANG KAMI
// =============================================
let typewriterTimer = null;
let isTyping = false;

const aboutParagraphs = [
    [
        { tag: 'strong', text: 'Angkringan Sekota' },
        { tag: null, text: ' adalah warung makan khas angkringan yang berlokasi di Tulungagung, Jawa Timur. Kami hadir untuk memberikan pengalaman makan yang nyaman, hangat, dan penuh keakraban bagi semua kalangan.' }
    ],
    [
        { tag: null, text: 'Dengan menu yang beragam dan harga yang sangat terjangkau, kami selalu berusaha menjaga kualitas cita rasa khas angkringan yang autentik. Nikmati berbagai sajian lezat dari nasi kucing, gorengan, minuman hangat, hingga menu spesial kami yang menjadi favorit pelanggan setia.' }
    ],
    [
        { tag: null, text: 'Buka setiap hari mulai pukul ' },
        { tag: 'strong', text: '17.00 – 23.00 WIB' },
        { tag: null, text: '. Kami selalu menyambut Anda dengan hangat dan ramah.' }
    ]
];

function startAboutTypewriter() {
    if (typewriterTimer) {
        clearTimeout(typewriterTimer);
        typewriterTimer = null;
    }

    const box = document.getElementById('aboutTypewriterBox');
    if (!box) return;

    box.innerHTML = '';
    isTyping = true;

    const cursor = document.createElement('span');
    cursor.className = 'type-cursor';
    cursor.id = 'typeCursor';

    let pIdx = 0;
    let segIdx = 0;
    let charIdx = 0;
    let currentP = null;
    let currentEl = null;

    function typeNextChar() {
        if (!isTyping) return;

        if (pIdx >= aboutParagraphs.length) {
            cursor.classList.add('done');
            setTimeout(() => { if (cursor.parentNode) cursor.remove(); }, 2500);
            isTyping = false;
            return;
        }

        if (!currentP) {
            currentP = document.createElement('p');
            box.appendChild(currentP);
            currentP.appendChild(cursor);
            segIdx = 0;
            charIdx = 0;
            currentEl = null;
        }

        const segments = aboutParagraphs[pIdx];
        if (segIdx >= segments.length) {
            pIdx++;
            currentP = null;
            currentEl = null;
            typewriterTimer = setTimeout(typeNextChar, 180);
            return;
        }

        const seg = segments[segIdx];
        if (!currentEl) {
            if (seg.tag) {
                currentEl = document.createElement(seg.tag);
                currentP.insertBefore(currentEl, cursor);
            } else {
                currentEl = document.createElement('span');
                currentP.insertBefore(currentEl, cursor);
            }
        }

        if (charIdx < seg.text.length) {
            currentEl.textContent += seg.text[charIdx];
            charIdx++;
            const char = seg.text[charIdx - 1];
            const speed = (char === '.' || char === ',') ? 65 : (12 + Math.random() * 8);
            typewriterTimer = setTimeout(typeNextChar, speed);
        } else {
            segIdx++;
            charIdx = 0;
            currentEl = null;
            typewriterTimer = setTimeout(typeNextChar, 20);
        }
    }

    typeNextChar();
}

function finishTypewriter() {
    if (!isTyping) return;
    if (typewriterTimer) clearTimeout(typewriterTimer);
    isTyping = false;

    const box = document.getElementById('aboutTypewriterBox');
    if (!box) return;

    box.innerHTML = `
        <p><strong>Angkringan Sekota</strong> adalah warung makan khas angkringan yang berlokasi di Tulungagung, Jawa Timur. Kami hadir untuk memberikan pengalaman makan yang nyaman, hangat, dan penuh keakraban bagi semua kalangan.</p>
        <p>Dengan menu yang beragam dan harga yang sangat terjangkau, kami selalu berusaha menjaga kualitas cita rasa khas angkringan yang autentik. Nikmati berbagai sajian lezat dari nasi kucing, gorengan, minuman hangat, hingga menu spesial kami yang menjadi favorit pelanggan setia.</p>
        <p>Buka setiap hari mulai pukul <strong>17.00 – 23.00 WIB</strong>. Kami selalu menyambut Anda dengan hangat dan ramah.</p>
    `;
}

// =============================================
//  LOKASI & ULASAN PENGUNJUNG
// =============================================
function salinAlamat(alamat) {
    const btn = document.getElementById('btnCopyAddress');
    const label = document.getElementById('copyBtnLabel');
    const icon = document.getElementById('copyIconSvg');

    function applySuccessState() {
        if (label) label.textContent = 'Tersalin!';
        if (icon) {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
        }
        setTimeout(() => {
            if (label) label.textContent = 'Salin Alamat';
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />';
            }
        }, 2200);
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(alamat).then(() => {
            showToast('Alamat Angkringan Sekota berhasil disalin!');
            applySuccessState();
        }).catch(() => {
            showToast('Alamat: ' + alamat);
        });
    } else {
        showToast('Alamat: ' + alamat);
    }
}

let selectedRating = 5;
const ratingLabels = {
    1: '1 Bintang: Perlu Peningkatan',
    2: '2 Bintang: Cukup',
    3: '3 Bintang: Lumayan Enak',
    4: '4 Bintang: Sangat Enak dan Nyaman',
    5: '5 Bintang: Sangat Puas!'
};

function renderStarsUI(activeVal, isHover = false) {
    const stars = document.querySelectorAll('#starPicker .star-btn');
    stars.forEach(btn => {
        const val = parseInt(btn.dataset.val, 10);
        if (isHover) {
            btn.classList.toggle('hover', val <= activeVal);
        } else {
            btn.classList.remove('hover');
            btn.classList.toggle('active', val <= activeVal);
        }
    });
    const label = document.getElementById('starRatingLabel');
    if (label && ratingLabels[activeVal]) {
        label.textContent = ratingLabels[activeVal];
    }
}

function setRating(val) {
    selectedRating = val;
    const input = document.getElementById('reviewRatingInput');
    if (input) input.value = val;
    renderStarsUI(val, false);
}

function previewRating(val) {
    renderStarsUI(val, true);
}

function resetStarPreview() {
    renderStarsUI(selectedRating, false);
}

function handleKirimUlasan(e) {
    e.preventDefault();
    const nameInput = document.getElementById('reviewCustomerName');
    const textInput = document.getElementById('reviewText');
    const ratingInput = document.getElementById('reviewRatingInput');
    const btnSubmit = document.getElementById('btnSubmitReview');

    const customer_name = nameInput ? nameInput.value.trim() : '';
    const review = textInput ? textInput.value.trim() : '';
    const rating = ratingInput ? parseInt(ratingInput.value, 10) : 5;

    if (!customer_name) {
        showToast('Mohon masukkan nama Anda.');
        if (nameInput) nameInput.focus();
        return;
    }
    if (!review) {
        showToast('Mohon tuliskan keterangan atau ulasan Anda.');
        if (textInput) textInput.focus();
        return;
    }

    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span>Mengirim ulasan...</span>';
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    fetch('{{ route("ulasan.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            customer_name: customer_name,
            rating: rating,
            review: review
        })
    })
    .then(res => res.json())
    .then(data => {
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span>Kirim Ulasan Saya</span>';
        }

        if (data.success) {
            showToast(data.message);

            // Sisipkan ulasan baru ke bagian atas feed
            const feedList = document.getElementById('reviewsFeedList');
            const emptyNotice = document.getElementById('emptyReviewNotice');
            if (emptyNotice) emptyNotice.remove();

            if (feedList && data.data) {
                const u = data.data;
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    starsHtml += i <= u.rating ? '★' : '☆';
                }

                const newCard = document.createElement('div');
                newCard.className = 'review-item-card bg-stone-50 border border-stone-200/90 rounded-xl p-4 transition-all duration-150 hover:bg-stone-100/70 is-new';
                newCard.innerHTML = `
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-[#C8102E] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                ${escapeHtml(u.inisial || 'AS')}
                            </div>
                            <div>
                                <div class="font-bold text-sm text-stone-900 leading-tight">${escapeHtml(u.customer_name)}</div>
                                <span class="text-[11px] text-stone-500">Pengunjung Baru</span>
                            </div>
                        </div>
                        <span class="text-xs text-stone-400 shrink-0">Baru saja</span>
                    </div>
                    <div class="text-amber-500 text-xs tracking-wider mb-1.5" aria-label="${u.rating} dari 5 bintang">${starsHtml}</div>
                    <p class="text-xs sm:text-sm text-stone-700 leading-relaxed">&ldquo;${escapeHtml(u.review)}&rdquo;</p>
                `;

                feedList.prepend(newCard);
                feedList.scrollTop = 0;
            }

            // Update counter
            const counter = document.getElementById('feedCountNum');
            if (counter) {
                const curr = parseInt(counter.textContent, 10) || 0;
                counter.textContent = curr + 1;
            }
            const summaryText = document.getElementById('summaryReviewCountText');
            if (summaryText) {
                const curr = parseInt(document.getElementById('feedCountNum')?.textContent || '1', 10);
                summaryText.textContent = `Berdasarkan ${curr} ulasan pengunjung`;
            }

            // Reset form
            if (nameInput) nameInput.value = '';
            if (textInput) textInput.value = '';
            setRating(5);
        } else {
            showToast(data.message || 'Terjadi kendala saat mengirim ulasan.');
        }
    })
    .catch(err => {
        console.error(err);
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span>Kirim Ulasan Saya</span>';
        }
        showToast('Gagal terhubung ke server. Silakan coba lagi.');
    });
}

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>
<script>
// =============================================
//  SPLASH PEMBUKA — tampil minimal 2,4 detik, tutup setelah halaman siap
// =============================================
(function () {
    var splash = document.getElementById('splash');
    if (!splash) return;
    var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
    var minMs = reduce ? 700 : 2400;
    var started = Date.now();
    var closed = false;

    function close() {
        if (closed) return;
        closed = true;
        splash.classList.add('splash--out');
        setTimeout(function () { splash.remove(); }, 800);
    }
    function closeWhenReady() {
        setTimeout(close, Math.max(0, minMs - (Date.now() - started)));
    }

    if (document.readyState === 'complete') closeWhenReady();
    else window.addEventListener('load', closeWhenReady);

    setTimeout(close, 6000);               // batas aman bila ada gambar yang lambat
    splash.addEventListener('click', close); // ketuk untuk melewati
})();
</script>
</body>
</html>