
@if($update)
    <form class="flex flex-col w-full" method="POST" action="{{ route('reply.update', ['reply' => $reply])}}">
        @method('patch')
@else
    <form class="flex flex-col w-full" method="POST" action="{{ $thread->path().'/replies'}}">
@endif
        @csrf
        <div class="form-group">
            <textarea name="body" id="body" placeholder="Have something to say?" row="5" class="form-control">{{$data}}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Publish</button>
    </form>