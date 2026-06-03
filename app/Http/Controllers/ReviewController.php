<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();
        $canReview = ($post->user_id === $user->id && $post->helper_id)
            || ($post->helper_id === $user->id);
        abort_unless($canReview && in_array($post->status,['in_progress','completed']), 403);

        $data = $request->validate([
            'rating'  => ['required','integer','between:1,5'],
            'comment' => ['nullable','string','max:500'],
        ]);

        $revieweeId = $post->user_id === $user->id ? $post->helper_id : $post->user_id;

        Review::updateOrCreate(
            ['post_id'=>$post->id,'reviewer_id'=>$user->id],
            ['reviewee_id'=>$revieweeId,'rating'=>$data['rating'],'comment'=>$data['comment']??null]
        );

        // Auto-recalculate trust score for the reviewee
        \App\Models\User::find($revieweeId)?->recalculateTrustScore();

        return back()->with('status','Ulasan anda telah dihantar. Terima kasih!');
    }
}
