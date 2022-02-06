
<div class="card mb-4">
    <div class="card-header" style="display: flex;">
        <img class="rounded-circle ml-2" 
            src="{{ asset('/storage/images/'. $reply->author->avatar) }}"
            style="width: 100px; height: 100px; padding: 10px; margin: 0px;">
        <div>
            <h4 style="margin: 20px;"><a href="/profiles/{{ $reply->author->name }}">{{ $reply->author->name }}</a> said: {{ $reply->created_at->diffForHumans()}} ...</h4>
        </div>
    </div>

    <div class="card-body">
        <p>{!! nl2br($reply->body)  !!}</p>
    </div>
</div>
<hr>
<div class="flex mb-3" style="margin-left:20%;">
    <form method="POST" action="/replies/{{$reply->id}}/likes">
        @csrf
        <div class="flex items-center mr-10 ">
            <span  class="mr-3 text-xl text-gray-500">{{$reply->replyLikesCount() }} {{ Str::plural('like', $reply->replyLikesCount()) }}</span>
            <button type="submit" <?php if ($thread->locked == '1') { ?> disabled <?php   } ?> class="text-xs flex">
                <svg viewBox="0 0 20 20" class="mr-3 w-5 {{ $reply->isLikedBy() ? 'text-blue-500' : 'text-gray-500'}}">
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
       
   @if( $reply->author_id == auth()->id()  && $thread->locked == 0)
        <div>
            <div class="flex items-center mr-10">
                <a href="" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $reply->id }}" style="text-decoration-line:none;">
                    <span class="mr-3 text-xl text-gray-500" >Edit</span>
                    <i class="fas fa-edit"></i>
                </a>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{ $reply->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Reply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        @include('../layouts/input', ['update'=>true, 'data'=>$reply->body, 'id'=>$reply->id])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>


    @endif
       
    @if( $reply->author_id == auth()->id()  && $thread->locked == 0)
        <form onsubmit="return confirm('Do you really want to delete?');"  method="POST" 
            action="{{ $reply->path() }}" >
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

