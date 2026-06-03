@extends('layouts.app')
@section('title','Profil')
@section('content')
@php $lat=$user->home_lat??2.9264; $lng=$user->home_lng??101.6964; @endphp

{{-- Hero --}}
<div class="bj-hero mb-3">
    <div class="bj-hero-inner" style="background:linear-gradient(160deg,#2D1B69,#5B6BF8,#9B59B6);padding-bottom:1.5rem">
        <div style="position:relative;z-index:1;text-align:center">
            {{-- Avatar with upload --}}
            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatarForm">
                @csrf
                <input type="file" name="avatar" id="avatarInput" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit()">
                <div onclick="document.getElementById('avatarInput').click()"
                     style="width:78px;height:78px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;border:3px solid rgba(255,255,255,.3);cursor:pointer;position:relative">
                    @if($user->avatar)
                        <img src="{{ asset('storage/'.$user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="width:100%;height:100%;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-person-fill" style="font-size:2.4rem;color:#fff"></i>
                        </div>
                    @endif
                    <div style="position:absolute;bottom:0;right:0;width:26px;height:26px;background:#5B6BF8;border-radius:50%;border:2px solid #fff;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-camera-fill" style="font-size:.65rem;color:#fff"></i>
                    </div>
                </div>
            </form>
            <div style="font-size:1.2rem;font-weight:900;color:#fff;letter-spacing:-.4px">
                {{ $user->name }}
                @if($user->is_verified)<i class="bi bi-patch-check-fill ms-1" style="color:#A5F3E0;font-size:1rem"></i>@endif
            </div>
            <div style="color:rgba(255,255,255,.65);font-size:.8rem;margin-top:.2rem">+60{{ $user->phone }}</div>
        </div>
    </div>
    {{-- Stats --}}
    <div class="bj-card mx-3 mb-0" style="margin-top:-1px;border-radius:0 0 var(--r) var(--r);border-top:none;box-shadow:none;border-top:1px solid #F0F2FF">
        <div class="d-flex" style="padding:.9rem 0">
            <div class="flex-grow-1 text-center">
                <div style="font-size:1.4rem;font-weight:900;color:#1A1D3B;letter-spacing:-.5px">{{ $user->posts_count }}</div>
                <div class="bj-label">Hebahan</div>
            </div>
            <div style="width:1px;background:#E4E7FF"></div>
            <div class="flex-grow-1 text-center">
                <div style="font-size:1.4rem;font-weight:900;color:#1A1D3B;letter-spacing:-.5px">{{ $user->reviews_count>0?number_format($user->trust_score,1):'—' }}</div>
                <div class="bj-label">Rating</div>
            </div>
            <div style="width:1px;background:#E4E7FF"></div>
            <div class="flex-grow-1 text-center">
                <div style="font-size:1.4rem;font-weight:900;color:#1A1D3B;letter-spacing:-.5px">{{ $user->reviews_count }}</div>
                <div class="bj-label">Ulasan</div>
            </div>
        </div>
    </div>
</div>

{{-- Identity --}}
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-shield-lock-fill me-1" style="color:var(--p)"></i>Tahap Akses</div>
    @if($user->is_verified)
    <div class="d-flex align-items-center gap-3">
        <div class="mi mi-mon" style="width:46px;height:46px;border-radius:15px;font-size:1.1rem"><i class="bi bi-patch-check-fill"></i></div>
        <div>
            <div class="fw-bold" style="font-size:.875rem;color:#1A1D3B">Pengguna Disahkan</div>
            <div style="font-size:.76rem;color:#00C8AA;font-weight:600">Identiti disahkan via MyDigital ID</div>
        </div>
    </div>
    @else
    <div class="d-flex align-items-start gap-3 mb-3">
        <div class="mi mi-buy" style="width:46px;height:46px;border-radius:15px;font-size:1.1rem"><i class="bi bi-shield-exclamation"></i></div>
        <div>
            <div class="fw-bold" style="font-size:.875rem;color:#1A1D3B">Pengguna Asas</div>
            <div style="font-size:.76rem;color:#9BA3C4;line-height:1.4">Sahkan dengan <strong>MyDigital ID</strong> untuk lencana disahkan & kepercayaan jiran yang lebih tinggi.</div>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.verify') }}">@csrf
        <button class="btn btn-primary btn-pill w-100"><i class="bi bi-fingerprint me-2"></i>Sahkan dengan MyDigital ID</button>
    </form>
    @endif
</div>

