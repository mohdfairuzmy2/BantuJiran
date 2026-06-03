@extends('layouts.app')
@section('title','Cipta Hebahan')
@section('content')
@php
$allTypes=['sos','donate','tool','mobility','group_buy'];
$defaultLat=$user->home_lat??2.9264; $defaultLng=$user->home_lng??101.6964;
$tClr=['sos'=>'#FF4757','donate'=>'#00C8AA','tool'=>'#9B59B6','mobility'=>'#5B6BF8','group_buy'=>'#FF9500'];
$tTc=['sos'=>'sos','donate'=>'donate','tool'=>'tool','mobility'=>'mob','group_buy'=>'buy'];
@endphp

<div class="bj-title mb-3"><i class="bi bi-megaphone-fill me-2" style="color:var(--p)"></i>Cipta Hebahan</div>

<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
@csrf
<input type="hidden" name="type" id="typeF" value="{{ $type }}">

{{-- Type selector --}}
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3">Jenis Modul</div>
    <div class="d-flex gap-2 overflow-auto pb-1" style="scrollbar-width:none">
        @foreach($allTypes as $t)
        @php $m=\App\Models\Post::typeMeta($t); @endphp
        <button type="button" class="type-pick d-flex flex-column align-items-center gap-1 flex-shrink-0"
            data-type="{{ $t }}"
            style="border:2px solid {{ $type===$t?$tClr[$t]:'#E4E7FF' }};border-radius:16px;background:{{ $type===$t?$tClr[$t].'12':'#F8F9FF' }};padding:.65rem .9rem;cursor:pointer;transition:all .15s;min-width:74px;text-align:center;outline:none">
            <div class="mi mi-{{ $tTc[$t] }}" style="width:40px;height:40px;border-radius:13px;font-size:1.1rem">
                <i class="bi {{ $m['icon'] }}"></i>
            </div>
            <span style="font-size:.65rem;font-weight:800;color:{{ $type===$t?$tClr[$t]:'#9BA3C4' }};line-height:1.2;margin-top:.2rem">
                {{ explode(' ',$m['label'])[0] }}
            </span>
        </button>
        @endforeach
    </div>
</div>

{{-- Fields --}}
<div class="bj-card p-3 mb-3">
    <div class="mb-3">
        <label class="form-label">Tajuk <span style="color:#FF4757">*</span></label>
        <input type="text" name="title" class="form-control" maxlength="120" value="{{ old('title') }}" placeholder="Cth: Perlukan bantuan segera" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Butiran</label>
        <textarea name="body" class="form-control" rows="3" maxlength="2000" placeholder="Terangkan keperluan anda...">{{ old('body') }}</textarea>
    </div>

    <div class="type-group" data-for="sos">
        <label class="form-label">Tahap Kesegeraan <span style="color:#FF4757">*</span></label>
        <div class="d-flex gap-2">
            @foreach(['low'=>'Rendah','medium'=>'Sederhana','high'=>'Tinggi','critical'=>'Kritikal'] as $v=>$l)
            @php $sc=['low'=>'#00C8AA','medium'=>'#FF9500','high'=>'#FF4757','critical'=>'#9B59B6'][$v]; @endphp
            <label class="flex-grow-1" style="cursor:pointer">
                <input type="radio" name="severity" value="{{ $v }}" class="d-none" {{ $v==='medium'?'checked':'' }}>
                <div class="sev-opt text-center py-2 rounded-3 fw-bold" data-val="{{ $v }}" data-clr="{{ $sc }}"
                     style="border:2px solid #E4E7FF;font-size:.72rem;color:#9BA3C4;transition:all .15s">{{ $l }}</div>
            </label>
            @endforeach
        </div>
    </div>

    <div class="type-group" data-for="donate">
        <div class="row g-2">
            <div class="col-7"><label class="form-label">Nama Barang <span style="color:#FF4757">*</span></label><input type="text" name="meta[item_name]" class="form-control" placeholder="Cth: Almari kayu"></div>
            <div class="col-5"><label class="form-label">Kuantiti</label><input type="number" name="meta[qty]" class="form-control" placeholder="1" min="1"></div>
            <div class="col-12">
                <label class="form-label">Keadaan Barang</label>
                <select name="meta[condition]" class="form-select">
                    <option value="Baru">Baru</option>
                    <option value="Seperti Baru">Seperti Baru</option>
                    <option value="Baik" selected>Baik</option>
                    <option value="Sederhana">Sederhana</option>
                </select>
            </div>
        </div>
    </div>

    <div class="type-group" data-for="group_buy">
        <div class="row g-2">
            <div class="col-7"><label class="form-label">Perkhidmatan / Barangan <span style="color:#FF4757">*</span></label><input type="text" name="meta[item]" class="form-control" placeholder="Cth: Servis aircond"></div>
            <div class="col-5"><label class="form-label">Harga (RM)</label><input type="number" step="0.01" name="price" class="form-control" placeholder="0.00"></div>
            <div class="col-12"><label class="form-label">Maklumat Tambahan</label><input type="text" name="meta[deadline]" class="form-control" placeholder="Cth: Tempahan sebelum 6pm"></div>
        </div>
    </div>

    <div class="type-group" data-for="mobility">
        <div class="row g-2">
            <div class="col-6"><label class="form-label">Dari <span style="color:#FF4757">*</span></label><input type="text" name="meta[origin]" class="form-control" placeholder="Presint 9"></div>
            <div class="col-6"><label class="form-label">Ke <span style="color:#FF4757">*</span></label><input type="text" name="meta[destination]" class="form-control" placeholder="Stesen MRT"></div>
            <div class="col-6"><label class="form-label">Masa Bertolak</label><input type="text" name="meta[depart_time]" class="form-control" placeholder="8:00 pagi"></div>
            <div class="col-6"><label class="form-label">Tempat Kosong</label><input type="number" name="meta[seats]" class="form-control" placeholder="3" min="1" max="8"></div>
        </div>
    </div>

    <div class="type-group" data-for="tool">
        <div class="row g-2">
            <div class="col-7"><label class="form-label">Nama Peralatan <span style="color:#FF4757">*</span></label><input type="text" name="meta[tool_name]" class="form-control" placeholder="Mesin gerudi"></div>
            <div class="col-5"><label class="form-label">Tersedia Hingga</label><input type="text" name="meta[available_until]" class="form-control" placeholder="Esok"></div>
        </div>
    </div>

