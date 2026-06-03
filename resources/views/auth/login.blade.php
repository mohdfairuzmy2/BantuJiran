@extends('layouts.app')
@section('title','Log Masuk')
@push('head')
<style>
.bj-scroll,body { background:linear-gradient(160deg,#2D1B69 0%,#5B6BF8 45%,#9B59B6 100%)!important; }
.bj-nav,.container.app-container.pt-2 { display:none!important; }
main { padding-top:0!important; }
/* Dark mode — keep gradient on login */
[data-theme="dark"] .bj-scroll,[data-theme="dark"] body { background:linear-gradient(160deg,#1A0F3D 0%,#3B4ACC 45%,#6B3B9B 100%)!important; }
[data-theme="dark"] .bj-card { background:#1A1F32!important; }
</style>
@endpush
@section('content')
<div class="text-center" style="padding:2.5rem 0 2rem;position:relative;z-index:1">
    <div style="width:84px;height:84px;background:rgba(255,255,255,.15);backdrop-filter:blur(20px);border-radius:28px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;border:2px solid rgba(255,255,255,.2);box-shadow:0 12px 40px rgba(0,0,0,.2)">
        <i class="bi bi-people-fill" style="font-size:2.4rem;color:#fff"></i>
    </div>
    <div style="font-size:1.8rem;font-weight:900;color:#fff;letter-spacing:-.6px;line-height:1">BantuJiran</div>
    <div style="color:rgba(255,255,255,.65);font-size:.875rem;margin-top:.4rem;font-weight:500">Jiran membantu jiran</div>
</div>

<div class="bj-card p-4 mb-3">
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombor Telefon</label>
            <div class="input-group">
                <span class="input-group-text" style="font-size:.95rem">+60</span>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    placeholder="0123456789" value="{{ old('phone') }}" required autofocus
                    style="font-weight:700;font-size:1rem;letter-spacing:.5px">
            </div>
            @error('phone')<div class="mt-1" style="font-size:.76rem;color:#FF4757"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Nama <span style="color:#B8BDD8;text-transform:none;letter-spacing:0;font-size:.72rem;font-weight:500">(untuk pendaftaran baru)</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Nama panggilan anda" value="{{ old('name') }}">
            @error('name')<div class="mt-1" style="font-size:.76rem;color:#FF4757"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-pill w-100">
            <i class="bi bi-arrow-right-circle-fill me-2"></i>Masuk
        </button>
    </form>
</div>

<div class="bj-card p-3" style="background:rgba(255,255,255,.7);backdrop-filter:blur(16px)">
    <div class="d-flex align-items-center gap-3">
        <div class="mi mi-mob" style="width:40px;height:40px;border-radius:13px;font-size:1.05rem;flex-shrink:0">
            <i class="bi bi-patch-check-fill"></i>
        </div>
        <div>
            <div style="font-size:.8rem;font-weight:700;color:#1A1D3B">Pengesahan MyDigital ID</div>
            <div style="font-size:.72rem;color:#6B7299;line-height:1.4;margin-top:.15rem">e-KYC untuk lencana disahkan & kepercayaan jiran yang lebih tinggi. <em>Akan datang.</em></div>
        </div>
    </div>
</div>
@endsection
