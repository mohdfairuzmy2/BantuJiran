<!DOCTYPE html>
<html lang="ms" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin · @yield('title','BantuJiran')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
body{font-family:'Inter',sans-serif;background:#f8f9fa}
.sidebar{width:220px;min-height:100vh;background:linear-gradient(160deg,#1A1D3B,#5B6BF8);position:fixed;top:0;left:0;padding:1.5rem 1rem}
.sidebar .brand{color:#fff;font-weight:900;font-size:1.1rem;margin-bottom:2rem;display:block;text-decoration:none}
.sidebar a{color:rgba(255,255,255,.7);display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:600;margin-bottom:.25rem;transition:.15s}
.sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,.15);color:#fff}
.main{margin-left:220px;padding:2rem}
.stat-card{background:#fff;border-radius:16px;padding:1.25rem;box-shadow:0 2px 12px rgba(91,107,248,.08)}
.stat-num{font-size:2rem;font-weight:900;color:#1A1D3B}
.stat-lbl{font-size:.75rem;font-weight:700;color:#9BA3C4;text-transform:uppercase;letter-spacing:.5px}
</style>
</head>
<body>
<div class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand"><i class="bi bi-people-fill me-2"></i>BJ Admin</a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users')?'active':'' }}"><i class="bi bi-people"></i>Pengguna</a>
    <a href="{{ route('admin.posts') }}" class="{{ request()->routeIs('admin.posts')?'active':'' }}"><i class="bi bi-megaphone"></i>Post</a>
    <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports')?'active':'' }}"><i class="bi bi-flag"></i>Laporan</a>
    <hr style="border-color:rgba(255,255,255,.2);margin:1rem 0">
    <a href="{{ route('feed.index') }}"><i class="bi bi-arrow-left"></i>Balik ke App</a>
</div>
<div class="main">
    @if(session('status'))<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