</div>

{{-- Location --}}
<div class="bj-card p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="bj-label"><i class="bi bi-geo-alt-fill me-1" style="color:var(--p)"></i>Lokasi Hebahan</div>
        <button type="button" id="useMyLoc" class="btn btn-ghost btn-sm btn-pill">
            <i class="bi bi-crosshair me-1"></i>Semasa
        </button>
    </div>
    <div id="pickMap" class="bj-map" style="height:210px"></div>
    <input type="hidden" name="lat" id="latF" value="{{ old('lat',$defaultLat) }}">
    <input type="hidden" name="lng" id="lngF" value="{{ old('lng',$defaultLng) }}">
    <div class="mt-3 d-flex align-items-center gap-2">
        <span class="bj-label mb-0">Radius</span>
        <input type="range" name="radius_km" min="1" max="20" value="{{ old('radius_km',3) }}" class="form-range flex-grow-1"
            oninput="document.getElementById('rkm').textContent=this.value" style="accent-color:var(--p)">
        <span style="background:var(--p-soft);color:var(--p);font-size:.7rem;font-weight:800;border-radius:8px;padding:.2rem .55rem;white-space:nowrap">
            <span id="rkm">3</span> km
        </span>
    </div>
</div>

{{-- Image upload --}}
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-2"><i class="bi bi-image-fill me-1" style="color:var(--p)"></i>Gambar <span style="color:var(--tm);text-transform:none;letter-spacing:0;font-weight:500">(pilihan, maks 3)</span></div>
    <input type="file" name="images[]" id="imgInput" accept="image/*" multiple class="d-none" onchange="previewImages(this)">
    <div id="imgPreviews" class="d-flex gap-2 flex-wrap mb-2"></div>
    <button type="button" onclick="document.getElementById('imgInput').click()"
        class="btn btn-ghost btn-sm btn-pill">
        <i class="bi bi-camera-fill me-1"></i>Pilih Gambar
    </button>
</div>

<button type="submit" class="btn btn-primary btn-pill btn-lg w-100 mb-3">
    <i class="bi bi-megaphone-fill me-2"></i>Siarkan Hebahan
</button>
</form>
@endsection

@push('scripts')
<script>
const TC=@json($tClr),TT=@json($tTc);
function refreshType(t){
    document.getElementById('typeF').value=t;
    document.querySelectorAll('.type-group').forEach(g=>g.style.display=g.dataset.for===t?'':'none');
    document.querySelectorAll('.type-pick').forEach(b=>{
        const on=b.dataset.type===t;
        b.style.borderColor=on?(TC[t]||'var(--p)'):'#E4E7FF';
        b.style.background=on?((TC[t]||'#5B6BF8')+'12'):'#F8F9FF';
        b.querySelector('span').style.color=on?(TC[t]||'var(--p)'):'#9BA3C4';
    });
}
document.querySelectorAll('.type-pick').forEach(b=>b.addEventListener('click',()=>!b.disabled&&refreshType(b.dataset.type)));
refreshType(@json($type));

document.querySelectorAll('.sev-opt').forEach(el=>{
    el.addEventListener('click',function(){
        document.querySelectorAll('.sev-opt').forEach(x=>{x.style.borderColor='#E4E7FF';x.style.background='#F8F9FF';x.style.color='#9BA3C4';});
        this.style.borderColor=this.dataset.clr; this.style.background=this.dataset.clr+'18'; this.style.color=this.dataset.clr;
        this.closest('label').querySelector('input[type=radio]').checked=true;
    });
});
document.querySelector('.sev-opt[data-val="medium"]')?.click();

const pm=L.map('pickMap').setView([{{ $defaultLat }},{{ $defaultLng }}],15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(pm);
const mk=L.marker([{{ $defaultLat }},{{ $defaultLng }}],{draggable:true}).addTo(pm);
function sll(la,ln){document.getElementById('latF').value=la.toFixed(7);document.getElementById('lngF').value=ln.toFixed(7);}
mk.on('dragend',e=>{const p=e.target.getLatLng();sll(p.lat,p.lng);});
pm.on('click',e=>{mk.setLatLng(e.latlng);sll(e.latlng.lat,e.latlng.lng);});
function previewImages(input){
    const prev=document.getElementById('imgPreviews');
    prev.innerHTML='';
    const files=Array.from(input.files).slice(0,3);
    files.forEach(f=>{
        const url=URL.createObjectURL(f);
        const div=document.createElement('div');
        div.style.cssText='width:72px;height:72px;border-radius:12px;overflow:hidden;flex-shrink:0';
        div.innerHTML=`<img src="${url}" style="width:100%;height:100%;object-fit:cover">`;
        prev.appendChild(div);
    });
}
document.getElementById('useMyLoc').addEventListener('click',()=>{
    if(!navigator.geolocation)return;
    navigator.geolocation.getCurrentPosition(p=>{pm.setView([p.coords.latitude,p.coords.longitude],16);mk.setLatLng([p.coords.latitude,p.coords.longitude]);sll(p.coords.latitude,p.coords.longitude);});
});
</script>
@endpush
