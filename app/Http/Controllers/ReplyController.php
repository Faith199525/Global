<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Models\Thread;

class ReplyController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($category, Thread $thread)
    {  
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }
        
        $thread->addReply ([
            'body' => request('body'),
            'author_id' => auth()->id()
        ]);

        session()->flash('success', 'Your thread reply is successfully created!' );

        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reply)
    {     
        $reply = Reply::find($reply);

        if ($reply->author_id != auth()->id()) {
            abort(403, "You don't have permision to do this.");
        }

        $reply->update(request()->validate(['body' => 'required']));

        session()->flash('success', 'Your thread reply is successfully updated!' );

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy($category, Thread $thread, Reply $reply)
    { 
        if ($reply->author_id != auth()->id()) {
            abort(403, "You don't have permision to do this.");
        }

        $reply->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        session()->flash('success', 'Your thread reply is successfully delected!' );

        return back();
    } 
}
