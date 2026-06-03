<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private const KIND_FOR_TYPE = [
        'sos' => 'help_offer',
        'donate' => 'item_request',
        'tool' => 'borrow_request', 'mobility' => 'carpool_request', 'group_buy' => 'group_buy_join',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasLocation()) {
            return redirect()->route('profile.show')->with('status', 'Sila tetapkan lokasi anda untuk melihat suapan jiran berdekatan.');
        }

        $type   = $request->query('type');
        $radius = (float) $request->query('radius', 5);
        $radius = max(1, min($radius, 20));
        $search = trim($request->query('q', ''));
        $sort   = $request->query('sort', 'nearest'); // nearest|latest|popular

        $query = Post::with('user')->active()->withinRadius($user->home_lat, $user->home_lng, $radius);

        if ($type && in_array($type, Post::TYPES, true)) $query->where('type', $type);
        if ($search) $query->where(fn($q) => $q->where('title','like',"%$search%")->orWhere('body','like',"%$search%"));

        $query->when($sort === 'latest',  fn($q) => $q->reorder('created_at','desc'));
        $query->when($sort === 'popular', fn($q) => $q->reorder('responses_count','desc'));

        $posts = $query->limit(100)->get();
        $mapPoints = $posts->map(fn(Post $p) => [
            'id'=>$p->id,'lat'=>$p->lat,'lng'=>$p->lng,'type'=>$p->type,
            'title'=>$p->title,'distance'=>$p->distance_label,'url'=>route('posts.show',$p),'label'=>$p->meta()['label'],
        ])->values();

        return view('feed.index', [
            'posts'=>$posts,'mapPoints'=>$mapPoints,'activeType'=>$type,
            'radius'=>$radius,'center'=>['lat'=>$user->home_lat,'lng'=>$user->home_lng],
            'search'=>$search,'sort'=>$sort,
        ]);
    }

    public function create(Request $request)
    {
        $type = $request->query('type','sos');
        if (!in_array($type, Post::TYPES, true)) $type = 'sos';
        return view('posts.create', ['type'=>$type,'user'=>Auth::user()]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $base = $request->validate([
            'type'      => ['required','in:'.implode(',',Post::TYPES)],
            'title'     => ['required','string','max:120'],
            'body'      => ['nullable','string','max:2000'],
            'lat'       => ['required','numeric','between:-90,90'],
            'lng'       => ['required','numeric','between:-180,180'],
            'radius_km' => ['nullable','numeric','between:1,20'],
            'images.*'  => ['nullable','image','max:4096'],
        ]);

        $type = $base['type'];

        // Handle images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('posts','public');
            }
        }

        $meta = []; $extra = [];
        switch ($type) {
            case 'sos':
                $v = $request->validate(['severity'=>['required','in:low,medium,high,critical']]);
                $extra['severity'] = $v['severity'];
                $extra['expires_at'] = now()->addHours(12);
                break;
            case 'group_buy':
                $extra['price'] = $request->input('price');
                $meta = $request->input('meta',[]);
                break;
            case 'mobility': case 'tool': case 'donate':
                $meta = $request->input('meta',[]);
                break;
        }

        $post = Post::create(array_merge([
            'user_id'=>$user->id,'type'=>$type,'title'=>$base['title'],
            'body'=>$base['body']??null,'lat'=>$base['lat'],'lng'=>$base['lng'],
            'radius_km'=>$base['radius_km']??3,'meta'=>$meta?:null,
            'images'=>$images?:null,'status'=>'open',
        ], $extra));

        return redirect()->route('posts.show',$post)->with('status','Hebahan anda telah disiarkan kepada jiran berdekatan.');
    }

    public function show(Post $post)
    {
        abort_if(Auth::user()->is_banned, 403);
        $post->load(['user','responses.user','helper','reviews.reviewer']);
        $user = Auth::user();
        $distanceLabel = null;
        if ($user->hasLocation()) {
            $m = $this->haversine($user->home_lat,$user->home_lng,$post->lat,$post->lng);
            $distanceLabel = $m < 1000 ? round($m).' m' : number_format($m/1000,1).' km';
        }
        $hasReported = Report::where('reporter_id',$user->id)->where('post_id',$post->id)->exists();
        return view('posts.show', [
            'post'=>$post,'distanceLabel'=>$distanceLabel,
            'responseKind'=>self::KIND_FOR_TYPE[$post->type]??'help_offer',
            'myResponse'=>$post->responses->firstWhere('user_id',$user->id),
            'isOwner'=>$post->user_id===$user->id,
            'hasReviewed'=>$post->reviews->where('reviewer_id',$user->id)->isNotEmpty(),
            'hasReported'=>$hasReported,
        ]);
    }

    public function report(Request $request, Post $post)
    {
        $data = $request->validate(['reason'=>['required','string','max:120'],'notes'=>['nullable','string','max:500']]);
        Report::updateOrCreate(
            ['reporter_id'=>Auth::id(),'post_id'=>$post->id],
            ['reason'=>$data['reason'],'notes'=>$data['notes']??null,'status'=>'pending']
        );
        return back()->with('status','Laporan anda telah dihantar. Terima kasih!');
    }

    public function updateStatus(Request $request, Post $post)
    {
        abort_unless($post->user_id === Auth::id(), 403);
        $data = $request->validate(['status'=>['required','in:open,in_progress,completed,closed']]);
        $post->update(['status'=>$data['status']]);
        return back()->with('status','Status hebahan dikemas kini.');
    }

    private function haversine(float $lat1,float $lng1,float $lat2,float $lng2): float {
        $earth=6371000; $dLat=deg2rad($lat2-$lat1); $dLng=deg2rad($lng2-$lng1);
        $a=sin($dLat/2)**2+cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLng/2)**2;
        return $earth*2*atan2(sqrt($a),sqrt(1-$a));
    }
}
