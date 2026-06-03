<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1a7fd4">
    <title>BantuJiran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *{-webkit-tap-highlight-color:transparent}
        body { font-family:'Inter',sans-serif; background:#dfe8f0; -webkit-font-smoothing:antialiased; }
        .hero {
            background: linear-gradient(160deg,#1a5fa8 0%,#1a9fba 55%,#0db8a0 100%);
            padding: 3rem 1.5rem 2.5rem; text-align:center; position:relative; overflow:hidden;
        }
        .hero::before {
            content:''; position:absolute; top:-60px; right:-60px;
            width:220px;height:220px;background:rgba(255,255,255,.06);border-radius:50%;
        }
        .hero::after {
            content:''; position:absolute; bottom:-80px; left:-40px;
            width:200px;height:200px;background:rgba(255,255,255,.05);border-radius:50%;
        }
        .hero-icon {
            width:84px;height:84px;background:rgba(255,255,255,.18);
            backdrop-filter:blur(10px);border-radius:28px;
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 1.25rem;border:2px solid rgba(255,255,255,.28);
            box-shadow:0 8px 32px rgba(0,0,0,.15); position:relative;z-index:1;
        }
        .hero h1{font-size:2rem;font-weight:800;color:#fff;letter-spacing:-.5px;line-height:1.15;margin-bottom:.6rem;position:relative;z-index:1}
        .hero p{color:rgba(255,255,255,.78);font-size:.9rem;max-width:280px;margin:0 auto 2rem;line-height:1.6;position:relative;z-index:1}
        .btn-cta {
            background:#fff;color:#1a7fd4;border:none;border-radius:99px;
            padding:.85rem 2rem;font-size:.95rem;font-weight:800;
            width:100%;max-width:320px;display:block;margin:0 auto;
            box-shadow:0 6px 24px rgba(0,0,0,.15);transition:transform .15s,box-shadow .15s;
            text-decoration:none;text-align:center;
        }
        .btn-cta:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(0,0,0,.2);color:#1a7fd4}
        .pill-row{display:flex;gap:.75rem;justify-content:center;margin-top:1.75rem;flex-wrap:wrap;position:relative;z-index:1}
        .pill{background:rgba(255,255,255,.15);color:rgba(255,255,255,.85);font-size:.75rem;font-weight:600;padding:.3rem .85rem;border-radius:99px;display:flex;align-items:center;gap:.35rem}

        .section{padding:1.75rem 1.25rem;max-width:560px;margin:0 auto}
        .section h2{font-size:1.2rem;font-weight:800;color:#1a2740;letter-spacing:-.3px;margin-bottom:.35rem}
        .section p{color:#6b8299;font-size:.85rem;margin-bottom:1.25rem}
        .mod-card{background:#fff;border-radius:18px;padding:1rem 1.1rem;display:flex;align-items:center;gap:.9rem;margin-bottom:.65rem;box-shadow:0 2px 12px rgba(30,60,100,.07)}
        .mod-icon{width:46px;height:46px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
        .mod-card h6{font-size:.875rem;font-weight:700;color:#1a2740;margin-bottom:.2rem}
        .mod-card p{font-size:.75rem;color:#8fa8bf;margin:0;line-height:1.35}
        .foot{background:#fff;padding:1.5rem;text-align:center;border-top:1px solid #e5edf4}
        .foot a{color:#1a7fd4;font-weight:700;text-decoration:none;font-size:.875rem}
    </style>
</head>
<body>
<section class="hero">
    <div class="hero-icon"><i class="bi bi-people-fill" style="font-size:2.2rem;color:#fff"></i></div>
    <h1>Jiran Membantu<br>Jiran</h1>
    <p>Komuniti digital hiper-lokal untuk kejiranan yang lebih padu & berdaya tahan.</p>
    @auth
        <a href="{{ route('feed.index') }}" class="btn-cta"><i class="bi bi-compass-fill me-2"></i>Lihat Suapan Jiran</a>
    @else
        <a href="{{ route('login') }}" class="btn-cta"><i class="bi bi-box-arrow-in-right me-2"></i>Mula Sekarang — Percuma</a>
    @endauth
    <div class="pill-row">
        <span class="pill"><i class="bi bi-shield-check-fill"></i>Selamat</span>
        <span class="pill"><i class="bi bi-geo-alt-fill"></i>Hiper-Lokal</span>
        <span class="pill"><i class="bi bi-heart-fill"></i>Komuniti Dahulu</span>
    </div>
</section>

<div class="section">
    <h2>5 Modul Utama</h2>
    <p>Pelbagai cara untuk bantu & terima bantuan daripada jiran anda.</p>

    <div class="mod-card">
        <div class="mod-icon" style="background:linear-gradient(135deg,#f05a7a,#f8416a)"><i class="bi bi-exclamation-triangle-fill" style="color:#fff"></i></div>
        <div><h6>Mohon Bantuan</h6><p>Hebahkan keperluan mendesak anda. Jiran terdekat dapat notis segera.</p></div>
    </div>
    <div class="mod-card">
        <div class="mod-icon" style="background:linear-gradient(135deg,#00c8aa,#00a693)"><i class="bi bi-box2-heart-fill" style="color:#fff"></i></div>
        <div><h6>Derma Barang</h6><p>Iklan barang tidak digunakan untuk diberi. Jiran lain boleh mohon.</p></div>
    </div>
    <div class="mod-card">
        <div class="mod-icon" style="background:linear-gradient(135deg,#9b6fdb,#7c5cbf)"><i class="bi bi-tools" style="color:#fff"></i></div>
        <div><h6>Perkongsian Peralatan</h6><p>Pinjam peralatan jarang guna — elak pembaziran & belanja lebihan.</p></div>
    </div>
    <div class="mod-card">
        <div class="mod-icon" style="background:linear-gradient(135deg,#1a7fd4,#0bb5e3)"><i class="bi bi-car-front-fill" style="color:#fff"></i></div>
        <div><h6>Mobiliti & Tumpangan</h6><p>Kongsi perjalanan jarak dekat, jimat minyak & kurang kesesakan.</p></div>
    </div>
    <div class="mod-card">
        <div class="mod-icon" style="background:linear-gradient(135deg,#f7c059,#f59e0b)"><i class="bi bi-bag-fill" style="color:#fff"></i></div>
        <div><h6>Pengiklanan Perkhidmatan/Barangan</h6><p>Iklan perkhidmatan atau barangan jualan kepada jiran sekawasan.</p></div>
    </div>
</div>

<div class="foot">
    @auth
        <a href="{{ route('feed.index') }}">Pergi ke Suapan <i class="bi bi-arrow-right"></i></a>
    @else
        <a href="{{ route('login') }}">Log masuk sekarang <i class="bi bi-arrow-right"></i></a>
    @endauth
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
