@extends('admin.layout')
@section('title','Post')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Post</h4>
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari tajuk..." style="width:220px">
        <button class="btn btn-primary btn-sm">Cari</button>
    </form>
</div>
<div class="stat-card">
<table class="table table-hover">
    <thead><tr><th>#</th><th>Tajuk</th><th>Jenis</th><th>Pengguna</th><th>Status</th><th>Tarikh</th><th></th></tr></thead>
    <tbody>
    @foreach($posts as $p)
    <tr>
        <td style="font-size:.8rem;color:#9BA3C4">{{ $p->id }}</td>
        <td><a href="{{ route('posts.show',$p) }}" target="_blank" class="text-decoration-none fw-semibold">{{ Str::limit($p->title,35) }}</a></td>
        <td><span class="badge bg-secondary" style="font-size:.65rem">{{ $p->type }}</span></td>
        <td style="font-size:.85rem">{{ $p->user->name }}</td>
        <td><span class="badge" style="background:{{ ['open'=>'#00C8AA','in_progress'=>'#FF9500','completed'=>'#9B59B6','expired'=>'#9BA3C4'][$p->status]??'#9BA3C4' }};font-size:.65rem">{{ $p->status }}</span></td>
        <td style="font-size:.78rem;color:#9BA3C4">{{ $p->created_at->format('d/m/y') }}</td>
        <td>
            <form method="POST" action="{{ route('admin.posts.delete',$p) }}" onsubmit="return confirm('Padam post ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" style="font-size:.7rem"><i class="bi bi-trash"></i></button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{ $posts->links() }}
</div>
@endsection
