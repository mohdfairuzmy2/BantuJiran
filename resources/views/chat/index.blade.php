@extends('layouts.app')
@section('title','Sembang')
@section('content')
<div class="bj-title mb-3"><i class="bi bi-chat-dots-fill me-2" style="color:var(--p)"></i>Sembang</div>
@forelse($conversations as $conv)
@php $other=$conv->otherUser(auth()->id()); $last=$conv->messages->first(); @endphp
<a href="{{ route('chat.show',$conv) }}" class="text-decoration-none d-block mb-2">
<div class="bj-card px-3 py-3 d-flex align-items-center gap-3"
     style="transition:box-shadow .18s,transform .18s"
     onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 28px rgba(91,107,248,.12)'"
     onmouseout="this.style.transform='';this.style.boxShadow=''">
    <div class="mi mi-mob" style="width:48px;height:48px;border-radius:50%;font-size:1.2rem;flex-shrink:0"><i class="bi bi-person-fill"></i></div>
    <div class="flex-grow-1 min-w-0">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="fw-bold" style="font-size:.9rem;color:#1A1D3B">{{ $other?->name??'Pengguna' }}</span>
            @if($conv->last_message_at)<span style="font-size:.68rem;color:#B8BDD8">{{ $conv->last_message_at->diffForHumans(null,true) }}</span>@endif
        </div>
        <div class="text-truncate" style="font-size:.76rem;color:#9BA3C4">
            @if($conv->post)<span style="background:var(--p-soft);color:var(--p);font-size:.62rem;font-weight:800;border-radius:5px;padding:.1rem .35rem;margin-right:.3rem"><i class="bi bi-pin-angle-fill"></i></span>@endif
            {{ $last?->body??($conv->post?->title??'Mulakan perbualan...') }}
        </div>
    </div>
    <i class="bi bi-chevron-right" style="color:#D4D8F0;font-size:.78rem;flex-shrink:0"></i>
</div>
</a>
@empty
<div class="bj-card">
    <div class="bj-empty">
        <div class="bj-empty-icon"><i class="bi bi-chat-heart-fill"></i></div>
        <div class="bj-title mb-1" style="font-size:.95rem">Belum ada perbualan</div>
        <div class="bj-sub mb-3">Bantu jiran untuk mula bersembang!</div>
        <a href="{{ route('feed.index') }}" class="btn btn-primary btn-pill px-4">
            <i class="bi bi-compass-fill me-1"></i>Lihat Suapan
        </a>
    </div>
</div>
@endforelse
@endsection
