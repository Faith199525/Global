<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    { 
        $threads = $this->getThreads($category);

        $archives = Thread::selectRaw('year(created_at) year, monthname(created_at) month, count(*) published')
            ->groupBy('year', 'month')
            ->orderByRaw('min(created_at) desc')
            ->where('archived' , 0)
            ->whereDateBetween('created_at',(new Carbon)->subDays(120)
            ->startOfDay()
            ->toDateString(),(new Carbon)
            ->now()
            ->endOfDay()
            ->toDateString() )
            ->get()
            ->toArray();
        
        return view('threads.index', compact('threads', 'archives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'files' => 'nullable',
            'category_id' => ['required', 'exists:categories,id']
        ]);

        if ($request->hasFile('files')) 
        {
            //create empty array 
            $media = array();
            $Files = $request->file('files');

            foreach ($Files as $file)
            {
                $filename = $file->getClientOriginalName();
                $filename = time(). '.' . $filename;
                $filePath = '/images/' . $filename;
                Storage::disk('public')->put($filePath, file_get_contents($file));
                
                //put filename into array created above
                array_push($media, $filename);
            }   
        }

        $thread = Thread::create([
            'author_id' => auth()->id(),
            'category_id' => request('category_id'),
            'title' => request('title'),
            'body' => request('body'),
            'files' => json_encode($request->hasFile('files') ? $media : null),
        ]);

        session()->flash('success', 'Your thread is successfully created!' );

        return redirect('/threads');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($category, Thread $thread)
    {
        $replies = $thread->replies()->latest()->paginate(20);
        
        return view('threads.show', compact('thread', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit($category, Request $request, Thread $thread)
    {
        return view('threads.edit', [
            'thread'=> $thread
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($category, Request $request, Thread $thread)
    {

        if ($thread->author_id != auth()->id()) {
            abort(403, "You don't have permision to do this.");
        }

        $attributes = request()->validate([
            'title' => 'required',
            'category_id' => ['required', 'exists:categories,id'],
            'body' =>'required'
        ]);

        $id = $thread->id;
        $thread = Thread::find($id);
        $attributes['author_id'] = auth()->id();        
        
        $thread->update($attributes);

        session()->flash('success', 'Your thread is successfully updated!' );

        return redirect('/threads');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($category, Thread $thread)
    { 
        if ($thread->author_id != auth()->id()) {
            abort(403, "You don't have permision to do this.");
        }
        
        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        session()->flash('success', 'Your post is successfully deleted!');

        return redirect('/threads');
    }

    public function getThreads ($category) {

        if ($category->exists) {

            $threads = $category->threads()->latest()->paginate(25);
            
        }

        else {
            $threads = Thread::whereDateBetween('created_at',(new Carbon)->subDays(120)
                ->startOfDay()
                ->toDateString(),(new Carbon)
                ->now()
                ->endOfDay()
                ->toDateString() )
                ->where('archived' , 0)
                ->latest()
                ->paginate(25);
        }

        if ($name = request('by')) {
            $user = User::where('name', $name)->firstorFail();

            $threads = Thread::where('author_id', $user->id)
                ->where('archived' , 0)
                ->whereDateBetween('created_at',(new Carbon)->subDays(120)
                ->startOfDay()
                ->toDateString(),(new Carbon)
                ->now()
                ->endOfDay()
                ->toDateString() )
                ->latest()
                ->paginate(25);
        }

        if (request('popular')) {
           $threads = Thread::orderByDesc('replies_count')
                ->where('archived' , 0)
                ->whereDateBetween('created_at',(new Carbon)->subDays(120)
                ->startOfDay()
                ->toDateString(),(new Carbon)
                ->now()
                ->endOfDay()
                ->toDateString() )
                ->latest()
                ->paginate(25);
        }

        if (request('month') && request('year')) {
           
            $month = request('month');
            $year = request('year');

            $threads = Thread::whereMonth('created_at', Carbon::parse($month)->month)
                            ->whereYear('created_at', $year)
                            
                            ->latest()
                            ->paginate();
        }
        
        return $threads;
    }

   
}
