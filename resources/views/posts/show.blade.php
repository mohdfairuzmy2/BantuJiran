@extends('layouts.app')
@section('title',$post->title)
@section('content')
@php
$meta=$post->meta();
$m=$post->meta??[];
$tclr=['sos'=>'#FF4757','donate'=>'#00C8AA','tool'=>'#9B59B6','mobility'=>'#5B6BF8','group_buy'=>'#FF9500'][$post->type]??'#5B6BF8';
$ttc=['sos'=>'sos','donate'=>'donate','tool'=>'tool','mobility'=>'mob','group_buy'=>'buy'][$post->type]??'mob';
$sc=['open'=>'#00C8AA','in_progress'=>'#FF9500','completed'=>'#9B59B6','closed'=>'#9BA3C4'][$post->status]??'#9BA3C4';
$sl=['open'=>'Dibuka','in_progress'=>'Berjalan','completed'=>'Selesai','closed'=>'Ditutup'][$post->status]??ucfirst($post->status);
$kl=['help_offer'=>'Tawar Bantuan','group_buy_join'=>'Saya Berminat','carpool_request'=>'Mohon Tumpangan','borrow_request'=>'Mohon Pinjam','item_request'=>'Mohon Barang'];
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <a href="{{ url()->previous() }}" class="d-inline-flex align-items-center gap-1 text-decoration-none" style="color:#9BA3C4;font-size:.82rem;font-weight:700">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <div class="d-flex gap-2">
        <button onclick="sharePost()" class="btn btn-ghost btn-sm btn-pill" title="Kongsi" aria-label="Kongsi post">
            <i class="bi bi-share-fill" style="font-size:.85rem"></i>
        </button>
        @if(!$isOwner)
        <button onclick="document.getElementById('reportModal').classList.toggle('d-none')" class="btn btn-sm btn-pill" style="background:#FFF0F1;color:#FF4757;font-weight:700" title="Laporkan" aria-label="Laporkan post">
            <i class="bi bi-flag-fill" style="font-size:.85rem"></i>
        </button>
        @endif
    </div>
</div>

{{-- Report form --}}
@if(!$isOwner)
<div id="reportModal" class="bj-card p-3 mb-3 d-none">
    <div class="bj-label mb-2"><i class="bi bi-flag-fill me-1" style="color:#FF4757"></i>Laporkan Post</div>
    @if($hasReported)
        <div style="font-size:.82rem;color:#00C8AA"><i class="bi bi-check-circle-fill me-1"></i>Anda telah melaporkan post ini. Sedang disemak.</div>
    @else
    <form method="POST" action="{{ route('posts.report',$post) }}">
        @csrf
        <select name="reason" class="form-select mb-2" required>
            <option value="">-- Pilih sebab --</option>
            <option>Kandungan palsu/spam</option>
            <option>Bahasa kesat/menyinggung</option>
            <option>Penipuan</option>
            <option>Kandungan berbahaya</option>
            <option>Lain-lain</option>
        </select>
        <textarea name="notes" class="form-control mb-2" rows="2" placeholder="Maklumat tambahan (pilihan)..."></textarea>
        <button class="btn btn-sm w-100" style="background:#FF4757;color:#fff;border-radius:12px;font-weight:700">
            <i class="bi bi-send-fill me-1"></i>Hantar Laporan
        </button>
    </form>
    @endif
</div>
@endif

{{-- Hero --}}
<div class="bj-hero mb-3">
    <div class="bj-hero-inner" style="background:linear-gradient(135deg,{{ $tclr }},{{ $tclr }}99)">
        <div style="position:relative;z-index:1;display:flex;align-items:flex-start;gap:1rem">
            <div class="mi mi-{{ $ttc }}" style="width:52px;height:52px;border-radius:17px;font-size:1.3rem;background:rgba(255,255,255,.2);flex-shrink:0">
                <i class="bi {{ $meta['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span style="font-size:.65rem;font-weight:800;letter-spacing:.5px;text-transform:uppercase;background:rgba(255,255,255,.2);color:#fff;border-radius:8px;padding:.2rem .55rem">{{ $meta['label'] }}</span>
                    @if($post->type==='sos'&&$post->severity)
                        <span style="font-size:.62rem;font-weight:800;background:rgba(0,0,0,.2);color:#fff;border-radius:6px;padding:.2rem .5rem">{{ strtoupper($post->severity) }}</span>
                    @endif
                    <span style="margin-left:auto;font-size:.65rem;font-weight:700;background:rgba(255,255,255,.2);color:#fff;border-radius:8px;padding:.2rem .55rem">{{ $sl }}</span>
                </div>
                <div style="font-size:1.05rem;font-weight:900;color:#fff;line-height:1.3;letter-spacing:-.3px">{{ $post->title }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Body + details --}}
