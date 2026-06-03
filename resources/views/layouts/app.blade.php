<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#5B6BF8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title', 'BantuJiran') &middot; BantuJiran</title>
    <link rel="manifest" href="/manifest.json">
    <!-- Apply theme before render to prevent flash -->
    <script>
        (function(){
            var t=localStorage.getItem('bj-theme')||'light';
            document.documentElement.setAttribute('data-theme',t);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    @stack('head')
    <style>
        /* ── Light mode (default) ── */
        :root {
            --p:        #5B6BF8;
            --p-dark:   #4555E8;
            --p-soft:   #EEF0FF;
            --p-rgb:    91,107,248;
            --c-sos:    #FF4757;
            --c-buy:    #FF9500;
            --c-mob:    #5B6BF8;
            --c-tool:   #9B59B6;
            --c-mon:    #00C8AA;
            --app-bg:   #F0F2FF;
            --bg2:      #E8EBFE;
            --card:     #FFFFFF;
            --th:       #1A1D3B;
            --tb:       #4A5068;
            --tm:       #9BA3C4;
            --bdr:      #E4E7FF;
            --r:        20px;
            --r-sm:     12px;
            --sh:       0 2px 16px rgba(91,107,248,.07),0 1px 4px rgba(0,0,0,.04);
            --sh-md:    0 8px 32px rgba(91,107,248,.14),0 2px 8px rgba(0,0,0,.06);
            --nav-h:    70px;
            --pw:       393px;
            --ph:       852px;
            --nav-bg:   rgba(240,242,255,.92);
            --nav-bdr:  rgba(228,231,255,.7);
            --bnav-bg:  rgba(255,255,255,.97);
            --bnav-bdr: rgba(228,231,255,.8);
            --input-bg: #F8F9FF;
            --input-bdr:#E4E7FF;
        }

        /* ── Dark mode ── */
        [data-theme="dark"] {
            --app-bg:   #0E1018;
            --bg2:      #161B2E;
            --card:     #1A1F32;
            --th:       #E8ECFF;
            --tb:       #A0ABCC;
            --tm:       #555E80;
            --bdr:      #252A40;
            --p-soft:   #1E2248;
            --sh:       0 2px 16px rgba(0,0,0,.3),0 1px 4px rgba(0,0,0,.2);
            --sh-md:    0 8px 32px rgba(0,0,0,.4),0 2px 8px rgba(0,0,0,.3);
            --nav-bg:   rgba(14,16,24,.92);
            --nav-bdr:  rgba(37,42,64,.8);
            --bnav-bg:  rgba(18,20,32,.97);
            --bnav-bdr: rgba(37,42,64,.8);
            --input-bg: #12152A;
            --input-bdr:#252A40;
        }
        *,*::before,*::after { box-sizing:border-box; -webkit-tap-highlight-color:transparent; }

        body {
            font-family:'Inter',-apple-system,sans-serif;
            -webkit-font-smoothing:antialiased;
            font-size:14px; color:var(--tb); margin:0;
        }

        /* ── DESKTOP: phone mockup ── */
        @media(min-width:768px){
            html,body { width:100%;height:100%;overflow:hidden; }
            body {
                background:#0D0F1E;
                background-image:
                    radial-gradient(ellipse 80% 60% at 20% 10%, rgba(91,107,248,.25) 0%,transparent 60%),
                    radial-gradient(ellipse 60% 50% at 85% 85%, rgba(155,89,182,.2) 0%,transparent 55%),
                    radial-gradient(ellipse 50% 40% at 55% 50%, rgba(0,200,170,.1) 0%,transparent 50%);
            }
            .bj-frame {
                position:fixed; top:50%; left:50%;
                transform:translate(-50%,-50%);
                width:var(--pw); height:var(--ph);
                background:var(--app-bg);
                border-radius:52px;
                box-shadow:
                    0 0 0 2px rgba(255,255,255,.08),
                    0 0 0 11px #0D0F1E,
                    0 0 0 13px #1E2235,
                    0 0 0 14px rgba(255,255,255,.04),
                    0 60px 120px rgba(0,0,0,.7),
                    0 20px 40px rgba(0,0,0,.4);
                overflow:hidden;
                display:flex; flex-direction:column;
                flex-shrink:0; z-index:1;
            }
            /* Notch */
            .bj-frame::before {
                content:'';
                position:absolute; top:0; left:50%; transform:translateX(-50%);
                width:126px; height:34px;
                background:#0D0F1E;
                border-radius:0 0 22px 22px;
                z-index:100;
            }
            /* Side buttons */
            .bj-frame::after {
                content:'';
                position:absolute; right:-13px; top:130px;
                width:3px; height:60px;
                background:#1E2235; border-radius:0 3px 3px 0;
                box-shadow: 0 80px 0 #1E2235, 0 -80px 0 #1E2235;
            }
            .bj-statusbar {
                display:flex; align-items:center; justify-content:space-between;
                padding:10px 28px 0; height:44px; flex-shrink:0;
                position:relative; z-index:99;
            }
            .bj-statusbar .st { font-size:.76rem; font-weight:800; color:var(--th); letter-spacing:-.3px; }
            .bj-statusbar .si { display:flex; align-items:center; gap:.3rem; color:var(--th); }
            .bj-statusbar .si i { font-size:.78rem; }
            .bj-scroll {
                flex:1; overflow-y:auto; overflow-x:hidden;
                display:flex; flex-direction:column;
                scrollbar-width:none; -ms-overflow-style:none;
                position:relative; min-height:0;
            }
            .bj-scroll::-webkit-scrollbar { display:none; }
            .bj-nav { position:sticky!important; top:0; }
            .bj-bnav {
                position:relative!important; flex-shrink:0;
                border-radius:0 0 40px 40px;
            }
            .bj-homebar {
                flex-shrink:0; height:24px;
                background:var(--app-bg);
                border-radius:0 0 40px 40px;
                display:flex; align-items:center; justify-content:center;
            }
            .bj-homebar::after {
                content:''; width:120px; height:5px;
                background:rgba(128,128,180,.25); border-radius:99px;
            }
            [data-theme="dark"] .bj-frame {
                background:var(--app-bg);
                box-shadow:
                    0 0 0 2px rgba(255,255,255,.04),
                    0 0 0 11px #0D0F1E,
                    0 0 0 13px #1A1F30,
                    0 0 0 14px rgba(255,255,255,.03),
                    0 60px 120px rgba(0,0,0,.8),
                    0 20px 40px rgba(0,0,0,.5);
            }
            .bj-desklabel {
                position:fixed;
                bottom:calc(50% - var(--ph)/2 - 2.5rem);
                left:50%; transform:translateX(-50%);
                color:rgba(255,255,255,.3); font-size:.68rem;
                font-weight:600; letter-spacing:.8px; text-transform:uppercase;
                white-space:nowrap; z-index:0;
            }
        }

        /* ── MOBILE ── */
        @media(max-width:767px){
            body { background:var(--app-bg); }
            .bj-frame,.bj-scroll,.bj-statusbar,.bj-homebar,.bj-desklabel { all:unset; display:contents; }
            .bj-bnav {
                position:fixed!important; bottom:0; left:0; right:0;
                padding-bottom:env(safe-area-inset-bottom);
                border-radius:0!important;
            }
            main { padding-bottom:calc(var(--nav-h) + 1.25rem + env(safe-area-inset-bottom))!important; }
        }

        /* ── Navbar ── */
        .bj-nav {
            background:var(--nav-bg);
            backdrop-filter:blur(28px) saturate(180%);
            -webkit-backdrop-filter:blur(28px) saturate(180%);
            border-bottom:1px solid var(--nav-bdr);
            padding:.6rem 0; top:0; z-index:50;
        }
        .bj-logo {
            display:flex; align-items:center; gap:.5rem;
            text-decoration:none; color:var(--th)!important;
            font-weight:900; font-size:1.15rem; letter-spacing:-.5px;
        }
        .bj-logo-mark {
            width:34px; height:34px; border-radius:11px;
            background:linear-gradient(135deg,#5B6BF8,#9B59B6);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:.95rem;
            box-shadow:0 3px 10px rgba(91,107,248,.4);
        }
        .bj-loc {
            background:var(--p-soft); color:var(--p);
            font-size:.72rem; font-weight:700;
            padding:.3rem .7rem; border-radius:99px;
            display:flex; align-items:center; gap:.3rem;
            max-width:148px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
        }

        /* ── Bottom Nav ── */
        .bj-bnav {
            z-index:40; height:var(--nav-h);
            background:var(--bnav-bg);
            backdrop-filter:blur(28px) saturate(180%);
            -webkit-backdrop-filter:blur(28px) saturate(180%);
            border-top:1px solid var(--bnav-bdr);
            box-shadow:0 -4px 24px rgba(91,107,248,.06);
            display:flex; align-items:stretch;
        }
        .bj-bnav a {
            flex:1; display:flex; flex-direction:column;
            align-items:center; justify-content:center; gap:.15rem;
            color:var(--tm); text-decoration:none;
            font-size:.6rem; font-weight:700; letter-spacing:.3px;
            text-transform:uppercase; position:relative; transition:color .2s;
        }
        .bj-bnav a i { font-size:1.3rem; transition:transform .2s; }
        .bj-bnav a.active { color:var(--p); }
        .bj-bnav a.active i { transform:translateY(-1px); }
        .bj-bnav a.active::before {
            content:''; position:absolute; top:0; left:50%; transform:translateX(-50%);
            width:28px; height:3px; background:var(--p); border-radius:0 0 4px 4px;
        }
        .bj-fab {
            width:54px; height:54px; border-radius:18px; margin-top:-16px;
            background:linear-gradient(135deg,#5B6BF8 0%,#9B59B6 100%);
            color:#fff!important; display:flex; align-items:center; justify-content:center;
            box-shadow:0 6px 20px rgba(91,107,248,.5); transition:transform .18s;
        }
        .bj-fab:hover { transform:scale(.93) translateY(1px); }
        .bj-fab i { font-size:1.3rem!important; }
        .bj-fab::before { display:none!important; }

        /* ── Cards ── */
        .bj-card {
            background:var(--card); border-radius:var(--r);
            box-shadow:var(--sh); border:none; overflow:hidden;
        }
        .bj-hero {
            border-radius:var(--r); overflow:hidden; position:relative;
            box-shadow:var(--sh-md);
        }
        .bj-hero-inner {
            background:linear-gradient(135deg,#5B6BF8 0%,#7B5BF8 50%,#9B59B6 100%);
            padding:1.4rem 1.25rem 1.2rem; position:relative;
        }
        .bj-hero-inner::after {
            content:''; position:absolute;
            top:-60px; right:-50px; width:200px; height:200px;
            background:rgba(255,255,255,.07); border-radius:50%; pointer-events:none;
        }
        .bj-hero-inner::before {
            content:''; position:absolute;
            bottom:-80px; left:-30px; width:180px; height:180px;
            background:rgba(255,255,255,.05); border-radius:50%; pointer-events:none;
        }

        /* ── Module icons ── */
        .mi {
            width:52px; height:52px; border-radius:17px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.35rem; flex-shrink:0;
        }
        .mi-sos  { background:linear-gradient(135deg,#FF4757,#FF6B81); color:#fff; }
        .mi-buy  { background:linear-gradient(135deg,#FF9500,#FFBE00); color:#fff; }
        .mi-mob  { background:linear-gradient(135deg,#5B6BF8,#4ECDC4); color:#fff; }
        .mi-tool { background:linear-gradient(135deg,#9B59B6,#7D3C98); color:#fff; }
        .mi-donate { background:linear-gradient(135deg,#00C8AA,#00A693); color:#fff; }

        /* Type text colors */
        .tc-sos  { color:#FF4757; } .tc-buy  { color:#FF9500; }
        .tc-mob  { color:#5B6BF8; } .tc-tool { color:#9B59B6; } .tc-donate { color:#00C8AA; }

        /* ── Buttons ── */
        .btn { font-family:'Inter',sans-serif; font-weight:700; font-size:.875rem; border-radius:var(--r-sm); transition:all .18s; letter-spacing:-.1px; }
        .btn-primary { background:linear-gradient(135deg,var(--p),#7B5BF8); border:none; color:#fff; box-shadow:0 4px 14px rgba(var(--p-rgb),.4); }
        .btn-primary:hover { background:linear-gradient(135deg,var(--p-dark),var(--p)); box-shadow:0 6px 20px rgba(var(--p-rgb),.5); transform:translateY(-1px); }
        .btn-primary:active { transform:translateY(0); }
        .btn-pill { border-radius:99px!important; }
        .btn-ghost { background:var(--p-soft); border:none; color:var(--p); font-weight:700; }
        .btn-ghost:hover { background:#E0E3FD; color:var(--p); }
        .btn-outline-primary { border:2px solid var(--p); color:var(--p); background:transparent; }
        .btn-outline-primary:hover { background:var(--p-soft); color:var(--p); border-color:var(--p); }
        .btn-outline-danger { border:2px solid #FF4757; color:#FF4757; background:transparent; }
        .btn-outline-danger:hover { background:#FFF0F1; color:#FF4757; }
        .btn-teal { background:linear-gradient(135deg,#00C8AA,#00A693); border:none; color:#fff; box-shadow:0 4px 14px rgba(0,200,170,.35); }
        .btn-warning { background:linear-gradient(135deg,#FF9500,#FFBE00); border:none; color:#fff; font-weight:700; }
        .btn-lg { padding:.85rem 1.5rem; font-size:.95rem; border-radius:16px; }
        .btn-sm { border-radius:9px; font-size:.78rem; padding:.3rem .7rem; }

        /* ── Forms ── */
        .form-control,.form-select {
            border:2px solid var(--input-bdr); border-radius:var(--r-sm);
            padding:.7rem 1rem; font-size:.9rem; color:var(--th);
            background:var(--input-bg); font-family:'Inter',sans-serif;
            transition:border-color .15s,box-shadow .15s;
        }
        .form-control:focus,.form-select:focus {
            border-color:var(--p); background:var(--card);
            box-shadow:0 0 0 4px rgba(var(--p-rgb),.1); outline:none;
        }
        .form-control::placeholder { color:var(--tm); }
        .form-label { font-weight:700; font-size:.7rem; color:var(--tm); text-transform:uppercase; letter-spacing:.6px; margin-bottom:.4rem; }
        .input-group-text { border:2px solid var(--input-bdr); border-right:none; background:var(--p-soft); color:var(--th); font-weight:700; border-radius:var(--r-sm) 0 0 var(--r-sm); }
        .input-group .form-control { border-left:none; border-radius:0 var(--r-sm) var(--r-sm) 0!important; }
        .input-group:focus-within .input-group-text { border-color:var(--p); background:var(--card); }

        /* ── Chips ── */
        .bj-chip {
            display:inline-flex; align-items:center; gap:.3rem;
            padding:.38rem .9rem; border-radius:99px; font-size:.75rem; font-weight:700;
            border:2px solid var(--bdr); background:var(--card); color:var(--tm);
            cursor:pointer; transition:all .15s; white-space:nowrap; letter-spacing:-.1px;
        }
        .bj-chip.active { background:var(--p); border-color:var(--p); color:#fff; box-shadow:0 3px 10px rgba(var(--p-rgb),.35); }
        .bj-chip:hover:not(.active) { border-color:var(--p); color:var(--p); }

        /* ── Alerts ── */
        .alert { border:none; border-radius:var(--r-sm); font-size:.875rem; }
        .alert-info { background:var(--p-soft); color:#3040C8; }
        .alert-danger { background:#FFF0F1; color:#C01030; }
        .alert-warning { background:#FFF8E7; color:#9A5000; }

        /* ── Empty state ── */
        .bj-empty { text-align:center; padding:2.5rem 1.5rem; }
        .bj-empty-icon {
            width:80px; height:80px; border-radius:26px;
            background:linear-gradient(135deg,var(--p-soft),#E8E0FF);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 1rem; font-size:2rem; color:var(--p);
        }

        /* ── Section label ── */
        .bj-label { font-size:.68rem; font-weight:800; letter-spacing:.7px; text-transform:uppercase; color:var(--tm); }
        .bj-title { font-size:1.05rem; font-weight:800; color:var(--th); letter-spacing:-.3px; }
        .bj-sub   { font-size:.8rem; color:var(--tm); font-weight:500; }

        /* ── Map ── */
        .leaflet-container { z-index:1; }
        .bj-map { border-radius:var(--r-sm); border:2px solid #E4E7FF; overflow:hidden; }

        /* ── Misc ── */
        .app-container { max-width:600px; }
        @media(min-width:768px){ .app-container { max-width:100%; padding:0 1rem; } }
        ::-webkit-scrollbar { width:3px; } ::-webkit-scrollbar-thumb { background:#C8CCEF; border-radius:99px; }
        .fade-in { animation:bjIn .28s cubic-bezier(.22,.68,0,1.2); }
        @keyframes bjIn { from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none} }
        .badge-trust { background:#FFF3CD; color:#9A5000; font-weight:700; border-radius:8px; }

        /* ── Dark mode overrides ── */
        [data-theme="dark"] body { color:var(--tb); background:var(--app-bg); }
        [data-theme="dark"] .bj-frame { background:var(--app-bg); }
        [data-theme="dark"] .bj-card { background:var(--card); }
        [data-theme="dark"] .bj-homebar { background:var(--app-bg); }
        [data-theme="dark"] .alert-info { background:#1E2248; color:#A0ABFF; }
        [data-theme="dark"] .alert-danger { background:#2A1520; color:#FF8090; }
        [data-theme="dark"] .alert-warning { background:#2A1F0A; color:#FFB840; }
        [data-theme="dark"] .badge-trust { background:#2A2010; color:#FFB840; }
        [data-theme="dark"] .btn-ghost { background:rgba(91,107,248,.2); color:#A0ABFF; }
        [data-theme="dark"] .btn-ghost:hover { background:rgba(91,107,248,.3); color:#A0ABFF; }
        [data-theme="dark"] .btn-outline-primary { border-color:#5B6BF8; color:#A0ABFF; }
        [data-theme="dark"] .btn-outline-primary:hover { background:#1E2248; color:#A0ABFF; }
        [data-theme="dark"] .btn-outline-danger { border-color:#FF4757; color:#FF8090; }
        [data-theme="dark"] .btn-outline-danger:hover { background:#2A1520; color:#FF8090; }
        [data-theme="dark"] .form-select option { background:#1A1F32; color:var(--th); }
        [data-theme="dark"] hr { border-color:var(--bdr); }
        [data-theme="dark"] .leaflet-tile { filter:invert(1) hue-rotate(180deg) brightness(.85) saturate(.8); }
        [data-theme="dark"] .leaflet-container { background:#0E1018; }
        /* Theme transition */
        html { transition:background .3s; }
        .bj-frame,.bj-card,.bj-nav,.bj-bnav { transition:background .3s,border-color .3s; }
    </style>
</head>
<body>
<div class="bj-frame">
    <!-- Status bar -->
    <div class="bj-statusbar">
        <span class="st" id="bjClock">9:41</span>
        <div style="width:126px"></div>
        <div class="si"><i class="bi bi-reception-4"></i><i class="bi bi-wifi"></i><i class="bi bi-battery-half"></i></div>
    </div>

    <!-- Scroll area -->
    <div class="bj-scroll" id="bjScroll">
        <!-- Navbar -->
        <nav class="bj-nav navbar">
            <div class="container app-container d-flex align-items-center justify-content-between">
                <a class="bj-logo" href="{{ route('feed.index') }}">
                    <div class="bj-logo-mark"><i class="bi bi-people-fill"></i></div>
                    BantuJiran
                </a>
                <div class="d-flex align-items-center gap-2">
                    @auth
                    <div class="bj-loc">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>{{ auth()->user()->address_label ?? 'Tetapkan lokasi' }}</span>
                    </div>
                    @endauth
                    <!-- Theme toggle -->
                    <button id="themeToggle" onclick="toggleTheme()"
                        title="Tukar tema"
                        style="width:34px;height:34px;border:none;border-radius:11px;background:var(--p-soft);color:var(--p);display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;transition:background .2s,color .2s">
                        <i class="bi bi-moon-fill" id="themeIcon" style="font-size:.9rem"></i>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Alerts -->
        <div class="container app-container pt-2">
            @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="bi bi-info-circle-fill me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        <!-- Page -->
        <main class="container app-container py-3 fade-in">@yield('content')</main>
    </div><!-- /bj-scroll -->

    <!-- Bottom nav — outside scroll -->
    @auth
    <nav class="bj-bnav">
        <a href="{{ route('feed.index') }}" class="{{ request()->routeIs('feed.*')?'active':'' }}">
            <i class="bi bi-compass{{ request()->routeIs('feed.*')?'-fill':'' }}"></i><span>Suapan</span>
        </a>
        <a href="{{ route('modules.index') }}" class="{{ request()->routeIs('modules.*')?'active':'' }}">
            <i class="bi bi-grid{{ request()->routeIs('modules.*')?'-fill':'' }}"></i><span>Modul</span>
        </a>
        <a href="{{ route('posts.create') }}" class="bj-fab"><i class="bi bi-plus-lg"></i></a>
        <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*')?'active':'' }}" style="position:relative">
            <i class="bi bi-chat-dots{{ request()->routeIs('chat.*')?'-fill':'' }}"></i>
            <span>Sembang</span>
            @auth @if(auth()->user()->unreadChats() > 0)
            <span id="chatBadge" style="position:absolute;top:8px;right:calc(50% - 20px);width:8px;height:8px;background:#FF4757;border-radius:50%;border:2px solid var(--bnav-bg)"></span>
            @endif @endauth
        </a>
        <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*')?'active':'' }}">
            <i class="bi bi-person-circle"></i><span>Profil</span>
        </a>
    </nav>
    @endauth

    <div class="bj-homebar"></div>
</div><!-- /bj-frame -->

<div class="bj-desklabel">BantuJiran &nbsp;·&nbsp; Komuniti Digital Hiper-Lokal</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
/* ── Clock ── */
(function tick(){
    var e=document.getElementById('bjClock');
    if(e){var n=new Date();e.textContent=String(n.getHours()).padStart(2,'0')+':'+String(n.getMinutes()).padStart(2,'0');}
    setTimeout(tick,10000);
})();

/* ── Desktop chat fix ── */
if(window.innerWidth>=768){
    document.addEventListener('DOMContentLoaded',function(){
        document.querySelectorAll('.chat-input-wrap').forEach(function(el){
            el.style.position='absolute';el.style.bottom='0';el.style.left='0';el.style.right='0';
        });
    });
}

/* ── Theme ── */
function applyTheme(t){
    document.documentElement.setAttribute('data-theme',t);
    localStorage.setItem('bj-theme',t);
    var icon=document.getElementById('themeIcon');
    var btn=document.getElementById('themeToggle');
    if(icon){
        icon.className = t==='dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
    if(btn){
        btn.style.background = t==='dark' ? 'rgba(91,107,248,.25)' : 'var(--p-soft)';
    }
    // Update meta theme-color
    var meta=document.querySelector('meta[name="theme-color"]');
    if(meta) meta.content = t==='dark' ? '#1A1F32' : '#5B6BF8';
}

function toggleTheme(){
    var cur=document.documentElement.getAttribute('data-theme')||'light';
    applyTheme(cur==='dark'?'light':'dark');
}

// Apply saved theme icon on load
document.addEventListener('DOMContentLoaded',function(){
    var t=localStorage.getItem('bj-theme')||'light';
    applyTheme(t);
});

/* ── Service Worker + Push ── */
@auth
if('serviceWorker' in navigator){
    navigator.serviceWorker.register('/sw.js').then(reg=>{
        // Ask for push permission after SW ready
        if('PushManager' in window && Notification.permission === 'default'){
            setTimeout(()=>requestPushPermission(reg),3000);
        }
    });
}

async function requestPushPermission(reg){
    const perm = await Notification.requestPermission();
    if(perm !== 'granted') return;
    try {
        const sub = await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlB64ToUint8Array('{{ config("app.vapid_public_key","BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuBkr3qBUYIHBQFLXYp5Nksh8U") }}'),
        });
        const key = sub.getKey('p256dh');
        const auth = sub.getKey('auth');
        await fetch('{{ route("push.subscribe") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({
                endpoint: sub.endpoint,
                p256dh: key ? btoa(String.fromCharCode(...new Uint8Array(key))) : null,
                auth_token: auth ? btoa(String.fromCharCode(...new Uint8Array(auth))) : null,
            })
        });
    } catch(e){ console.log('Push subscription failed:',e); }
}

function urlB64ToUint8Array(b){
    const p = (b+'='.repeat((4-b.length%4)%4)).replace(/-/g,'+').replace(/_/g,'/');
    const r = atob(p);
    return Uint8Array.from([...r].map(c=>c.charCodeAt(0)));
}
@endauth

/* ── Onboarding (new user) ── */
@auth @if(!auth()->user()->hasOnboarded())
document.addEventListener('DOMContentLoaded', function(){
    var m = document.getElementById('onboardModal');
    if(m){ var modal = new bootstrap.Modal(m,{backdrop:'static'}); modal.show(); }
});
@endif @endauth
</script>

{{-- Onboarding modal for new users --}}
@auth @if(!auth()->user()->hasOnboarded())
<div class="modal fade" id="onboardModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content" style="border-radius:24px;border:none;overflow:hidden">
            <div id="ob-slides">
                {{-- Slide 1 --}}
                <div class="ob-slide" data-slide="1">
                    <div style="background:linear-gradient(135deg,#5B6BF8,#9B59B6);padding:2.5rem 1.5rem 1.5rem;text-align:center">
                        <div style="font-size:3.5rem">🏘️</div>
                        <h5 style="color:#fff;font-weight:900;margin:.75rem 0 .25rem">Selamat Datang!</h5>
                        <p style="color:rgba(255,255,255,.8);font-size:.875rem">BantuJiran — platform komuniti hiper-lokal untuk jiran bantu jiran.</p>
                    </div>
                    <div style="padding:1.5rem">
                        <div class="d-flex gap-3 mb-3">
                            <div style="width:44px;height:44px;background:#EEF0FF;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem">🆘</div>
                            <div><div style="font-weight:700;font-size:.875rem">Mohon Bantuan</div><div style="font-size:.78rem;color:#9BA3C4">Minta bantuan jiran bila perlu</div></div>
                        </div>
                        <div class="d-flex gap-3 mb-3">
                            <div style="width:44px;height:44px;background:#EEF0FF;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem">🛒</div>
                            <div><div style="font-weight:700;font-size:.875rem">Group Buy & Berkongsi</div><div style="font-size:.78rem;color:#9BA3C4">Jimat kos bersama jiran sekawasan</div></div>
                        </div>
                        <div class="d-flex gap-3 mb-4">
                            <div style="width:44px;height:44px;background:#EEF0FF;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.3rem">🔧</div>
                            <div><div style="font-weight:700;font-size:.875rem">Pinjam Alatan</div><div style="font-size:.78rem;color:#9BA3C4">Kurangkan pembaziran dengan berkongsi</div></div>
                        </div>
                        <button class="btn btn-primary btn-pill w-100" onclick="obNext(2)">
                            Seterusnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
                {{-- Slide 2 --}}
                <div class="ob-slide d-none" data-slide="2">
                    <div style="background:linear-gradient(135deg,#00C8AA,#5B6BF8);padding:2.5rem 1.5rem 1.5rem;text-align:center">
                        <div style="font-size:3.5rem">📍</div>
                        <h5 style="color:#fff;font-weight:900;margin:.75rem 0 .25rem">Tetapkan Lokasi</h5>
                        <p style="color:rgba(255,255,255,.8);font-size:.875rem">Lokasi anda diperlukan untuk melihat dan membantu jiran berdekatan.</p>
                    </div>
                    <div style="padding:1.5rem">
                        <div class="mb-3" style="background:#F0F2FF;border-radius:14px;padding:1rem;font-size:.82rem;color:#4A5068">
                            <i class="bi bi-shield-check-fill me-2" style="color:#5B6BF8"></i>
                            Lokasi anda <strong>tidak dikongsi</strong> secara awam. Hanya digunakan untuk mengira jarak ke pos berdekatan.
                        </div>
                        <button class="btn btn-primary btn-pill w-100 mb-2" onclick="obNext(3)">
                            <i class="bi bi-geo-alt-fill me-1"></i>Faham, Teruskan
                        </button>
                    </div>
                </div>
                {{-- Slide 3 --}}
                <div class="ob-slide d-none" data-slide="3">
                    <div style="background:linear-gradient(135deg,#FF9500,#FF4757);padding:2.5rem 1.5rem 1.5rem;text-align:center">
                        <div style="font-size:3.5rem">🔔</div>
                        <h5 style="color:#fff;font-weight:900;margin:.75rem 0 .25rem">Notifikasi Penting</h5>
                        <p style="color:rgba(255,255,255,.8);font-size:.875rem">Benarkan notifikasi untuk tahu bila ada SOS berdekatan atau respons pada post anda.</p>
                    </div>
                    <div style="padding:1.5rem">
                        <button class="btn btn-primary btn-pill w-100 mb-2" onclick="obFinish(true)">
                            <i class="bi bi-bell-fill me-1"></i>Benarkan Notifikasi
                        </button>
                        <button class="btn btn-ghost btn-pill w-100" onclick="obFinish(false)">
                            Lain kali
                        </button>
                    </div>
                </div>
            </div>
            {{-- Progress dots --}}
            <div style="display:flex;justify-content:center;gap:.5rem;padding:.75rem 0;background:#fff">
                @for($i=1;$i<=3;$i++)
                <div class="ob-dot" data-dot="{{ $i }}" style="width:{{ $i===1?'24':'8' }}px;height:8px;border-radius:99px;background:{{ $i===1?'#5B6BF8':'#E4E7FF' }};transition:.3s"></div>
                @endfor
            </div>
        </div>
    </div>
</div>
<script>
function obNext(n){
    document.querySelectorAll('.ob-slide').forEach(s=>s.classList.add('d-none'));
    document.querySelector('[data-slide="'+n+'"]').classList.remove('d-none');
    document.querySelectorAll('.ob-dot').forEach(d=>{
        const active=+d.dataset.dot===n;
        d.style.width=active?'24px':'8px';
        d.style.background=active?'#5B6BF8':'#E4E7FF';
    });
}
function obFinish(allowPush){
    if(allowPush && 'Notification' in window) Notification.requestPermission();
    fetch('{{ route("profile.onboarded") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
    bootstrap.Modal.getInstance(document.getElementById('onboardModal')).hide();
}
</script>
@endif @endauth

@stack('scripts')
</body>
</html>
