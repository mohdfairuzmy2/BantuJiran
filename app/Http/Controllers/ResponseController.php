<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Post;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    private const ALLOWED_KINDS = ['help_offer','group_buy_join','carpool_request','borrow_request','item_request'];

    public function store(Request $request, Post $post)
    {
        $user = Auth::user();
        abort_if($post->user_id === $user->id, 422, 'Anda tidak boleh membalas hebahan sendiri.');
        if ($post->isSensitive() && !$user->is_verified) {
            return back()->with('status','Hanya pengguna disahkan boleh membantu untuk modul ini.');
        }

        $data = $request->validate([
            'kind'    => ['required','in:'.implode(',',self::ALLOWED_KINDS)],
            'qty'     => ['nullable','integer','min:1','max:99'],
            'message' => ['nullable','string','max:500'],
        ]);

        $response = Response::updateOrCreate(
            ['post_id'=>$post->id,'user_id'=>$user->id,'kind'=>$data['kind']],
            ['qty'=>$data['qty']??1,'message'=>$data['message']??null,'status'=>'pending']
        );

        $conversation = Conversation::firstOrCreateBetween($post->id,$user->id,$post->user_id);
        if ($msg = $data['message']??null) {
            $conversation->messages()->create(['user_id'=>$user->id,'body'=>$msg]);
            $conversation->update(['last_message_at'=>now()]);
        }

        // Push notification to post owner
        PushController::sendToUser(
            $post->user_id,
            'Respons baru pada "'.$post->title.'"',
            $user->name.' telah membalas hebahan anda.',
            route('posts.show',$post)
        );

        return redirect()->route('chat.show',$conversation)->with('status','Maklum balas anda dihantar. Teruskan perbualan di sini.');
    }

    public function accept(Request $request, Response $response)
    {
        $post = $response->post;
        abort_unless($post->user_id === Auth::id(), 403);
        $response->update(['status'=>'accepted']);
        $post->update(['helper_id'=>$response->user_id,'status'=>'in_progress']);
        PushController::sendToUser($response->user_id,'Bantuan anda diterima!','Anda telah diterima untuk "'.$post->title.'".', route('posts.show',$post));
        return back()->with('status','Anda telah menerima bantuan daripada '.$response->user->name.'.');
    }

    public function decline(Request $request, Response $response)
    {
        abort_unless($response->post->user_id === Auth::id(), 403);
        $response->update(['status'=>'declined']);
        return back()->with('status','Maklum balas ditolak.');
    }
}
