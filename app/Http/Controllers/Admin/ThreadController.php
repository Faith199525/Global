<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->q) {
            $threads = Thread::whereHas('author', function($query) use($request) {
                $query->where('name', 'like', '%' . $request->q . '%');
             })->orWhere( function($query) use( $request){
             $query->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('body', 'like', '%' . $request->q . '%');
             })   
                ->withTrashed()
                ->orderBy('created_at')
                ->paginate(20);

                return view('admin.threads.index', compact('threads'));
        }

        $threads = Thread::withTrashed()->paginate(20);

        

        return view('admin.threads.index', compact('threads'));
    }

    public function lock(Thread $thread) {
        
        $thread->lock();

        $thread->save();

        return back();
    }

    public function unlock(Thread $thread) {
        
        $thread->unlock();

        $thread->save();

        return back();
    }

    public function archive(Thread $thread) {

        $thread->archive();

        $thread->save();

        return back();
    }

    public function unarchive(Thread $thread) {
        
        $thread->unarchive();

        $thread->save();

        return back();
    }

    public function delete(Thread $thread)
    {        
        $thread->delete();

        $thread->save();

        return back();
    }

    public function restore($id)
    {     
        $thread = Thread::withTrashed()->find($id);
        $thread->restore();

        

        return back();
    }
}
