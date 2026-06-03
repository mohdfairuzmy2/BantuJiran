@extends('layouts.app')
@section('title','Suapan Jiran')

@push('head')
<style>
#ptr-wrap { position:relative; }
#ptr-ind {
    position:absolute; top:-58px; left:50%; transform:translateX(-50%);
    width:44px; height:44px; background:#fff; border-radius:50%;
    box-shadow:0 4px 20px rgba(91,107,248,.2);
    display:flex; align-items:center; justify-content:center;
    color:var(--p); font-size:1.1rem; opacity:0; z-index:99; pointer-events:none;
    transition:opacity .2s;
}
#ptr-ind.vis { opacity:1; }
#ptr-ind.spin i { animation:ptrspin .55s linear infinite; }
@keyframes ptrspin { to{transform:rotate(360deg)} }
</style>
@endpush

@section('content')
@php
$types=[
    ''=>['label'=>'Semua','icon'=>'bi-grid-fill'],
    'sos'=>\App\Models\Post::typeMeta('sos'),
    'donate'=>\App\Models\Post::typeMeta('donate'),
    'tool'=>\App\Models\Post::typeMeta('tool'),
    'mobility'=>\App\Models\Post::typeMeta('mobility'),
    'group_buy'=>\App\Models\Post::typeMeta('group_buy'),
];
$chipClr=[''=>'var(--p)','sos'=>'#FF4757','donate'=>'#00C8AA','tool'=>'#9B59B6','mobility'=>'#5B6BF8','group_buy'=>'#FF9500'];
@endphp

{{-- Hero --}}
<div class="bj-hero mb-3">
    <div class="bj-hero-inner">
        <div style="position:relative;z-index:1">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:rgba(255,255,255,.65);margin-bottom:.5rem">
                <i class="bi bi-geo-alt-fill me-1"></i>{{ auth()->user()->address_label ?? 'Kawasan Anda' }}
            </div>
            <div style="font-size:2.6rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-1px">{{ $posts->count() }}</div>
            <div style="font-size:.82rem;color:rgba(255,255,255,.75);font-weight:500;margin-top:.2rem">Hebahan aktif berdekatan</div>
        </div>
        <div style="position:absolute;top:1.1rem;right:1.1rem;z-index:1;display:flex;gap:.5rem">
            <button id="btnL" onclick="sv('list')" style="width:36px;height:36px;background:rgba(255,255,255,.25);border:none;border-radius:11px;color:#fff;display:flex;align-items:center;justify-content:center;transition:.15s">
                <i class="bi bi-list-ul" style="font-size:1rem"></i>
            </button>
            <button id="btnM" onclick="sv('map')" style="width:36px;height:36px;background:rgba(255,255,255,.12);border:none;border-radius:11px;color:rgba(255,255,255,.7);display:flex;align-items:center;justify-content:center;transition:.15s">
                <i class="bi bi-map" style="font-size:1rem"></i>
            </button>
        </div>
    </div>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('feed.index') }}" id="fForm" class="mb-3">
    <input type="hidden" name="type" id="tInput" value="{{ $activeType }}">
    <div class="d-flex gap-2 overflow-auto pb-1 mb-2" style="scrollbar-width:none">
        @foreach($types as $k=>$m)
        <button type="button" class="bj-chip {{ ($activeType??'')===$k?'active':'' }}"
            data-type="{{ $k }}"
            style="{{ ($activeType??'')===$k?'background:'.$chipClr[$k].';border-color:'.$chipClr[$k].';color:#fff;box-shadow:0 3px 10px '.$chipClr[$k].'55':'' }}">
            <i class="bi {{ $m['icon'] }}"></i>{{ $m['label'] }}
        </button>
        @endforeach
    </div>
    {{-- Search --}}
    <div class="input-group mb-2">
        <span class="input-group-text"><i class="bi bi-search" style="font-size:.85rem"></i></span>
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari hebahan...">
    </div>

    {{-- Sort + Radius --}}
    <div class="bj-card px-3 py-2 d-flex align-items-center gap-2 flex-wrap">
        <select name="sort" class="form-select form-select-sm" style="width:auto;border-radius:9px;font-size:.75rem;font-weight:700" onchange="this.form.submit()">
            <option value="nearest" {{ ($sort??'nearest')==='nearest'?'selected':'' }}>📍 Terdekat</option>
            <option value="latest"  {{ ($sort??'')==='latest'?'selected':'' }}>🕐 Terbaru</option>
            <option value="popular" {{ ($sort??'')==='popular'?'selected':'' }}>🔥 Popular</option>
        </select>
        <i class="bi bi-broadcast-pin" style="color:var(--p);font-size:.9rem;flex-shrink:0"></i>
        <input type="range" name="radius" min="1" max="20" value="{{ $radius }}"
            class="form-range flex-grow-1" oninput="document.getElementById('rVal').textContent=this.value"
            style="accent-color:var(--p)">
        <span style="background:var(--p-soft);color:var(--p);font-size:.7rem;font-weight:800;border-radius:8px;padding:.2rem .55rem;white-space:nowrap">
            <span id="rVal">{{ (int)$radius }}</span> km
        </span>
        <button class="btn btn-primary btn-sm" type="submit" style="padding:.28rem .6rem;border-radius:9px;flex-shrink:0">
            <i class="bi bi-search"></i>
        </button>
    </div>
