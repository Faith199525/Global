@extends('layouts.app')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/js/all.min.js" integrity="sha512-YSdqvJoZr83hj76AIVdOcvLWYMWzy6sJyIMic2aQz5kh2bPTd9dzY3NtdeEAzPp/PhgZqr4aJObB3ym/vsItMg==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-8 mb-3">
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
                <div class="card-body" >
                    <p>{!! nl2br($thread->body)  !!}</p>
                </div>
                @if ($thread->files != 'null' ) 
                    @foreach(explode(',', str_replace(array('[',']'), '', str_replace('"', '', str_replace('key', '', str_replace('{', '', str_replace(':', '', str_replace('}', '', $thread->files))))))) as $path)
                        <div style="display:inline-block">
                            <img src="{{ asset('storage/images/' . $path)}}" class="mb-3 px-3" style="width:357px; height:170px;">
                        </div>
                    @endforeach
                @endif
                
            </div>
            
            </article>

            <hr>
            <div class="flex mb-3" style="margin-left:20%;">
                <form method="POST" action="/threads/{{$thread->id}}/likes">
                    @csrf
                    <div class="flex items-center mr-10 ">
                        <span  class="mr-3 text-xl text-gray-500">{{ $thread->threadLikesCount() }} {{ Str::plural('like', $thread->threadLikesCount()) }}</span>
                        <button type="submit" <?php if ($thread->locked == '1') { ?> disabled <?php   } ?> class="text-xs flex">
                            <svg viewBox="0 0 20 20" class="mr-3 w-5 {{ $thread->isLikedBy() ? 'text-blue-500' : 'text-gray-500'}}">
                                <g id="Page-1" stroke="none" stroke-width="1" 
                                    fill="none" fill-rule="evenodd">
                                    <g class="fill-current">
                                        <path d="M11.0010436,0 C9.89589787,0 9.00000024,0.886706352 9.0000002,1.99810135 L9,8 L1.9973917,8 C0.894262725,8 0,8.88772964 0,10 L0,12 L2.29663334,18.1243554 C2.68509206,19.1602453 3.90195042,20 5.00853025,20 L12.9914698,20 C14.1007504,20 15,19.1125667 15,18.000385 L15,10 L12,3 L12,0 L11.0010436,0 L11.0010436,0 Z M17,10 L20,10 L20,20 L17,20 L17,10 L17,10 Z" id="Fill-97"></path>
                                    </g>
                                </g>
                            </svg>
                        </button>   
                    </div>
                </form>
                
                
                @if( $thread->author_id == auth()->id() && $thread->locked == 0 )
                    <div>
                        <div class="flex items-center mr-10">
                            <a href="{{ $thread->path() }}/edit" style="text-decoration-line:none;">
                                <span class="mr-3 text-xl text-gray-500" >Edit</span>
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                @endif
                

                
                @if( $thread->author_id == auth()->id()  && $thread->locked == 0 )
                    <form onsubmit="return confirm('Do you really want to delete?');"  method="POST" 
                        action="{{ $thread->path() }}" >
                        @csrf
                        @method('DELETE') 
                        <div class="flex items-center mr-10">
                            <span class="mr-3 text-xl text-gray-500">Delete</span>
                            <button type="submit">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </form>
                @endif
                
            </div>
            
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if (auth()->check()  && $thread->locked == 0)
                @include('../layouts/input', ['update'=>false, 'data'=>'', 'id'=>''])
            @endif
            
            @if ($thread->locked == 1)
                <p class="fixed bg-blue-500 text-white py-2 px-4 rounded-xl top-10 right-3 text-sm">This thread is currently locked </p>
            @endif

        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p><strong>Published:</strong> {{ $thread->created_at->diffForHumans() }}</p>
                    <p><strong>Last Updated:</strong> {{ $thread->updated_at->diffForHumans() }}</p>
                    <p><strong>Created By:</strong> {{ $thread->author->name }}</p>
                    <p><strong>Reply Count:</strong> {{ $thread->replies_count }}  {{ Str::plural('reply', $thread->replies_count) }}.</p>
                    <p><strong>Likes Count:</strong> {{ $thread->threadLikesCount() }} {{Str::plural('like', $thread->threadLikesCount()) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@if (session()->has('success'))
    <div x-data="{ show: true}"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        class="fixed bg-blue-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
        <p>{{ session('success') }}</p>
    </div>
@endif

<script>

    </script>
@endsection
