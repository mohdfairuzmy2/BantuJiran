@extends('layouts.app')
@section('title','Sembang')
@push('head')
<style>
#thread {
    flex:1; overflow-y:auto; display:flex; flex-direction:column;
    padding:.75rem; gap:.4rem; scrollbar-width:thin; min-height:0;
}
.msg { max-width:78%; padding:.6rem .9rem; border-radius:18px; font-size:.875rem; line-height:1.45; word-break:break-word; }
.msg.mine { background:linear-gradient(135deg,#5B6BF8,#7B5BF8); color:#fff; margin-left:auto; border-bottom-right-radius:5px; box-shadow:0 3px 12px rgba(91,107,248,.35); }
.msg.theirs { background:#fff; color:#1A1D3B; border:1.5px solid #E4E7FF; border-bottom-left-radius:5px; box-shadow:0 1px 4px rgba(91,107,248,.06); }
.msg .t { font-size:.58rem; opacity:.55; display:block; margin-top:.25rem; text-align:right; }
.msg.theirs .t { text-align:left; color:#B8BDD8; }
.chat-input-wrap {
    position:fixed; bottom:var(--nav-h); left:0; right:0;
    background:rgba(240,242,255,.95); backdrop-filter:blur(24px);
    border-top:1.5px solid rgba(228,231,255,.8);
    padding:.65rem 1rem; padding-bottom:calc(.65rem + env(safe-area-inset-bottom));
}
.chat-in {
    flex:1; border:2px solid #E4E7FF; border-radius:99px;
    padding:.55rem 1.1rem; font-size:.875rem;
    background:#fff; color:#1A1D3B; outline:none;
    font-family:'Inter',sans-serif; transition:border-color .15s;
}
.chat-in:focus { border-color:var(--p); }
.chat-send {
    width:44px; height:44px; border-radius:14px; border:none; flex-shrink:0;
    background:linear-gradient(135deg,#5B6BF8,#9B59B6); color:#fff;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 14px rgba(91,107,248,.45); transition:transform .15s;
}
.chat-send:hover { transform:scale(.93); }
/* Desktop: make thread fill available space in frame */
@media(min-width:768px){
    .chat-wrap { display:flex; flex-direction:column; height:calc(var(--ph) - 44px - 60px - var(--nav-h) - 24px - 78px); }
}
@media(max-width:767px){
    .chat-wrap { display:flex; flex-direction:column; height:calc(100svh - 130px - var(--nav-h) - env(safe-area-inset-bottom)); }
}
</style>
@endpush
@section('content')
{{-- Header --}}
<div class="bj-card px-3 py-3 mb-2 d-flex align-items-center gap-3">
    <a href="{{ route('chat.index') }}"
       style="width:38px;height:38px;background:var(--p-soft);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--p);text-decoration:none;flex-shrink:0">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="mi mi-mob" style="width:42px;height:42px;border-radius:50%;font-size:1.1rem;flex-shrink:0"><i class="bi bi-person-fill"></i></div>
    <div class="flex-grow-1 min-w-0">
        <div class="fw-bold" style="font-size:.925rem;color:#1A1D3B">{{ $other?->name??'Pengguna' }}</div>
        @if($conversation->post)
        <a href="{{ route('posts.show',$conversation->post) }}" class="text-decoration-none text-truncate d-block" style="font-size:.7rem;color:#9BA3C4">
            <i class="bi bi-pin-angle-fill me-1" style="color:var(--p)"></i>{{ $conversation->post->title }}
        </a>
        @endif
    </div>
</div>

{{-- Messages --}}
<div class="chat-wrap">
    <div id="thread" style="background:#F8F9FF;border-radius:var(--r)">
        @foreach($conversation->messages as $msg)
        <div class="msg {{ $msg->user_id===auth()->id()?'mine':'theirs' }}">
            {{ $msg->body }}<span class="t">{{ $msg->created_at->format('H:i') }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<div class="chat-input-wrap">
    <form id="chatForm" method="POST" action="{{ route('chat.send',$conversation) }}" class="d-flex gap-2 align-items-center">
        @csrf
        <input type="text" name="body" id="msgIn" class="chat-in" placeholder="Taip mesej..." autocomplete="off" required autofocus>
        <button type="submit" class="chat-send"><i class="bi bi-send-fill" style="font-size:.9rem"></i></button>
    </form>
</div>
<script>
const thread=document.getElementById('thread');
const pollUrl=@json(route('chat.poll',$conversation));
let lastId={{ $conversation->messages->last()->id??0 }};
const myId={{ auth()->id() }};
function scrollBot(){ thread.scrollTop=thread.scrollHeight; }
scrollBot();
function addMsg(body,time,mine){
    const d=document.createElement('div');
    d.className='msg '+(mine?'mine':'theirs');
    d.innerHTML=`${body}<span class="t">${time}</span>`;
    thread.appendChild(d); scrollBot();
}
async function poll(){
    try{ const r=await fetch(`${pollUrl}?after=${lastId}`); const d=await r.json(); d.messages.forEach(m=>{lastId=m.id;addMsg(m.body,m.time,m.mine);}); }catch(e){}
}
setInterval(poll,3000);
window.addEventListener('resize',scrollBot);
</script>
@endpush
