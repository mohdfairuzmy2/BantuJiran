@extends('admin.layout')
@section('title','Laporan')
@section('content')
<h4 class="fw-bold mb-4">Laporan Pengguna</h4>
<div class="stat-card">
<table class="table table-hover">
    <thead><tr><th>Post</th><th>Dilaporkan oleh</th><th>Sebab</th><th>Status</th><th>Tarikh</th><th></th></tr></thead>
    <tbody>
    @foreach($reports as $r)
    <tr>
        <td><a href="{{ route('posts.show',$r->post_id) }}" target="_blank" class="text-decoration-none fw-semibold" style="font-size:.85rem">{{ Str::limit($r->post->title??'[deleted]',30) }}</a></td>
        <td style="font-size:.82rem">{{ $r->reporter->name }}</td>
        <td style="font-size:.82rem">{{ $r->reason }}</td>
        <td>
            <span class="badge" style="background:{{ ['pending'=>'#FF4757','reviewed'=>'#00C8AA','dismissed'=>'#9BA3C4'][$r->status] }};font-size:.65rem">{{ ucfirst($r->status) }}</span>
        </td>
        <td style="font-size:.78rem;color:#9BA3C4">{{ $r->created_at->format('d/m/y') }}</td>
        <td>
            @if($r->status==='pending')
            <div class="d-flex gap-1">
                <form method="POST" action="{{ route('admin.reports.review',$r) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="reviewed"><button class="btn btn-sm btn-outline-success" style="font-size:.7rem">Selesai</button></form>
                <form method="POST" action="{{ route('admin.reports.review',$r) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="dismissed"><button class="btn btn-sm btn-outline-secondary" style="font-size:.7rem">Abaikan</button></form>
            </div>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{ $reports->links() }}
</div>
@endsection
