@extends('admin.layout')
@section('title','Pengguna')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Pengguna</h4>
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari nama/telefon..." style="width:220px">
        <button class="btn btn-primary btn-sm">Cari</button>
    </form>
</div>
<div class="stat-card">
<table class="table table-hover">
    <thead><tr><th>#</th><th>Nama</th><th>Telefon</th><th>Post</th><th>Rating</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($users as $u)
    <tr>
        <td style="font-size:.8rem;color:#9BA3C4">{{ $u->id }}</td>
        <td>
            @if($u->avatar)<img src="{{ asset('storage/'.$u->avatar) }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;margin-right:.4rem">@endif
            <span class="fw-semibold">{{ $u->name }}</span>
            @if($u->is_admin)<span class="badge bg-primary ms-1" style="font-size:.6rem">Admin</span>@endif
            @if($u->is_verified)<span class="badge bg-success ms-1" style="font-size:.6rem">Disahkan</span>@endif
        </td>
        <td style="font-size:.85rem">{{ $u->phone }}</td>
        <td>{{ $u->posts_count }}</td>
        <td>{{ $u->trust_score > 0 ? number_format($u->trust_score,1).'★' : '—' }}</td>
        <td>
            @if($u->is_banned)
                <span class="badge bg-danger">Disekat</span>
            @else
                <span class="badge bg-success">Aktif</span>
            @endif
        </td>
        <td>
            @unless($u->is_admin)
            <form method="POST" action="{{ route('admin.users.ban',$u) }}" onsubmit="return confirm('Pasti?')">
                @csrf
                <button class="btn btn-sm {{ $u->is_banned?'btn-outline-success':'btn-outline-danger' }}" style="font-size:.7rem">
                    {{ $u->is_banned?'Angkat Sekatan':'Sekat' }}
                </button>
            </form>
            @endunless
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{{ $users->links() }}
</div>
@endsection
