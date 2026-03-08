<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LuckyDraw Pro - Premium Lottery & Draw System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue-primary: #1a56db;
            --blue-dark: #1e3a8a;
            --blue-light: #eff6ff;
            --accent: #f59e0b;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── NAV ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            height: 68px;
            display: flex;
            align-items: center;
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 36px; height: 36px;
            background: var(--blue-primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 18px;
        }
        .nav-logo-text {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }
        .nav-logo-text span { color: var(--blue-primary); }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
            margin: 0; padding: 0;
        }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--blue-primary); }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-ghost {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-ghost:hover { background: #f1f5f9; }
        .btn-primary {
            padding: 9px 22px;
            background: var(--blue-primary);
            color: #fff;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover { background: #1649c0; transform: translateY(-1px); }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            background: none;
            border: none;
        }
        .hamburger span {
            display: block;
            width: 24px; height: 2px;
            background: var(--text-dark);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 68px; left: 0; right: 0;
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
            z-index: 99;
            flex-direction: column;
            gap: 4px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--text-dark);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .mobile-menu a:hover { background: var(--blue-light); color: var(--blue-primary); }
        .mobile-menu .mobile-divider { height: 1px; background: var(--border); margin: 8px 0; }
        .mobile-menu .btn-primary-full {
            display: block;
            text-align: center;
            padding: 13px;
            background: var(--blue-primary);
            color: #fff;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            margin-top: 4px;
        }

        /* ── HERO ── */
        .hero {
            padding-top: 68px;
            background: linear-gradient(160deg, #f0f7ff 0%, #e8f0fe 40%, #faf5ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(26,86,219,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(245,158,11,0.07) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 24px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(26,86,219,0.08);
            border: 1px solid rgba(26,86,219,0.2);
            border-radius: 100px;
            color: var(--blue-primary);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .hero-badge::before { content: ''; width: 8px; height: 8px; background: var(--blue-primary); border-radius: 50%; }
        .hero h1 {
            font-size: clamp(40px, 5vw, 62px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--text-dark);
            margin: 0 0 20px;
        }
        .hero h1 span { color: var(--blue-primary); }
        .hero-sub {
            font-size: 17px;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 36px;
        }
        .hero-cta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            padding: 14px 28px;
            background: var(--blue-primary);
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(26,86,219,0.3);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(26,86,219,0.4); }
        .btn-hero-secondary {
            padding: 14px 28px;
            background: #fff;
            border: 1.5px solid var(--border);
            color: var(--text-dark);
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-hero-secondary:hover { border-color: var(--blue-primary); color: var(--blue-primary); }
        .hero-trust {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 36px;
            flex-wrap: wrap;
        }
        .trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }
        .trust-icon {
            width: 28px; height: 28px;
            background: var(--blue-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
        }

        /* Hero Card */
        .hero-card-wrap {
            position: relative;
        }
        .hero-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            padding: 28px;
            border: 1px solid var(--border);
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card-title { font-size: 14px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .live-dot {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #ef4444;
            font-weight: 600;
        }
        .live-dot::before {
            content: '';
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        .draw-card {
            background: var(--blue-light);
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(26,86,219,0.12);
            cursor: default;
            transition: box-shadow 0.2s;
        }
        .draw-card:hover { box-shadow: 0 4px 16px rgba(26,86,219,0.1); }
        .draw-card-icon {
            width: 44px; height: 44px;
            background: var(--blue-primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-right: 14px;
            flex-shrink: 0;
        }
        .draw-card-name { font-size: 14px; font-weight: 700; color: var(--text-dark); }
        .draw-card-prize { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .draw-card-end { font-size: 13px; font-weight: 700; color: var(--blue-primary); }
        .draw-card-soon { font-size: 13px; font-weight: 600; color: #9ca3af; }

        .card-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }
        .card-stat {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            text-align: center;
            border: 1px solid var(--border);
        }
        .card-stat-val { font-size: 20px; font-weight: 800; color: var(--text-dark); }
        .card-stat-label { font-size: 11px; color: var(--text-muted); margin-top: 2px; font-weight: 500; }

        /* Floating badges */
        .float-badge {
            position: absolute;
            background: #fff;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
        }
        .float-badge-1 { top: -20px; right: -10px; color: #16a34a; }
        .float-badge-2 { bottom: -16px; left: -10px; color: var(--blue-primary); }

        /* ── STATS ── */
        .stats-section {
            background: var(--blue-primary);
            padding: 48px 0;
        }
        .stats-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .stat-item { text-align: center; }
        .stat-val { font-size: 36px; font-weight: 800; color: #fff; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }

        /* ── HOW IT WORKS ── */
        .section { padding: 96px 0; }
        .section-inner { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        .section-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--blue-primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-dark);
            margin: 0 0 12px;
        }
        .section-sub { font-size: 16px; color: var(--text-muted); line-height: 1.6; max-width: 480px; }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header .section-sub { margin: 0 auto; }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }
        .step-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            border: 1px solid var(--border);
            position: relative;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .step-card:hover { box-shadow: 0 12px 40px rgba(0,0,0,0.08); transform: translateY(-4px); }
        .step-number {
            position: absolute;
            top: 24px; right: 24px;
            font-size: 13px;
            font-weight: 700;
            color: #d1d5db;
            letter-spacing: 0.5px;
        }
        .step-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
        }
        .step-icon-1 { background: #eff6ff; }
        .step-icon-2 { background: #fef3c7; }
        .step-icon-3 { background: #f0fdf4; }
        .step-title { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .step-desc { font-size: 14px; color: var(--text-muted); line-height: 1.65; }
        .step-connector {
            position: absolute;
            top: 52px; right: -16px;
            color: var(--blue-primary);
            font-size: 20px;
            z-index: 2;
        }

        /* ── DRAW COUNTDOWN ── */
        .draw-section {
            background: linear-gradient(135deg, #1a56db 0%, #1e3a8a 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        .draw-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .draw-section-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .draw-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(255,255,255,0.15);
            border-radius: 100px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .draw-title { font-size: clamp(28px, 4vw, 44px); font-weight: 800; color: #fff; margin-bottom: 8px; }
        .draw-subtitle { color: rgba(255,255,255,0.65); font-size: 15px; margin-bottom: 48px; }
        .countdown-row {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-bottom: 48px;
            flex-wrap: wrap;
        }
        .countdown-box {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 24px 28px;
            min-width: 100px;
            backdrop-filter: blur(8px);
        }
        .countdown-val { font-size: 48px; font-weight: 800; color: #fff; line-height: 1; }
        .countdown-label { font-size: 11px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1.5px; margin-top: 8px; }
        .draw-info-row {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .draw-info-item { text-align: center; }
        .draw-info-val { font-size: 28px; font-weight: 800; color: #fff; }
        .draw-info-label { font-size: 12px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
        .btn-draw {
            padding: 15px 36px;
            background: #fff;
            color: var(--blue-primary);
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-draw:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,0.2); }

        /* ── PRODUCTS ── */
        .bg-light { background: #f8fafc; }
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .view-all {
            font-size: 14px;
            font-weight: 600;
            color: var(--blue-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .view-all:hover { text-decoration: underline; }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .product-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .product-card:hover { box-shadow: 0 12px 36px rgba(0,0,0,0.09); transform: translateY(-4px); }
        .product-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: linear-gradient(135deg, #e0f2fe, #bfdbfe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        .product-body { padding: 18px; }
        .product-entries {
            display: inline-block;
            padding: 3px 10px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .product-name { font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 12px; }
        .product-price { font-size: 20px; font-weight: 800; color: var(--blue-primary); margin-bottom: 14px; }
        .btn-buy {
            display: block;
            text-align: center;
            padding: 10px;
            background: var(--blue-primary);
            color: #fff;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-buy:hover { background: #1649c0; }

        /* ── FAIR SYSTEM ── */
        .fair-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        .fair-features { display: flex; flex-direction: column; gap: 24px; }
        .fair-feature {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .fair-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .fair-icon-1 { background: #eff6ff; }
        .fair-icon-2 { background: #f0fdf4; }
        .fair-icon-3 { background: #fefce8; }
        .fair-feature-title { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
        .fair-feature-desc { font-size: 14px; color: var(--text-muted); line-height: 1.6; }
        .fair-card {
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        }
        .fair-card-title { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; text-transform: uppercase; }
        .fair-card-hash {
            font-family: monospace;
            font-size: 11px;
            color: var(--text-dark);
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 8px;
            word-break: break-all;
            border: 1px solid var(--border);
            margin-bottom: 16px;
        }
        .verify-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border: 1.5px solid var(--blue-primary);
            color: var(--blue-primary);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }
        .verify-btn:hover { background: var(--blue-light); }

        /* ── TESTIMONIALS ── */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .testimonial-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            border: 1px solid var(--border);
            transition: box-shadow 0.2s;
        }
        .testimonial-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.07); }
        .stars { color: #f59e0b; font-size: 15px; margin-bottom: 14px; }
        .testimonial-text { font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 20px; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; }
        .author-avatar {
            width: 40px; height: 40px;
            background: var(--blue-primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
        }
        .author-name { font-size: 14px; font-weight: 700; }
        .author-role { font-size: 12px; color: var(--text-muted); }

        /* ── FOOTER ── */
        footer {
            background: var(--text-dark);
            color: #fff;
            padding: 60px 0 28px;
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .footer-logo-icon {
            width: 34px; height: 34px;
            background: var(--blue-primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 800;
        }
        .footer-logo-text { font-size: 17px; font-weight: 800; }
        .footer-desc { font-size: 13px; color: #9ca3af; line-height: 1.65; max-width: 260px; }
        .footer-col-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #d1d5db; margin-bottom: 16px; }
        .footer-links { display: flex; flex-direction: column; gap: 10px; }
        .footer-links a { font-size: 14px; color: #9ca3af; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #6b7280;
        }
        .footer-disclaimer {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
        }
        .footer-contact a { color: #9ca3af; text-decoration: none; }
        .footer-contact a:hover { color: #fff; }

        /* ── Alert ── */
        .alert-success {
            padding: 16px 20px;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            color: #15803d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
        }

        @media (max-width: 768px) {
            .nav-links, .nav-actions { display: none !important; }
            .hamburger { display: flex; }

            .hero-inner { grid-template-columns: 1fr; padding: 60px 24px; gap: 40px; }
            .hero-card-wrap { display: none; }

            .steps-grid { grid-template-columns: 1fr; }
            .fair-grid { grid-template-columns: 1fr; }
            .testimonials-grid { grid-template-columns: 1fr; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .countdown-box { min-width: 80px; padding: 16px 18px; }
            .countdown-val { font-size: 36px; }

            .footer-grid { grid-template-columns: 1fr; gap: 28px; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
            .products-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }

        @media (max-width: 480px) {
            .products-grid { grid-template-columns: 1fr; }
            .hero-cta { flex-direction: column; }
            .draw-info-row { gap: 24px; }
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.7s ease forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.45s; opacity: 0; }
    </style>
</head>

<body>

    <!-- ═══════════════ NAVBAR ═══════════════ -->
    <nav class="navbar">
        <div class="nav-inner">
            <!-- Logo -->
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon">L</div>
                <span class="nav-logo-text">Lucky<span>Draw</span> Pro</span>
            </a>

            <!-- Desktop Links -->
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#draw">Draw Results</a></li>
                @if (Route::has('login'))
                    @if (auth()->check())
                        <li><a href="{{ url('/dashboard') }}" style="color: var(--blue-primary)">Dashboard</a></li>
                    @endif
                @endif
            </ul>

            <!-- Desktop Actions -->
            <div class="nav-actions">
                @if (Route::has('login'))
                    @if (auth()->check())
                        <a href="{{ url('/dashboard') }}" class="btn-primary">
                            Dashboard
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                    @endif
                @endif
            </div>

            <!-- Hamburger -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="/">Home</a>
        <a href="#how-it-works">How It Works</a>
        <a href="#draw">Draw Results</a>
        @if (Route::has('login'))
            @if (auth()->check())
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <div class="mobile-divider"></div>
                <a href="{{ route('login') }}">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary-full">Get Started →</a>
            @endif
        @endif
    </div>


    <!-- ═══════════════ HERO ═══════════════ -->
    <section class="hero">
        <div class="hero-inner">
            <div>
                @if(session('status'))
                    <div class="alert-success fade-up">
                        <span>👋</span> {{ session('status') }}
                    </div>
                @endif

                <div class="hero-badge fade-up">Trusted Digital Draw Platform</div>

                <h1 class="fade-up delay-1">
                    Buy Digital Products,<br>
                    <span>Win Amazing Prizes</span>
                </h1>

                <p class="hero-sub fade-up delay-2">
                    Purchase quality digital products and automatically receive entries into our transparent, provably
                    fair promotional draws. Every purchase counts.
                </p>

                <div class="hero-cta fade-up delay-3">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        Create Account
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#products" class="btn-hero-secondary">Explore Products</a>
                </div>

                <div class="hero-trust fade-up delay-4">
                    <div class="trust-item">
                        <div class="trust-icon">🔒</div>
                        100% Secure
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon">✅</div>
                        Verified Fair
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon">👥</div>
                        10,000+ Users
                    </div>
                </div>
            </div>

            <!-- Right Card -->
            <div class="hero-card-wrap fade-up delay-2">
                <div class="float-badge float-badge-1">🏆 $50,000 Grand Prize</div>
                <div class="hero-card">
                    <div class="card-header">
                        <span class="card-title">Live Draws</span>
                        <span class="live-dot">Live Now</span>
                    </div>

                    <div class="draw-card">
                        <div class="draw-card-icon">🏆</div>
                        <div style="flex:1">
                            <div class="draw-card-name">Mega Jackpot #402</div>
                            <div class="draw-card-prize">Prize: $50,000.00</div>
                        </div>
                        <div class="draw-card-end">Ends in 2h</div>
                    </div>

                    <div class="draw-card" style="background:#f8fafc; border-color:var(--border)">
                        <div class="draw-card-icon" style="background:#e0f2fe; font-size:20px">💎</div>
                        <div style="flex:1">
                            <div class="draw-card-name">Emerald Draw</div>
                            <div class="draw-card-prize">Prize: $2,500.00</div>
                        </div>
                        <div class="draw-card-soon">Soon</div>
                    </div>

                    <div class="card-stats">
                        <div class="card-stat">
                            <div class="card-stat-val">12,453</div>
                            <div class="card-stat-label">Total Entries</div>
                        </div>
                        <div class="card-stat">
                            <div class="card-stat-val">3,217</div>
                            <div class="card-stat-label">Participants</div>
                        </div>
                    </div>
                </div>
                <div class="float-badge float-badge-2">🎉 New winner every day!</div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ STATS ═══════════════ -->
    <section class="stats-section">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-val">$2.4M+</div>
                <div class="stat-label">Prizes Awarded</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">150K+</div>
                <div class="stat-label">Active Members</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">100%</div>
                <div class="stat-label">Verifiable Fairness</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">24/7</div>
                <div class="stat-label">Global Support</div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ HOW IT WORKS ═══════════════ -->
    <section class="section" id="how-it-works">
        <div class="section-inner">
            <div class="section-header">
                <div class="section-label">Simple Process</div>
                <h2 class="section-title">How It Works</h2>
                <p class="section-sub">Three simple steps to start winning</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-number">STEP 1</span>
                    <div class="step-icon step-icon-1">🛒</div>
                    <div class="step-title">Buy Product</div>
                    <p class="step-desc">Purchase any digital product from our verified collection and unlock your draw entries instantly.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">STEP 2</span>
                    <div class="step-icon step-icon-2">🎫</div>
                    <div class="step-title">Get Entry</div>
                    <p class="step-desc">Automatically receive draw entries based on your purchase. More purchases mean more chances to win.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">STEP 3</span>
                    <div class="step-icon step-icon-3">🏆</div>
                    <div class="step-title">Transparent Draw</div>
                    <p class="step-desc">Win prizes through our provably fair draw system with full cryptographic transparency and verification.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ CURRENT DRAW ═══════════════ -->
    <section class="draw-section" id="draw">
        <div class="draw-section-inner">
            <div class="draw-badge">● Active Draw</div>
            <h2 class="draw-title">Current Promotional Draw</h2>
            <p class="draw-subtitle">Draw #2461 · Grand Prize: ৳50,000</p>

            <div class="countdown-row" id="countdown">
                <div class="countdown-box">
                    <div class="countdown-val" id="cd-days">02</div>
                    <div class="countdown-label">Days</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-val" id="cd-hours">14</div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-val" id="cd-minutes">32</div>
                    <div class="countdown-label">Minutes</div>
                </div>
                <div class="countdown-box">
                    <div class="countdown-val" id="cd-seconds">32</div>
                    <div class="countdown-label">Seconds</div>
                </div>
            </div>

            <div class="draw-info-row">
                <div class="draw-info-item">
                    <div class="draw-info-val">12,453</div>
                    <div class="draw-info-label">Total Entries</div>
                </div>
                <div class="draw-info-item">
                    <div class="draw-info-val">3,217</div>
                    <div class="draw-info-label">Participants</div>
                </div>
                <div class="draw-info-item">
                    <div class="draw-info-val">৳124,530</div>
                    <div class="draw-info-label">Prize Pool</div>
                </div>
            </div>

            <a href="{{ route('register') }}" class="btn-draw">
                Buy Products to Enter
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>


    <!-- ═══════════════ FEATURED PRODUCTS ═══════════════ -->
    <section class="section bg-light" id="products">
        <div class="section-inner">
            <div class="products-header">
                <div>
                    <div class="section-label">Shop & Win</div>
                    <h2 class="section-title" style="margin-bottom:4px">Featured Products</h2>
                    <p style="color:var(--text-muted); font-size:15px">Quality digital products with automatic entries</p>
                </div>
                <a href="#" class="view-all">View All →</a>
            </div>

            <div class="products-grid">
                <div class="product-card">
                    <div class="product-img">📚</div>
                    <div class="product-body">
                        <span class="product-entries">5 entries</span>
                        <div class="product-name">Premium eBook Collection</div>
                        <div class="product-price">৳499</div>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img">🎨</div>
                    <div class="product-body">
                        <span class="product-entries">10 entries</span>
                        <div class="product-name">Design Templates Pack</div>
                        <div class="product-price">৳899</div>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img">💼</div>
                    <div class="product-body">
                        <span class="product-entries">15 entries</span>
                        <div class="product-name">Business Course Access</div>
                        <div class="product-price">৳1499</div>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-img">📷</div>
                    <div class="product-body">
                        <span class="product-entries">3 entries</span>
                        <div class="product-name">Stock Photo Bundle</div>
                        <div class="product-price">৳699</div>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ PROVABLY FAIR ═══════════════ -->
    <section class="section" id="winners">
        <div class="section-inner">
            <div class="fair-grid">
                <div>
                    <div class="section-label">No Manipulation</div>
                    <h2 class="section-title">Provably Fair System</h2>
                    <p class="section-sub" style="margin-bottom:36px">Complete transparency in every draw</p>

                    <div class="fair-features">
                        <div class="fair-feature">
                            <div class="fair-icon fair-icon-1">#️⃣</div>
                            <div>
                                <div class="fair-feature-title">Cryptographic Hashing</div>
                                <p class="fair-feature-desc">Every draw uses server seed hashing that's published before the draw, ensuring no manipulation.</p>
                            </div>
                        </div>
                        <div class="fair-feature">
                            <div class="fair-icon fair-icon-2">🔗</div>
                            <div>
                                <div class="fair-feature-title">Immutable Records</div>
                                <p class="fair-feature-desc">All draw results are permanently recorded and can be independently verified by anyone.</p>
                            </div>
                        </div>
                        <div class="fair-feature">
                            <div class="fair-icon fair-icon-3">🔍</div>
                            <div>
                                <div class="fair-feature-title">Public Verification</div>
                                <p class="fair-feature-desc">Anyone can verify the fairness of any draw using our open verification tools.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fair-card">
                    <div class="fair-card-title">Server Seed Hash (Pre-Published)</div>
                    <div class="fair-card-hash">a1f3d2e8c9b4a7e6d5c2b1a0f9e8d7c6b5a4e3d2c1b0a9f8e7d6c5b4a3e2d1c0b9</div>

                    <div class="fair-card-title">Client Seed (User-Generated)</div>
                    <div class="fair-card-hash">f3e4a2d1c0b9a8f7e6d5c4b3a2f1e0d9c8b7a6e5d4</div>

                    <div class="fair-card-title">Result Hash</div>
                    <div class="fair-card-hash">a2d5e4f195d204f795f29b205e5f49a8a2d5b4e194c3a2f1e0d9c8b7a6e5d4c3b2a1</div>

                    <a href="#" class="verify-btn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        View Sample Verification
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ TESTIMONIALS ═══════════════ -->
    <section class="section bg-light">
        <div class="section-inner">
            <div class="section-header">
                <div class="section-label">Social Proof</div>
                <h2 class="section-title">Trusted by Thousands</h2>
                <p class="section-sub">See what our users say about us</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"I love the transparency! I can actually verify that the draws are fair. Got great products too."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">R</div>
                        <div>
                            <div class="author-name">Rafiq Ahmed</div>
                            <div class="author-role">Small Business Owner</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"Finally a platform I can trust. The products are useful and the draws are completely transparent."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar" style="background:#7c3aed">N</div>
                        <div>
                            <div class="author-name">Nusrat Jahan</div>
                            <div class="author-role">Freelance Designer</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">★★★★★</div>
                    <p class="testimonial-text">"Won my first prize last month! The verification system is amazing. Everything is clear and fair."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar" style="background:#059669">K</div>
                        <div>
                            <div class="author-name">Kamal Hossain</div>
                            <div class="author-role">Student · Dhaka, Bangladesh</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ═══════════════ FOOTER ═══════════════ -->
    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">
                        <div class="footer-logo-icon">L</div>
                        <span class="footer-logo-text">LuckyDraw Pro</span>
                    </div>
                    <p class="footer-desc">A trusted digital product platform with transparent promotional draws. Every draw is provably fair.</p>
                </div>
                <div>
                    <div class="footer-col-title">Company</div>
                    <div class="footer-links">
                        <a href="#">About Us</a>
                        <a href="#how-it-works">How It Works</a>
                        <a href="#">Products</a>
                        <a href="#draw">Draw Results</a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Legal</div>
                    <div class="footer-links">
                        <a href="#">Terms of Service</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Fair Play Policy</a>
                        <a href="#">Refund Policy</a>
                    </div>
                </div>
                <div class="footer-contact">
                    <div class="footer-col-title">Contact</div>
                    <div class="footer-links">
                        <a href="mailto:support@luckydrawpro.com">support@luckydrawpro.com</a>
                        <a href="tel:+8801234567890">+880 1234-567890</a>
                        <span style="color:#9ca3af; font-size:14px">Dhaka, Bangladesh</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; 2026 LuckyDraw Pro. All rights reserved.</span>
                <span class="footer-disclaimer">This is not a gambling platform.</span>
            </div>
        </div>
    </footer>


    <script>
        // ── Mobile Nav Toggle ──
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });
        // Close menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('open');
                mobileMenu.classList.remove('open');
            });
        });

        // ── Countdown Timer ──
        function updateCountdown() {
            // Set target to 2 days 14 hours from now (demo — replace with actual draw end time from server)
            const target = new Date();
            target.setDate(target.getDate() + 2);
            target.setHours(target.getHours() + 14);
            target.setMinutes(target.getMinutes() + 32);

            const stored = localStorage.getItem('draw_target');
            const drawTarget = stored ? new Date(parseInt(stored)) : target;
            if (!stored) localStorage.setItem('draw_target', drawTarget.getTime());

            function tick() {
                const now = new Date();
                const diff = drawTarget - now;
                if (diff <= 0) {
                    document.getElementById('cd-days').textContent = '00';
                    document.getElementById('cd-hours').textContent = '00';
                    document.getElementById('cd-minutes').textContent = '00';
                    document.getElementById('cd-seconds').textContent = '00';
                    return;
                }
                const d = Math.floor(diff / 86400000);
                const h = Math.floor((diff % 86400000) / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                document.getElementById('cd-days').textContent = String(d).padStart(2, '0');
                document.getElementById('cd-hours').textContent = String(h).padStart(2, '0');
                document.getElementById('cd-minutes').textContent = String(m).padStart(2, '0');
                document.getElementById('cd-seconds').textContent = String(s).padStart(2, '0');
            }
            tick();
            setInterval(tick, 1000);
        }
        updateCountdown();

        // ── Scroll reveal ──
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.animation = 'fadeUp 0.6s ease forwards';
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.step-card, .product-card, .testimonial-card, .fair-feature').forEach(el => {
            el.style.opacity = '0';
            observer.observe(el);
        });
    </script>

</body>
</html>