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
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
            width: 42px; height: 42px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255,255,255,0.3);
            overflow: hidden;
            flex-shrink: 0;
        }
        .navbar-logo-circle svg { width: 28px; height: 28px; }
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
            width: 200px; height: 200px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 6px solid rgba(255,255,255,0.3);
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .hero-logo-icon { font-size: 3.5rem; line-height: 1; margin-bottom: 0.5rem; }
        .hero-logo-text { font-size: 0.75rem; font-weight: 800; color: var(--red); text-transform: uppercase; letter-spacing: 0.15em; text-align: center; line-height: 1.3; }
        .hero-logo-sub  { font-size: 0.6rem; font-weight: 600; color: var(--red-dark); text-transform: uppercase; letter-spacing: 0.1em; }

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
        .product-card-img { width: 100%; aspect-ratio: 1; overflow: hidden; background: var(--cream); }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .product-card:hover .product-card-img img { transform: scale(1.08); }
        .product-card-body { padding: 0.9rem 1rem; }
        .product-card-name { font-size: 0.88rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.4rem; line-height: 1.3; }
        .product-card-price { display: inline-block; background: var(--red); color: var(--white); font-size: 0.76rem; font-weight: 700; padding: 0.22rem 0.8rem; border-radius: 20px; }

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
        }
        .footer-logo-circle span { font-size: 1.5rem; }
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
            <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="30" fill="#C8102E"/>
                <path d="M30 12L10 28h6v18h10V35h8v11h10V28h6L30 12z" fill="white"/>
            </svg>
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
                    <div class="hero-logo-icon">🏠</div>
                    <div class="hero-logo-text">ANGKRINGAN</div>
                    <div class="hero-logo-sub">SEKOTA</div>
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
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/menu/placeholder.jpg') }}"
                             alt="{{ $item->nama }}"
                             onerror="this.src='https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=400&q=80'">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
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
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/menu/placeholder.jpg') }}"
                             alt="{{ $item->nama }}"
                             onerror="this.src='https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=400&q=80'">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
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
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/menu/placeholder.jpg') }}"
                             alt="{{ $item->nama }}"
                             onerror="this.src='https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&q=80'">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
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
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : asset('images/menu/placeholder.jpg') }}"
                             alt="{{ $item->nama }}"
                             onerror="this.src='https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=400&q=80'">
                    </div>
                    <div class="product-card-body">
                        <div class="product-card-name">{{ $item->nama }}</div>
                        <span class="product-card-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
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