<div class="bj-card p-3 mb-3">
    @if($post->body)<p style="font-size:.875rem;color:#4A5068;line-height:1.6;margin-bottom:1rem">{{ $post->body }}</p>@endif

    @if($post->type!=='sos')
    <div style="border-top:1.5px solid #F0F2FF;padding-top:.75rem">
        @if($post->type==='group_buy')
            @if($post->price)<div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Harga</span><strong style="color:#1A1D3B">RM {{ number_format($post->price,2) }}</strong></div>@endif
            @if(!empty($m['item']))<div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Item</span><span style="color:#1A1D3B">{{ $m['item'] }}</span></div>@endif
            @if(!empty($m['target_qty']))<div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Sasaran</span><span style="color:#1A1D3B">{{ $m['target_qty'] }} unit</span></div>@endif
            @if(!empty($m['deadline']))<div class="d-flex justify-content-between py-2" style="font-size:.875rem"><span style="color:#9BA3C4">Tutup</span><span style="color:#1A1D3B">{{ $m['deadline'] }}</span></div>@endif
        @elseif($post->type==='mobility')
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Dari</span><span style="color:#1A1D3B">{{ $m['origin']??'-' }}</span></div>
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Ke</span><span style="color:#1A1D3B">{{ $m['destination']??'-' }}</span></div>
            @if(!empty($m['depart_time']))<div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Bertolak</span><span style="color:#1A1D3B">{{ $m['depart_time'] }}</span></div>@endif
            @if(!empty($m['seats']))<div class="d-flex justify-content-between py-2" style="font-size:.875rem"><span style="color:#9BA3C4">Tempat</span><span style="color:#1A1D3B">{{ $m['seats'] }}</span></div>@endif
        @elseif($post->type==='tool')
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Peralatan</span><span style="color:#1A1D3B">{{ $m['tool_name']??'-' }}</span></div>
            @if(!empty($m['available_until']))<div class="d-flex justify-content-between py-2" style="font-size:.875rem"><span style="color:#9BA3C4">Tersedia hingga</span><span style="color:#1A1D3B">{{ $m['available_until'] }}</span></div>@endif
        @elseif($post->type==='donate')
            <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Barang</span><span style="color:#1A1D3B">{{ $m['item_name']??'-' }}</span></div>
            @if(!empty($m['condition']))<div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #F8F9FF;font-size:.875rem"><span style="color:#9BA3C4">Keadaan</span><span style="color:#1A1D3B">{{ $m['condition'] }}</span></div>@endif
            @if(!empty($m['qty']))<div class="d-flex justify-content-between py-2" style="font-size:.875rem"><span style="color:#9BA3C4">Kuantiti</span><span style="color:#1A1D3B">{{ $m['qty'] }}</span></div>@endif
        @endif
    </div>
    @endif

    {{-- Image gallery --}}
    @if(!empty($post->images))
    <div class="d-flex gap-2 flex-wrap mt-3 pt-3" style="border-top:1.5px solid #F0F2FF">
        @foreach($post->images as $img)
        <a href="{{ asset('storage/'.$img) }}" target="_blank" style="width:80px;height:80px;border-radius:12px;overflow:hidden;display:block">
            <img src="{{ asset('storage/'.$img) }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
        </a>
        @endforeach
    </div>
    @endif

    @if($distanceLabel??null)
    <div class="mt-2 d-flex align-items-center gap-1" style="font-size:.76rem;color:#B8BDD8">
        <i class="bi bi-geo-alt-fill" style="color:var(--p)"></i>{{ $distanceLabel }}
    </div>
    @endif
</div>