</form>

{{-- Map --}}
<div id="mapView" class="d-none mb-3">
    <div id="map" class="bj-map" style="height:52vh"></div>
</div>

{{-- PTR + List --}}
<div id="ptr-wrap">
    <div id="ptr-ind"><i class="bi bi-arrow-clockwise"></i></div>
    <div id="listView">
        @forelse($posts as $post)
            @include('posts._card',['post'=>$post])
        @empty
        <div class="bj-card">
            <div class="bj-empty">
                <div class="bj-empty-icon"><i class="bi bi-compass"></i></div>
                <div class="bj-title mb-1" style="font-size:.95rem">Tiada hebahan berdekatan</div>
                <div class="bj-sub mb-3">Tiada dalam radius {{ (int)$radius }} km. Jadilah yang pertama!</div>
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i>Cipta Hebahan
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
const mapPts=@json($mapPoints),center=@json($center);
const tClr={sos:'#FF4757',donate:'#00C8AA',tool:'#9B59B6',mobility:'#5B6BF8',group_buy:'#FF9500'};
let map=null;

function sv(v){
    const isM=v==='map';
    document.getElementById('mapView').classList.toggle('d-none',!isM);
    document.getElementById('listView').classList.toggle('d-none',isM);
    document.getElementById('btnM').style.background=isM?'rgba(255,255,255,.32)':'rgba(255,255,255,.12)';
    document.getElementById('btnM').style.color=isM?'#fff':'rgba(255,255,255,.7)';
    document.getElementById('btnL').style.background=!isM?'rgba(255,255,255,.32)':'rgba(255,255,255,.12)';
    document.getElementById('btnL').style.color=!isM?'#fff':'rgba(255,255,255,.7)';
    if(isM) setTimeout(()=>{
        if(!map){
            map=L.map('map').setView([center.lat,center.lng],14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(map);
            L.circleMarker([center.lat,center.lng],{radius:8,color:'#fff',weight:3,fillColor:'#5B6BF8',fillOpacity:1}).addTo(map).bindPopup('<strong>Lokasi anda</strong>');
            mapPts.forEach(p=>{
                L.circleMarker([p.lat,p.lng],{radius:11,color:'#fff',weight:2.5,fillColor:tClr[p.type]||'#9BA3C4',fillOpacity:.92}).addTo(map)
                 .bindPopup(`<strong>${p.title}</strong><br><small>${p.label}</small><br><a href="${p.url}" style="color:#5B6BF8;font-weight:700">Lihat →</a>`);
            });
        } else { map.invalidateSize(); }
    },60);
}
sv('list');

document.querySelectorAll('.bj-chip').forEach(c=>{
    c.addEventListener('click',()=>{
        document.getElementById('tInput').value=c.dataset.type;
        document.getElementById('fForm').submit();
    });
});

/* SOS countdown timers */
function updateCountdowns(){
    document.querySelectorAll('.sos-countdown').forEach(el=>{
        const diff=parseInt(el.dataset.expires)*1000-Date.now();
        if(diff<=0){el.textContent='Tamat';return;}
        const h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000);
        el.textContent='Tamat dalam '+h+'j '+m+'m';
    });
}
updateCountdowns(); setInterval(updateCountdowns,60000);

/* Pull-to-refresh */
(function(){
    const scroller=document.getElementById('bjScroll')||window;
    const ind=document.getElementById('ptr-ind');
    const THRESH=80, MAX=110;
    let startY=0,pulling=false,released=false;
    function getTop(){ return scroller===window?window.scrollY:scroller.scrollTop; }
    function onTS(e){ if(getTop()>2)return; startY=e.touches[0].clientY; pulling=true; released=false; }
    function onTM(e){
        if(!pulling||released)return;
        const dy=Math.min(e.touches[0].clientY-startY,MAX);
        if(dy<=0){pulling=false;return;}
        e.preventDefault();
        const prog=Math.min(dy/THRESH,1);
        ind.style.top=(dy*.55-58)+'px'; ind.style.opacity=prog;
        ind.classList.toggle('vis',dy>18);
        ind.querySelector('i').style.transform=`rotate(${prog*240}deg)`;
    }
    function onTE(){
        if(!pulling||released)return; released=true; pulling=false;
        const top=parseInt(ind.style.top)||-58;
        if(top>=-15){ ind.classList.add('spin'); ind.style.top='12px'; ind.style.opacity='1'; setTimeout(()=>location.reload(),600); }
        else{ ind.style.transition='top .3s,opacity .3s'; ind.style.top='-58px'; ind.style.opacity='0'; setTimeout(()=>ind.style.transition='',320); }
    }
    const t=scroller===window?document:scroller;
    t.addEventListener('touchstart',onTS,{passive:true});
    t.addEventListener('touchmove',onTM,{passive:false});
    t.addEventListener('touchend',onTE,{passive:true});
    let wt=null;
    t.addEventListener('wheel',function(e){
        if(getTop()>2||e.deltaY>=0)return;
        clearTimeout(wt); wt=setTimeout(()=>{ ind.classList.add('vis','spin'); ind.style.top='12px'; ind.style.opacity='1'; setTimeout(()=>location.reload(),500); },150);
    },{passive:true});
})();
</script>
@endpush
