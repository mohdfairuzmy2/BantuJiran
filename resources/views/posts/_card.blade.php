@php
$meta=$post->meta();
$tc=['sos'=>'sos','donate'=>'donate','tool'=>'tool','mobility'=>'mob','group_buy'=>'buy'][$post->type]??'mob';
$tclr=['sos'=>'#FF4757','donate'=>'#00C8AA','tool'=>'#9B59B6','mobility'=>'#5B6BF8','group_buy'=>'#FF9500'][$post->type]??'#5B6BF8';
$tbdr=['sos'=>'#FFE4E7','donate'=>'#D0F7F2','tool'=>'#EDE0F8','mobility'=>'#E4E7FF','group_buy'=>'#FFE8C0'][$post->type]??'#E4E7FF';
$sl=['open'=>'Dibuka','in_progress'=>'Berjalan','completed'=>'Selesai','closed'=>'Ditutup'][$post->status]??ucfirst($post->status);
$sc=['open'=>'#00C8AA','in_progress'=>'#FF9500','completed'=>'#9B59B6','closed'=>'#9BA3C4'][$post->status]??'#9BA3C4';
@endphp
<a href="{{ route('posts.show',$post) }}" class="text-decoration-none d-block mb-2">
<div class="bj-card px-3 py-3 d-flex gap-3 align-items-center"
     style="border-left:3px solid {{ $tclr }};transition:box-shadow .18s,transform .18s"
     onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 28px rgba(91,107,248,.12)'"
     onmouseout="this.style.transform='';this.style.boxShadow=''">
    {{-- Thumbnail or icon --}}
    @if(!empty($post->images[0]))
        <div style="width:52px;height:52px;border-radius:14px;overflow:hidden;flex-shrink:0">
            <img src="{{ asset('storage/'.$post->images[0]) }}" style="width:100%;height:100%;object-fit:cover">
        </div>
    @else
        <div class="mi mi-{{ $tc }}"><i class="bi {{ $meta['icon'] }}"></i></div>
    @endif
    <div class="flex-grow-1 min-w-0">
        <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
            <span style="font-size:.65rem;font-weight:800;letter-spacing:.5px;text-transform:uppercase;color:{{ $tclr }}">{{ $meta['label'] }}</span>
            @if($post->type==='sos'&&$post->severity)
                <span style="font-size:.6rem;font-weight:800;background:#FF4757;color:#fff;border-radius:6px;padding:.1rem .4rem">{{ strtoupper($post->severity) }}</span>
            @endif
            @if($post->status!=='open')
                <span style="font-size:.6rem;font-weight:700;background:{{ $sc }}18;color:{{ $sc }};border-radius:6px;padding:.1rem .4rem;margin-left:auto">{{ $sl }}</span>
            @endif
        </div>
        <div class="fw-bold text-truncate" style="font-size:.9rem;color:#1A1D3B;line-height:1.3">{{ $post->title }}</div>
        @if($post->type==='sos' && $post->expires_at && $post->expires_at->isFuture())
        <div style="font-size:.68rem;color:#FF4757;font-weight:700;margin-top:.1rem">
            <i class="bi bi-alarm-fill me-1"></i>
            <span class="sos-countdown" data-expires="{{ $post->expires_at->timestamp }}">Tamat dalam...</span>
        </div>
        @endif
        @if($post->body)
        <div class="text-truncate" style="font-size:.76rem;color:#9BA3C4;margin-top:.15rem">{{ $post->body }}</div>
        @endif
        <div class="d-flex align-items-center justify-content-between mt-1">
            <div style="font-size:.7rem;color:#B8BDD8;display:flex;align-items:center;gap:.3rem">
                <i class="bi bi-person-circle"></i><span>{{ $post->user->name }}</span>
                @if($post->user->trust_score>0)
                <span style="background:#FFF3CD;color:#9A5000;font-size:.6rem;font-weight:700;border-radius:6px;padding:.1rem .35rem">★ {{ number_format($post->user->trust_score,1) }}</span>
                @endif
            </div>
            @if($post->distance_label)
            <div style="font-size:.7rem;color:#B8BDD8"><i class="bi bi-geo-alt"></i> {{ $post->distance_label }}</div>
            @endif
        </div>
    </div>
    <i class="bi bi-chevron-right" style="color:#D4D8F0;font-size:.78rem;flex-shrink:0"></i>
</div>
</a>