{{-- Poster --}}
<div class="bj-card p-3 mb-3 d-flex align-items-center gap-3">
    <div class="mi mi-mob" style="width:48px;height:48px;border-radius:50%;font-size:1.2rem;flex-shrink:0"><i class="bi bi-person-fill"></i></div>
    <div class="flex-grow-1">
        <div class="fw-bold" style="font-size:.9rem;color:#1A1D3B">
            {{ $post->user->name }}
            @if($post->user->is_verified)<i class="bi bi-patch-check-fill ms-1" style="color:var(--p);font-size:.85rem"></i>@endif
        </div>
        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
            <span style="background:#FFF3CD;color:#9A5000;font-size:.68rem;font-weight:700;border-radius:7px;padding:.15rem .5rem">
                <i class="bi bi-shield-check me-1"></i>{{ $post->user->trustBadge() }}
            </span>
            @if($post->user->reviews_count>0)
            <span style="font-size:.75rem;color:#9BA3C4">
                <i class="bi bi-star-fill" style="color:#FF9500"></i> {{ number_format($post->user->trust_score,1) }} ({{ $post->user->reviews_count }})
            </span>
            @endif
        </div>
    </div>
</div>

{{-- Actions --}}
@if(!$isOwner)
<div class="bj-card p-3 mb-3">
    @if($myResponse)
    <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-3" style="background:#E6FEF5">
        <i class="bi bi-check-circle-fill" style="color:#00C8AA;font-size:1.1rem"></i>
        <div>
            <div class="fw-bold" style="font-size:.8rem;color:#004D3A">Anda telah membalas</div>
            <div style="font-size:.74rem;color:#6B7299">{{ $kl[$myResponse->kind]??$myResponse->kind }} · {{ ucfirst($myResponse->status) }}</div>
        </div>
    </div>
    @endif
    <form method="POST" action="{{ route('responses.store',$post) }}" class="mb-2">
        @csrf
        <input type="hidden" name="kind" value="{{ $responseKind }}">
        @if($post->type==='group_buy')
        <div class="mb-2"><label class="form-label">Kuantiti</label><input type="number" name="qty" class="form-control" value="1" min="1" max="99"></div>
        @endif
        <textarea name="message" class="form-control mb-2" rows="2" placeholder="Tulis mesej (pilihan)..."></textarea>
        <button class="btn btn-primary btn-pill w-100"><i class="bi bi-hand-thumbs-up-fill me-2"></i>{{ $kl[$responseKind]??'Tawar Bantuan' }}</button>
    </form>
    <form method="POST" action="{{ route('chat.start',$post) }}">
        @csrf
        <button class="btn btn-ghost btn-pill w-100"><i class="bi bi-chat-dots-fill me-2"></i>Mula Sembang</button>
    </form>
</div>
@else
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-people-fill me-1" style="color:var(--p)"></i>Maklum Balas
        <span style="background:var(--p-soft);color:var(--p);font-size:.65rem;font-weight:800;border-radius:7px;padding:.15rem .5rem;margin-left:.3rem">{{ $post->responses->count() }}</span>
    </div>
    @forelse($post->responses as $resp)
    <div class="d-flex align-items-center gap-2 py-2" style="border-bottom:1px solid #F0F2FF">
        <div class="mi mi-mob" style="width:36px;height:36px;border-radius:50%;font-size:.95rem;flex-shrink:0"><i class="bi bi-person-fill"></i></div>
        <div class="flex-grow-1">
            <div class="fw-bold" style="font-size:.82rem;color:#1A1D3B">{{ $resp->user->name }}</div>
            @if($resp->message)<div style="font-size:.76rem;color:#9BA3C4">{{ $resp->message }}</div>@endif
        </div>
        <span style="font-size:.65rem;font-weight:700;background:{{ ['pending'=>'#FFF8E7','accepted'=>'#E6FEF5'][$resp->status]??'#F0F2FF' }};color:{{ ['pending'=>'#9A5000','accepted'=>'#004D3A'][$resp->status]??'#9BA3C4' }};border-radius:7px;padding:.2rem .5rem">{{ ucfirst($resp->status) }}</span>
        @if($resp->status==='pending')
        <form method="POST" action="{{ route('responses.accept',$resp) }}">@csrf @method('PATCH')
            <button class="btn btn-sm" style="background:#E6FEF5;color:#00C8AA;border:none;border-radius:9px;padding:.3rem .55rem"><i class="bi bi-check-lg"></i></button>
        </form>
        <form method="POST" action="{{ route('responses.decline',$resp) }}">@csrf @method('PATCH')
            <button class="btn btn-sm" style="background:#FFF0F1;color:#FF4757;border:none;border-radius:9px;padding:.3rem .55rem"><i class="bi bi-x-lg"></i></button>
        </form>
        @endif
    </div>
    @empty
    <div class="text-center py-3" style="color:#B8BDD8;font-size:.82rem"><i class="bi bi-inbox d-block mb-1" style="font-size:1.4rem"></i>Belum ada maklum balas.</div>
    @endforelse
