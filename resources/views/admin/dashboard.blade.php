@extends('admin.layout')
@section('title','Dashboard')
@section('content')
<h4 class="fw-bold mb-4">Dashboard</h4>
<div class="row g-3 mb-4">
    @foreach([['Pengguna','users',0,'bi-people','#5B6BF8'],['Post Aktif','active',1,'bi-megaphone','#00C8AA'],['Laporan Pending','reports',0,'bi-flag','#FF4757'],['Disekat','banned',0,'bi-slash-circle','#FF9500']] as [$l,$k,$v,$i,$c])
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width:36px;height:36px;background:{{ $c }}18;border-radius:11px;display:flex;align-items:center;justify-content:center">
                    <i class="bi {{ $i }}" style="color:{{ $c }}"></i>
                </div>
                <span class="stat-lbl">{{ $l }}</span>
            </div>
            <div class="stat-num">{{ $stats[$k] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">Post Terbaru</h6>
            <table class="table table-sm">
                <thead><tr><th>Tajuk</th><th>Jenis</th><th>Pengguna</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($recentPosts as $p)
                <tr>
                    <td><a href="{{ route('posts.show',$p) }}" target="_blank" class="text-decoration-none">{{ Str::limit($p->title,30) }}</a></td>
                    <td><span class="badge bg-secondary" style="font-size:.65rem">{{ $p->type }}</span></td>
                    <td style="font-size:.82rem">{{ $p->user->name }}</td>
                    <td><span class="badge" style="background:{{ ['open'=>'#00C8AA','in_progress'=>'#FF9500','completed'=>'#9B59B6'][$p->status]??'#9BA3C4' }};font-size:.65rem">{{ $p->status }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-5">
        <div class="stat-card">
            <h6 class="fw-bold mb-3">Laporan Pending <span class="badge bg-danger">{{ $stats['reports'] }}</span></h6>
            @forelse($pendingReports as $r)
            <div class="d-flex align-items-start gap-2 mb-2 pb-2 border-bottom">
                <div style="flex-grow:1;font-size:.8rem">
                    <div class="fw-bold">{{ Str::limit($r->post->title??'[deleted]',25) }}</div>
                    <div class="text-muted">{{ $r->reason }} · oleh {{ $r->reporter->name }}</div>
                </div>
                <form method="POST" action="{{ route('admin.reports.review',$r) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="reviewed">
                    <button class="btn btn-sm btn-outline-success" style="font-size:.7rem">Selesai</button>
                </form>
            </div>
            @empty<p class="text-muted small">Tiada laporan pending.</p>@endforelse
        </div>
    </div>
</div>
@endsection
