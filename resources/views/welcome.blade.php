<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Angkringan Sekota Tulungagung</title>
    <meta name="description" content="Angkringan Sekota Tulungagung - Nikmati cita rasa khas angkringan dengan menu lezat dan harga terjangkau.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            --red:        #C8102E;
            --red-dark:   #9E0C23;
            --cream:      #FDF5F0;
            --cream-dark: #F5E8E0;
            --white:      #FFFFFF;
            --text-dark:  #1A1A1A;
            --text-gray:  #666666;
            --nav-h:      64px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow: hidden; /* pages scroll individually */
            -webkit-user-select: none;
            user-select: none;
        }

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
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
        }
        .navbar-logo-circle {
            width: 44px; height: 44px;
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
           =================================================== */
        #page-beranda { background: var(--red); }
        .hero {
            min-height: calc(100vh - var(--nav-h));
            background: var(--red);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%; left: -20%;
            width: 60%; height: 180%;
            background: radial-gradient(ellipse, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%; right: -10%;
            width: 50%; height: 100%;
            background: radial-gradient(ellipse, rgba(0,0,0,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 4rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        .hero-content { flex: 1; }
        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            margin-bottom: 1.2rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .hero-subtitle {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.82);
            line-height: 1.65;
            margin-bottom: 2.2rem;
            max-width: 460px;
        }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-primary {
            background: var(--white);
            color: var(--red);
            padding: 0.75rem 1.75rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid var(--white);
            transition: all 0.25s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary:hover { background: transparent; color: var(--white); transform: translateY(-2px); }
        .btn-outline {
            background: transparent;
            color: var(--white);
            padding: 0.75rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid rgba(255,255,255,0.6);
            transition: all 0.25s;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.15); border-color: var(--white); transform: translateY(-2px); }
        .hero-logo-wrap { flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .hero-logo-circle {
            width: 230px; height: 230px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 6px solid rgba(255,255,255,0.35);
            box-shadow: 0 20px 60px rgba(0,0,0,0.28);
            animation: float 3s ease-in-out infinite;
            overflow: hidden;
            padding: 8px;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .hero-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }

        /* Produk terlaris strip (inside beranda page, cream bg) */
        .bestsellers {
            background: var(--white);
            padding: 4rem 2rem;
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
        .btn-order-wa {
            flex: 1.2;
            background: #25D366;
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
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
        }
        .btn-order-wa:hover {
            background: #1EBE5D;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
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
        .btn-order-system:disabled, .btn-order-wa:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
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

        /* ===================================================
           PAGE: MENU
           =================================================== */
        #page-menu { background: var(--cream); }
        .menu-content {
            max-width: 920px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }
        .menu-intro {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .menu-intro h3 { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.03em; }
        .menu-intro .city { font-size: 0.95rem; font-weight: 700; color: var(--red); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; }
        .menu-intro p { font-size: 0.84rem; color: var(--text-gray); line-height: 1.65; max-width: 480px; margin: 0 auto; }

        .filter-row { display: flex; justify-content: center; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .filter-btn {
            padding: 0.5rem 1.4rem;
            border-radius: 50px;
            border: 2px solid var(--red);
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.22s;
            background: var(--white);
            color: var(--red);
            font-family: 'Poppins', sans-serif;
        }
        .filter-btn:hover, .filter-btn.active { background: var(--red); color: var(--white); }

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
           PAGE: LOKASI & KONTAK
           =================================================== */
        #page-kontak { background: var(--cream); }
        .kontak-content-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }
        .kontak-subtitle {
            text-align: center;
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start; }
        .contact-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            border: 1px solid rgba(0,0,0,0.04);
            margin-bottom: 1.25rem;
        }
        .contact-card:last-child { margin-bottom: 0; }
        .contact-card h4 { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem; }
        .contact-card h4 .ico { font-size: 1.2rem; }
        .contact-wa-num {
            background: var(--cream);
            border: 1px solid var(--cream-dark);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.9rem;
            font-family: monospace;
            letter-spacing: 0.05em;
        }
        .btn-wa {
            background: #25D366;
            color: var(--white);
            padding: 0.65rem 1.4rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.25s;
        }
        .btn-wa:hover { background: #1ebe5d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,0.35); }
        .info-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .info-item { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.88rem; color: var(--text-gray); }
        .info-item .ico { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
        .map-box {
            background: var(--cream-dark);
            border-radius: 12px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1rem;
            border: 2px dashed rgba(200,16,46,0.2);
            overflow: hidden;
        }
        .map-box iframe { width: 100%; height: 100%; border: none; border-radius: 12px; }

        /* ===================================================
           FOOTER (shared in every page)
           =================================================== */
        .footer {
            background: var(--red);
            padding: 2.5rem 2rem 1.5rem;
            color: rgba(255,255,255,0.85);
        }
        .footer-container { max-width: 1100px; margin: 0 auto; }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 2.5rem;
            margin-bottom: 2rem;
        }
        .footer-brand { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 1rem; }
        .footer-logo-circle {
            width: 48px; height: 48px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            padding: 2px;
        }
        .footer-logo-circle img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; display: block; }
        .footer-brand-text { font-size: 0.85rem; font-weight: 700; color: var(--white); text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.3; }
        .footer-brand-sub { font-size: 0.7rem; font-weight: 400; color: rgba(255,255,255,0.7); }
        .footer-desc { font-size: 0.82rem; line-height: 1.7; color: rgba(255,255,255,0.75); }
        .footer-col h5 { font-size: 0.9rem; font-weight: 700; color: var(--white); margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.08em; }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
        .footer-links a { color: rgba(255,255,255,0.75); text-decoration: none; font-size: 0.83rem; transition: color 0.2s; display: flex; align-items: center; gap: 0.4rem; cursor: pointer; }
        .footer-links a:hover { color: var(--white); }
        .footer-contact-item { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.82rem; color: rgba(255,255,255,0.75); margin-bottom: 0.6rem; }
        .footer-contact-item .ico { font-size: 0.95rem; flex-shrink: 0; margin-top: 1px; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            padding-top: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .footer-bottom p { font-size: 0.78rem; color: rgba(255,255,255,0.65); }
        .footer-socials { display: flex; gap: 0.6rem; }
        .social-btn {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
        }
        .social-btn:hover { background: rgba(255,255,255,0.3); transform: translateY(-2px); }

        /* ===================================================
           RESPONSIVE
           =================================================== */
        @media (max-width: 900px) {
            .navbar-nav { display: none; }
            .hamburger  { display: block; }
            .hero-logo-circle { width: 150px; height: 150px; }
            .about-grid { grid-template-columns: 1fr; gap: 2rem; }
            .cards-grid { grid-template-columns: repeat(2, 1fr); }
            .about-stats { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-grid > *:first-child { grid-column: 1 / -1; }
        }
        @media (max-width: 600px) {
            .hero-container { flex-direction: column; text-align: center; padding: 2.5rem 1.25rem; }
            .hero-subtitle { max-width: 100%; }
            .hero-actions { justify-content: center; }
            .hero-logo-circle { width: 130px; height: 130px; }
            .cards-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .contact-grid { grid-template-columns: 1fr; }
            .about-stats { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .page-banner-text h2 { font-size: 1.5rem; }
            .page-banner-logo { width: 56px; height: 56px; }
            .bestsellers { padding: 2.5rem 1.25rem; }
            .menu-content, .about-content-wrap, .kontak-content-wrap { padding: 1.5rem 1rem 3rem; }
        }
    </style>
</head>
<body>

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
    <button class="hamburger" id="hamburgerBtn" aria-label="Buka menu">
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

    {{-- Hero --}}
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Angkringan<br>Sekota<br>Tulungagung</h1>
                <p class="hero-subtitle">
                    Cari tempat makan yang santai sambil menikmati suasana malam yang nyaman?
                    Angkringan Sekota hadir dengan menu lezat dan harga terjangkau di Tulungagung.
                </p>
                <div class="hero-actions">
                    <button class="btn-primary" onclick="goTo('menu')">Jelajahi Menu →</button>
                    <button class="btn-outline" onclick="goTo('tentang')">Tentang Kami</button>
                </div>
            </div>
            <div class="hero-logo-wrap">
                <div class="hero-logo-circle">
                    <img src="{{ $profil->logo_url ?? asset('images/logo.png') }}" alt="{{ $profil->nama_usaha ?? 'Angkringan Sekota' }}">
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Terlaris --}}
    <section class="bestsellers">
        <div class="bestsellers-container">
            <div class="section-sublabel">Menu Paling Diburu</div>
            <div class="section-title-red">PRODUK TERLARIS</div>

            <div class="cards-grid">
                @forelse($produk->take(6) as $item)
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)">
                                + Pesan
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;color:var(--text-gray);padding:2rem">🍽️ Menu segera hadir.</div>
                @endforelse
            </div>

            <button class="btn-cta-full" onclick="goTo('menu')">Lihat Seluruh Daftar Menu Selengkapnya</button>
        </div>
    </section>

    {{-- Footer --}}
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
        {{-- Intro --}}
        <div class="menu-intro">
            <h3>Angkringan Sekota</h3>
            <div class="city">Tulungagung</div>
            <p>Dari obrolan santai sampai cerita panjang, semua terasa lebih seru ditemani hidangan favorit di Angkringan SEKOTA.</p>
        </div>

        {{-- Filter --}}
        <div class="filter-row">
            <button class="filter-btn active" onclick="filterMenu(this,'semua')">Semua</button>
            <button class="filter-btn" onclick="filterMenu(this,'makanan')">Makanan</button>
            <button class="filter-btn" onclick="filterMenu(this,'minuman')">Minuman</button>
            <button class="filter-btn" onclick="filterMenu(this,'lainnya')">Lainnya</button>
        </div>

        @php
            $pMakanan = $produk->filter(fn($p) => strtolower($p->kategori ?? '') === 'makanan');
            $pMinuman = $produk->filter(fn($p) => strtolower($p->kategori ?? '') === 'minuman');
            $pLainnya = $produk->filter(fn($p) => !in_array(strtolower($p->kategori ?? ''), ['makanan','minuman']));
        @endphp

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
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)">
                                + Pesan
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
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)">
                                + Pesan
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
                <div class="product-card">
                    <div class="product-card-img">
                        <img src="{{ $item->gambar_url }}"
                             alt="{{ $item->nama }}"
                             loading="lazy">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <div class="product-card-footer">
                            <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            <button type="button" class="btn-pesan-item" onclick="tambahPesanan({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->harga }}, '{{ $item->kategori }}', this)">
                                + Pesan
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
            <div class="about-text">
                <h3>Siapa Kami</h3>
                <p>
                    <strong>Angkringan Sekota</strong> adalah warung makan khas angkringan yang berlokasi di
                    Tulungagung, Jawa Timur. Kami hadir untuk memberikan pengalaman makan yang nyaman,
                    hangat, dan penuh keakraban bagi semua kalangan.
                </p>
                <p>
                    Dengan menu yang beragam dan harga yang sangat terjangkau, kami selalu berusaha
                    menjaga kualitas cita rasa khas angkringan yang autentik. Nikmati berbagai sajian
                    lezat dari nasi kucing, gorengan, minuman hangat, hingga menu spesial kami yang
                    menjadi favorit pelanggan setia.
                </p>
                <p>
                    Buka setiap hari mulai pukul <strong>17.00 – 23.00 WIB</strong>. Kami selalu menyambut
                    Anda dengan hangat dan ramah.
                </p>
                <button class="btn-red" onclick="goTo('menu')">Lihat Menu Kami →</button>
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
<div id="page-kontak" class="page">

    <div class="page-banner">
        <div class="page-banner-text">
            <h2>Lokasi &amp; Kontak</h2>
            <p>Temukan kami dan hubungi langsung via WhatsApp.</p>
        </div>
    </div>

    <div class="kontak-content-wrap">
        <p class="kontak-subtitle">Silakan pesan atau tanyakan sesuatu seputar angkringan kami.</p>

        <div class="contact-grid">
            <div>
                <div class="contact-card">
                    <h4><span class="ico">💬</span> WhatsApp</h4>
                    <div class="contact-wa-num">0812-3456-7890</div>
                    <a href="https://wa.me/6281358391267" target="_blank" class="btn-wa">
                        💬&nbsp;Chat WhatsApp
                    </a>
                </div>

                <div class="contact-card">
                    <h4><span class="ico">📍</span> Informasi</h4>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="ico">📍</span>
                            <span>Jl. Contoh No. 123, Tulungagung, Jawa Timur</span>
                        </div>
                        <div class="info-item">
                            <span class="ico">🕐</span>
                            <span>Senin – Minggu: 17.00 – 23.00 WIB</span>
                        </div>
                        <div class="info-item">
                            <span class="ico">📸</span>
                            <span>@angkringan.sekota</span>
                        </div>
                        <div class="info-item">
                            <span class="ico">📘</span>
                            <span>Angkringan Sekota Tulungagung</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="contact-card" style="height:100%">
                    <h4><span class="ico">🗺️</span> Lokasi Kami</h4>
                    <div class="map-box">
                        <span style="color:#aaa;font-size:0.9rem">📍 Peta Google Maps</span>
                    </div>
                    <p style="font-size:0.8rem;color:var(--text-gray);margin-top:0.75rem;line-height:1.6">
                        Temukan kami di Google Maps dengan kata kunci <strong>"Angkringan Sekota Tulungagung"</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('partials._footer')
</div>

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
            <div class="modal-title">
                <span>🛒</span> Pesanan Makanan &amp; Minuman
            </div>
            <button type="button" class="btn-close-modal" onclick="tutupModalPesanan()" aria-label="Tutup">✕</button>
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

                <div class="order-form-group">
                    <label>Nomor WhatsApp (Opsional)</label>
                    <input type="tel" id="orderWa" class="order-input" placeholder="Contoh: 0812-3456-7890">
                </div>

                <div class="order-form-group">
                    <label>Catatan Tambahan (Opsional)</label>
                    <textarea id="orderCatatan" class="order-input" rows="2" placeholder="Contoh: Es manis sedikit, sambal dipisah..."></textarea>
                </div>

                <div id="orderStatusMsg" style="display:none;margin-top:0.75rem;padding:0.75rem;border-radius:8px;font-size:0.83rem;font-weight:600;"></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-order-wa" id="btnOrderWa" onclick="kirimPesananWhatsApp()">
                <span>💬</span> Pesan via WhatsApp
            </button>
            <button type="button" class="btn-order-system" id="btnOrderSystem" onclick="kirimPesananSistem()">
                <span>📩</span> Simpan ke Kasir
            </button>
            <button type="button" class="btn-order-clear" onclick="kosongkanPesanan()" title="Kosongkan Keranjang">
                🗑️
            </button>
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
const WA_TARGET = "{{ preg_replace('/[^0-9]/', '', $profil->whatsapp ?? '081234567890') }}";

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
    const btnOrderWa = document.getElementById('btnOrderWa');
    const btnOrderSystem = document.getElementById('btnOrderSystem');

    modalTotalItems.textContent = totalQty + ' porsi';
    modalTotalPrice.textContent = formatRupiah(totalPrice);

    if (totalQty === 0) {
        container.innerHTML = '<div class="order-empty-state">🍽️ Belum ada menu yang dipilih.<br><span style="font-size:0.8rem;color:#888;">Silakan klik "+ Pesan" pada menu makanan atau minuman.</span></div>';
        btnOrderWa.disabled = true;
        btnOrderSystem.disabled = true;
        return;
    }

    btnOrderWa.disabled = false;
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

function bukaModalPesanan() {
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
}

function pilihTipePemesanan(type) {
    document.getElementById('orderTypeValue').value = type;
    const tabDineIn = document.getElementById('typeTabDineIn');
    const tabTakeaway = document.getElementById('typeTabTakeaway');
    const tableGroup = document.getElementById('tableGroup');

    if (type === 'dine_in') {
        tabDineIn.classList.add('active');
        tabTakeaway.classList.remove('active');
        if (tableGroup) tableGroup.style.display = 'block';
    } else {
        tabTakeaway.classList.add('active');
        tabDineIn.classList.remove('active');
        if (tableGroup) tableGroup.style.display = 'none';
    }
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
    const catatan = document.getElementById('orderCatatan').value.trim();

    let text = "*PESANAN ANGKRINGAN SEKOTA*\n";
    text += "──────────────────────────\n";
    text += "👤 *Nama:* " + nama + "\n";
    text += "🍽️ *Tipe:* " + (isDineIn ? 'Makan di Tempat (Dine-in)' : 'Bawa Pulang (Takeaway)') + "\n";
    if (isDineIn && meja) {
        text += "📍 *Meja:* " + meja + "\n";
    }
    if (wa) {
        text += "📱 *No. WA:* " + wa + "\n";
    }
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

    return { nama, wa, text, totalPrice };
}

function kirimPesananWhatsApp() {
    const data = buatTeksPesanan();
    if (!data) return;

    let targetWa = WA_TARGET;
    if (targetWa.startsWith('0')) {
        targetWa = '62' + targetWa.substring(1);
    }
    if (!targetWa) targetWa = '6281234567890';

    const url = 'https://api.whatsapp.com/send?phone=' + targetWa + '&text=' + encodeURIComponent(data.text);
    window.open(url, '_blank');
}

function kirimPesananSistem() {
    const data = buatTeksPesanan();
    if (!data) return;

    const btn = document.getElementById('btnOrderSystem');
    const msgEl = document.getElementById('orderStatusMsg');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Mengirim...';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('nama_pengirim', data.nama);
    formData.append('nomor_wa', data.wa || '-');
    formData.append('isi_pesan', data.text);

    fetch('{{ route("pesan.store") }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = origText;
        msgEl.style.display = 'block';
        msgEl.style.background = '#D1FAE5';
        msgEl.style.color = '#065F46';
        msgEl.innerHTML = '✅ Pesanan berhasil dicatat oleh sistem kasir/admin! Silakan tunggu pesanan Anda disiapkan.';

        setTimeout(() => {
            keranjang = {};
            renderKeranjang();
            tutupModalPesanan();
        }, 2200);
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = origText;
        msgEl.style.display = 'block';
        msgEl.style.background = '#FEE2E2';
        msgEl.style.color = '#991B1B';
        msgEl.innerHTML = 'Terjadi kendala saat mengirim ke server. Silakan gunakan tombol "Pesan via WhatsApp".';
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
//  PAGE NAVIGATION SYSTEM
// =============================================
const PAGE_ORDER = ['beranda', 'menu', 'tentang', 'kontak'];
let currentPage = 'beranda';
let busy = false;

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

hamburgerBtn.addEventListener('click', () => mobileMenu.classList.toggle('open'));

function closeMob() { mobileMenu.classList.remove('open'); }

// =============================================
//  FILTER MENU
// =============================================
function filterMenu(btn, kat) {
    document.querySelectorAll('#page-menu .filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('#page-menu .menu-cat-block').forEach(block => {
        block.style.display = (kat === 'semua' || block.dataset.kat === kat) ? '' : 'none';
    });
}
</script>
</body>
</html>