</div>

<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-gear-fill me-1" style="color:var(--p)"></i>Urus Status</div>
    <form method="POST" action="{{ route('posts.status',$post) }}" class="d-flex gap-2">
        @csrf @method('PATCH')
        <select name="status" class="form-select">
            @foreach(['open'=>'Dibuka','in_progress'=>'Sedang berjalan','completed'=>'Selesai','closed'=>'Ditutup'] as $v=>$l)
            <option value="{{ $v }}" @selected($post->status===$v)>{{ $l }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary px-3" style="flex-shrink:0">Simpan</button>
    </form>
</div>
@endif

{{-- Review --}}
@if(($isOwner&&$post->helper_id)||(! $isOwner&&$post->helper_id===auth()->id()))
@if(in_array($post->status,['in_progress','completed']))
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-star-fill me-1" style="color:#FF9500"></i>Beri Ulasan</div>
    @if($hasReviewed)
    <div class="d-flex align-items-center gap-2" style="color:#00C8AA;font-size:.875rem"><i class="bi bi-check-circle-fill"></i>Ulasan telah dihantar. Terima kasih!</div>
    @else
    <form method="POST" action="{{ route('reviews.store',$post) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Penilaian</label>
            <div class="d-flex gap-2" id="stars">
                @for($i=1;$i<=5;$i++)<button type="button" class="star-b" data-v="{{ $i }}" style="background:none;border:none;font-size:2rem;color:#E4E7FF;cursor:pointer;padding:0;transition:color .1s"><i class="bi bi-star-fill"></i></button>@endfor
            </div>
            <input type="hidden" name="rating" id="rV" value="5">
        </div>
        <div class="mb-3"><label class="form-label">Komen (Pilihan)</label><textarea name="comment" class="form-control" rows="2" placeholder="Kongsikan pengalaman..."></textarea></div>
        <button class="btn btn-warning btn-pill w-100"><i class="bi bi-send-fill me-2"></i>Hantar Ulasan</button>
    </form>
    <script>
    let rv=5;
    function us(v){document.querySelectorAll('.star-b').forEach((b,i)=>b.style.color=i<v?'#FF9500':'#E4E7FF');}
    us(5);
    document.querySelectorAll('.star-b').forEach(b=>b.addEventListener('click',()=>{rv=+b.dataset.v;document.getElementById('rV').value=rv;us(rv);}));
    </script>
    @endif
</div>
@endif
@endif

{{-- Reviews list --}}
@if($post->reviews->isNotEmpty())
<div class="bj-card p-3 mb-3">
    <div class="bj-label mb-3"><i class="bi bi-chat-quote-fill me-1" style="color:var(--p)"></i>Ulasan</div>
    @foreach($post->reviews as $rev)
    <div class="pb-3 mb-3" style="border-bottom:1px solid #F0F2FF">
        <div class="d-flex align-items-center gap-2 mb-1">
            @for($s=1;$s<=5;$s++)<i class="bi bi-star-fill" style="font-size:.7rem;color:{{ $s<=$rev->rating?'#FF9500':'#E4E7FF' }}"></i>@endfor
            <strong style="font-size:.82rem;color:#1A1D3B;margin-left:.2rem">{{ $rev->reviewer->name }}</strong>
        </div>
        @if($rev->comment)<p style="font-size:.8rem;color:#9BA3C4;margin:0">&ldquo;{{ $rev->comment }}&rdquo;</p>@endif
    </div>
    @endforeach
</div>
@endif
@endsection


@push('scripts')
<script>
function sharePost(){
    const title = @json($post->title);
    const url   = @json(route('posts.show',$post));
    if(navigator.share){
        navigator.share({title:'BantuJiran: '+title, url:url}).catch(()=>{});
    } else {
        navigator.clipboard.writeText(url).then(()=>{
            const btn=event.currentTarget;
            const orig=btn.innerHTML;
            btn.innerHTML='<i class="bi bi-check-lg"></i>';
            btn.style.color='#00C8AA';
            setTimeout(()=>{btn.innerHTML=orig;btn.style.color='';},2000);
        });
    }
}
</script>
@endpush