{{-- Location --}}
<div class="bj-card p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="bj-label"><i class="bi bi-geo-alt-fill me-1" style="color:var(--p)"></i>Lokasi Saya</div>
        <button type="button" id="useMyLoc" class="btn btn-ghost btn-sm btn-pill">
            <i class="bi bi-crosshair me-1"></i>Semasa
        </button>
    </div>
    @unless($user->hasLocation())
    <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-3" style="background:#FFF8E7;font-size:.78rem;color:#9A5000">
        <i class="bi bi-exclamation-triangle-fill" style="color:#FF9500"></i>
        Sila tetapkan lokasi untuk melihat suapan jiran berdekatan.
    </div>
    @endunless
    <div id="profMap" class="bj-map mb-3" style="height:185px"></div>
    <form method="POST" action="{{ route('profile.location') }}">
        @csrf @method('PATCH')
        <input type="hidden" name="home_lat" id="latF" value="{{ $lat }}">
        <input type="hidden" name="home_lng" id="lngF" value="{{ $lng }}">
        <div class="mb-2">
            <label class="form-label">Label Kawasan</label>
            <input type="text" name="address_label" class="form-control" placeholder="Cth: Presint 9, Putrajaya" value="{{ $user->address_label }}">
        </div>
        <button class="btn btn-primary btn-pill w-100"><i class="bi bi-save-fill me-2"></i>Simpan Lokasi</button>
    </form>
</div>

{{-- History --}}
@if($history->isNotEmpty())
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-clock-history me-1" style="color:var(--p)"></i>Sejarah Aktiviti</div>
    @foreach($history as $h)
    @php $hm=\App\Models\Post::typeMeta($h->type); $htc=['sos'=>'sos','group_buy'=>'buy','mobility'=>'mob','tool'=>'tool'][$h->type]??'mob'; @endphp
    <a href="{{ route('posts.show',$h) }}" class="d-flex align-items-center gap-2 pb-2 mb-2 text-decoration-none" style="border-bottom:1px solid #F0F2FF">
        <div class="mi mi-{{ $htc }}" style="width:38px;height:38px;border-radius:12px;font-size:.9rem;flex-shrink:0">
            <i class="bi {{ $hm['icon'] }}"></i>
        </div>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-bold text-truncate" style="font-size:.82rem;color:#1A1D3B">{{ $h->title }}</div>
            <div style="font-size:.72rem;color:#9BA3C4">{{ $h->created_at->format('d M Y') }}</div>
        </div>
        <span style="font-size:.65rem;font-weight:700;background:#F0F2FF;color:#9BA3C4;border-radius:7px;padding:.15rem .5rem;flex-shrink:0">{{ ucfirst($h->status) }}</span>
    </a>
    @endforeach
</div>
@endif

{{-- Reviews --}}
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-star-fill me-1" style="color:#FF9500"></i>Ulasan Diterima</div>
    @forelse($reviews as $rev)
    <div class="pb-3 mb-3" style="border-bottom:1px solid #F0F2FF">
        <div class="d-flex align-items-center gap-2 mb-1">
            @for($s=1;$s<=5;$s++)<i class="bi bi-star-fill" style="font-size:.7rem;color:{{ $s<=$rev->rating?'#FF9500':'#E4E7FF' }}"></i>@endfor
            <strong style="font-size:.82rem;color:#1A1D3B;margin-left:.2rem">{{ $rev->reviewer->name }}</strong>
        </div>
        @if($rev->comment)<p style="font-size:.8rem;color:#9BA3C4;margin:0">&ldquo;{{ $rev->comment }}&rdquo;</p>@endif
    </div>
    @empty
    <div class="bj-empty py-3">
        <div class="bj-empty-icon" style="width:56px;height:56px;font-size:1.4rem"><i class="bi bi-star"></i></div>
        <div style="font-size:.82rem;color:#B8BDD8">Belum ada ulasan.</div>
    </div>
    @endforelse
</div>

@if(auth()->user()->is_admin)
<a href="{{ url('/admin') }}" class="btn btn-primary btn-pill w-100 mb-2" style="background:linear-gradient(135deg,#1A1D3B,#5B6BF8)">
    <i class="bi bi-speedometer2 me-2"></i>Panel Admin
</a>
@endif

<form method="POST" action="{{ route('logout') }}" class="mb-3">@csrf
    <button class="btn btn-outline-danger btn-pill w-100"><i class="bi bi-box-arrow-right me-2"></i>Log Keluar</button>
</form>
@endsection

@push('scripts')
<script>
const pm=L.map('profMap').setView([{{ $lat }},{{ $lng }}],15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(pm);
const mk=L.marker([{{ $lat }},{{ $lng }}],{draggable:true}).addTo(pm);
function sll(la,ln){document.getElementById('latF').value=la.toFixed(7);document.getElementById('lngF').value=ln.toFixed(7);}
mk.on('dragend',e=>{const p=e.target.getLatLng();sll(p.lat,p.lng);});
pm.on('click',e=>{mk.setLatLng(e.latlng);sll(e.latlng.lat,e.latlng.lng);});
document.getElementById('useMyLoc').addEventListener('click',()=>{
    if(!navigator.geolocation)return;
    navigator.geolocation.getCurrentPosition(p=>{pm.setView([p.coords.latitude,p.coords.longitude],16);mk.setLatLng([p.coords.latitude,p.coords.longitude]);sll(p.coords.latitude,p.coords.longitude);});
});
</script>
@endpush
