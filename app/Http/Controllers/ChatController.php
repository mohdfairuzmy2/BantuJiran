<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::with(['userOne','userTwo','post','messages'=>fn($q)=>$q->latest()->limit(1)])
            ->where('user_one_id',$userId)->orWhere('user_two_id',$userId)
            ->orderByDesc('last_message_at')->get();
        return view('chat.index', compact('conversations'));
    }

    public function start(Request $request, Post $post)
    {
        abort_if($post->user_id === Auth::id(), 422);
        $conversation = Conversation::firstOrCreateBetween($post->id, Auth::id(), $post->user_id);
        return redirect()->route('chat.show',$conversation);
    }

    public function show(Conversation $conversation)
    {
        $this->auth($conversation);
        $conversation->load(['userOne','userTwo','post','messages.user']);
        $conversation->messages()->where('user_id','!=',Auth::id())->whereNull('read_at')->update(['read_at'=>now()]);
        return view('chat.show', ['conversation'=>$conversation,'other'=>$conversation->otherUser(Auth::id())]);
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->auth($conversation);
        $data = $request->validate(['body'=>['required','string','max:1000']]);
        $msg = $conversation->messages()->create(['user_id'=>Auth::id(),'body'=>$data['body']]);
        $conversation->update(['last_message_at'=>now()]);

        // Push to other user
        $otherId = $conversation->user_one_id === Auth::id() ? $conversation->user_two_id : $conversation->user_one_id;
        PushController::sendToUser($otherId, Auth::user()->name.' menghantar mesej', $data['body'], route('chat.show',$conversation));

        return redirect()->route('chat.show',$conversation);
    }

    public function poll(Request $request, Conversation $conversation)
    {
        $this->auth($conversation);
        $after = (int)$request->query('after',0);
        $messages = $conversation->messages()->where('id','>',$after)->orderBy('id')->get(['id','user_id','body','created_at']);
        return response()->json(['messages'=>$messages->map(fn($m)=>[
            'id'=>$m->id,'mine'=>$m->user_id===Auth::id(),'body'=>$m->body,'time'=>$m->created_at->format('H:i'),
        ])]);
    }

    /** Server-Sent Events — real-time alternative to polling */
    public function stream(Request $request, Conversation $conversation): StreamedResponse
    {
        $this->auth($conversation);
        $after = (int)$request->query('after', 0);

        return response()->stream(function() use ($conversation, &$after) {
            $limit = 60; // max 60 seconds
            $start = time();
            while (time() - $start < $limit) {
                $messages = $conversation->messages()->where('id','>',$after)->orderBy('id')->get(['id','user_id','body','created_at']);
                if ($messages->isNotEmpty()) {
                    $after = $messages->last()->id;
                    $payload = json_encode(['messages'=>$messages->map(fn($m)=>[
                        'id'=>$m->id,'mine'=>$m->user_id===Auth::id(),'body'=>$m->body,'time'=>$m->created_at->format('H:i'),
                    ])]);
                    echo "data: $payload\n\n";
                    ob_flush(); flush();
                }
                sleep(2);
            }
            echo "data: {\"ping\":true}\n\n";
            ob_flush(); flush();
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function auth(Conversation $c): void { abort_unless($c->involves(Auth::id()),403); }
}
