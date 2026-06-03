<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'stats' => [
                'users'    => User::count(),
                'posts'    => Post::count(),
                'active'   => Post::active()->count(),
                'reports'  => Report::where('status','pending')->count(),
                'banned'   => User::where('is_banned',true)->count(),
                'messages' => Message::count(),
            ],
            'recentPosts'   => Post::with('user')->latest()->limit(8)->get(),
            'pendingReports' => Report::with(['reporter','post.user'])->where('status','pending')->latest()->limit(10)->get(),
        ]);
    }

    public function users(Request $request)
    {
        $q = $request->query('q');
        $users = User::when($q, fn($query) => $query->where('name','like',"%$q%")->orWhere('phone','like',"%$q%"))
            ->withCount('posts')
            ->latest()->paginate(20);
        return view('admin.users', compact('users','q'));
    }

    public function banUser(User $user)
    {
        abort_if($user->is_admin, 403);
        $user->update(['is_banned' => !$user->is_banned]);
        return back()->with('status', $user->is_banned ? 'Pengguna telah disekat.' : 'Sekatan pengguna diangkat.');
    }

    public function posts(Request $request)
    {
        $q = $request->query('q');
        $posts = Post::with('user')
            ->when($q, fn($query) => $query->where('title','like',"%$q%"))
            ->latest()->paginate(20);
        return view('admin.posts', compact('posts','q'));
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('status', 'Post telah dipadam.');
    }

    public function reports()
    {
        $reports = Report::with(['reporter','post.user'])->latest()->paginate(20);
        return view('admin.reports', compact('reports'));
    }

    public function reviewReport(Report $report, Request $request)
    {
        $data = $request->validate(['status' => ['required','in:reviewed,dismissed']]);
        $report->update($data);
        return back()->with('status', 'Laporan dikemas kini.');
    }
}
