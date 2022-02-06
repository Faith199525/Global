@extends('layouts.app')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

@section('content')

<header class="max-w-xl mx-auto text-center">
    <h1 class="text-4xl">
        Latest <span class="text-blue-500">Wissen</span> Threads
    </h1>
</header>
<div class="container">
<div class="row">
<main class="col-md-10">
    @forelse($threads as $thread) 
        <article class='transition-colors duration-300
            hover:bg-gray-100 border border-black border-opacity-0 hover:border-opacity-5 rounded-xl mr-5 mb-5'>
            <div class="py-2 px-5">
                <div style="display: flex;">
                    <img class="rounded-circle ml-2" 
                        src="{{ asset('/storage/images/'. $thread->author->avatar) }}"
                        style="width: 100px; height: 100px; padding: 10px; margin: 0px;">
                    <div>
                        <h4 style="margin: 20px;"><a href="/profiles/{{ $thread->author->name }}">{{ $thread->author->name }}</a> posted: <a href="{{$thread->path()}}"> {{ $thread->title }}</a></h4>

                        <span class="mt-2 block text-gray-400 text-xs" 
                            style="margin:20px;">Published <time>
                            {{$thread->created_at->diffForhumans() }}</time> 
                            with <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
                        </span> 
                    </div>
                </div>
                
                <div class="text-sm mt-4 space-y-4">
                    <p>{{ str::limit(strip_tags($thread->body), 1000) }}
                        @if (strlen(strip_tags($thread->body)) > 1000)
                            <a href="{{$thread->path()}}" class="btn btn-info btn-sm">Read More</a>
                        @endif
                    </p> 
                </div>

                @if (strlen(strip_tags($thread->body)) < 1000)
                    @if ($thread->files != 'null' ) 
                        @foreach(explode(',', str_replace(array('[',']'), '', str_replace('"', '', str_replace('key', '', str_replace('{', '', str_replace(':', '', str_replace('}', '', $thread->files))))))) as $path)
                            <div style="display:inline-block">
                                <img src="{{ asset('storage/images/' . $path)}}" class="mb-3 px-3" height='170px' width='357px'>
                            </div>
                        @endforeach
                    @endif
                @endif
                
                @if (session()->has('success'))
                    <div x-data="{ show: true}"
                        x-init="setTimeout(() => show = false, 4000)"
                        x-show="show"
                        class="fixed bg-blue-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div x-data="{ show: true}"
                        x-init="setTimeout(() => show = false, 4000)"
                        x-show="show"
                        class="fixed bg-blue-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </article>
        
    @empty
        <p style="text-align: center">No thread yet.  Please, click <a  href="/threads/create"> here</a> to create new thread. </p>
    @endforelse
    {{ $threads->links() }}
   </main>
   <div class="col-md-2">
       <div class="card">
       <h4 class="card-header">Archives</h4>
       <ol class="card-body">
           @foreach($archives as $stats) 
                <li >
                    <a style="text-decoration: none;" href="?month={{$stats['month']}}&year={{$stats['year']}} ">
                        {{ $stats['month'] . ' ' . $stats['year']}}
                    </a>
                </li>
            @endforeach
        </ol>
</div>
    </div>
</div>
</div>

@endsection
