<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Like;   
use App\Models\Reply;    

class LikeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    
    public function storeReplyLikes(Reply $reply) {
         
    
        $attributes = Like::where('user_id', auth()->id())
            ->where('likeable_type', 'App\Models\Reply')
            ->where('likeable_id', $reply->id)
            ->first();

        if (! $attributes) {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_id' => $reply->id,
                'likeable_type' => 'App\Models\Reply'
            ]);
        }
        return back();
    }

    public function storeThreadLikes(Thread $thread) {
         
    
        $attributes = Like::where('user_id', auth()->id())
            ->where('likeable_type', 'App\Models\Thread')
            ->where('likeable_id', $thread->id)
            ->first();

        if (! $attributes) {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_id' => $thread->id,
                'likeable_type' => 'App\Models\Thread'
            ]);
        }
        return back();
    }

    
}
