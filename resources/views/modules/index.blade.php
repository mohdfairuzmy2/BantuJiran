@extends('layouts.app')
@section('title','Modul Bantuan')
@section('content')
@php
$mods=[
    ['type'=>'sos',        'tc'=>'sos',    'desc'=>'Hebahkan keperluan anda — jiran berdekatan akan dimaklumkan.'],
    ['type'=>'donate',     'tc'=>'donate', 'desc'=>'Iklan barang tidak digunakan untuk diberi. Jiran lain boleh mohon.'],
    ['type'=>'tool',       'tc'=>'tool',   'desc'=>'Pinjam peralatan jarang guna — elak pembaziran.'],
    ['type'=>'mobility',   'tc'=>'mob',    'desc'=>'Kongsi tumpangan jarak dekat, jimat minyak & masa.'],
    ['type'=>'group_buy',  'tc'=>'buy',    'desc'=>'Iklan perkhidmatan atau barangan jualan kepada jiran sekawasan.'],
];
$user=auth()->user();
$btnClr=['sos'=>'#FF4757','donate'=>'#00C8AA','tool'=>'#9B59B6','mobility'=>'#5B6BF8','group_buy'=>'#FF9500'];
@endphp

{{-- Hero --}}
<div class="bj-hero mb-4">
    <div class="bj-hero-inner" style="background:linear-gradient(135deg,#2D1B69 0%,#5B6BF8 55%,#9B59B6 100%)">
        <div style="position:relative;z-index:1">
            <div class="bj-label" style="color:rgba(255,255,255,.6);margin-bottom:.5rem">
                <i class="bi bi-grid-fill me-1"></i>Modul Bantuan
            </div>
            <div style="font-size:1.3rem;font-weight:900;color:#fff;line-height:1.25;letter-spacing:-.4px">
                5 Cara Untuk<br>Bantu Jiran Anda
            </div>
        </div>
        <div style="position:absolute;right:1.25rem;bottom:1.1rem;z-index:1">
            <div style="width:56px;height:56px;background:rgba(255,255,255,.12);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.6rem">🤝</div>
        </div>
    </div>
</div>

{{-- Modules --}}
<div class="d-flex flex-column gap-2 mb-3">
@foreach($mods as $m)
@php $meta=\App\Models\Post::typeMeta($m['type']); @endphp
<div class="bj-card px-3 py-3 d-flex align-items-center gap-3">
    <div class="mi mi-{{ $m['tc'] }}"><i class="bi {{ $meta['icon'] }}"></i></div>
    <div class="flex-grow-1">
        <div class="fw-bold mb-1" style="font-size:.9rem;color:#1A1D3B">
            {{ $meta['label'] }}
        </div>
        <div style="font-size:.78rem;color:#9BA3C4;line-height:1.4">{{ $m['desc'] }}</div>
    </div>
    <a href="{{ route('posts.create',['type'=>$m['type']]) }}"
       class="mi mi-{{ $m['tc'] }}"
       style="width:38px;height:38px;border-radius:12px;font-size:1rem;text-decoration:none;flex-shrink:0">
        <i class="bi bi-plus-lg"></i>
    </a>
</div>
@endforeach
</div>

@unless($user->is_verified)
<div class="bj-card p-3" style="background:linear-gradient(135deg,#EEF0FF,#F0E8FF);border:2px solid #DDD8FF">
    <div class="d-flex align-items-start gap-3">
        <div class="mi mi-mob" style="width:46px;height:46px;border-radius:15px;font-size:1.1rem;flex-shrink:0">
            <i class="bi bi-fingerprint"></i>
        </div>
        <div>
            <div class="fw-bold mb-1" style="font-size:.875rem;color:#1A1D3B">Tingkatkan Kepercayaan</div>
            <div style="font-size:.78rem;color:#6B7299;margin-bottom:.85rem;line-height:1.4">Sahkan identiti via <strong>MyDigital ID</strong> untuk lencana disahkan & kepercayaan jiran yang lebih tinggi.</div>
            <a href="{{ route('profile.show') }}" class="btn btn-primary btn-pill btn-sm px-3">
                <i class="bi bi-shield-check me-1"></i>Sahkan Sekarang
            </a>
        </div>
    </div>
</div>
@endunless
@endsection
