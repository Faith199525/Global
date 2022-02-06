@extends('admin.layouts.app')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

@section('content')
<div class="container">
    <div class="content-header row">
        <div class="content-header-left breadcrumbs-left breadcrumbs-top col-md-6 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active">All Threads
            </li>
            </ol>
        </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                    <div class="col-md-6 ">
                        
                        </div>
                        <div class="col-md-6 ">
                            <form class="" action="">
                                <div class="row">
                                    <input class="col-md-9" type="text" name="q" placeholder="Enter author's name, thread title or content" class="form-control pull-right">
                                    <button class="btn btn-sm btn-primary col-md-2 ml-2">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">S/N</th>
                                        <th scope="col">Posted By</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($threads) @foreach ($threads as $key => $thread)
                                    <tr>
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>{{$thread->author->name}}</td>
                                        <td>{{$thread->title}}</td>
                                        <td>{{ Illuminate\Support\Str::limit(strip_tags($thread->body), 500) }}</td>
                                        <td class="text-right row">
                                            <div class="btn-group" role="group">
                                                <!-- <div class="col-md-4 custom">
                                                   <a href="#" class="btn btn-sm btn-primary">View</a>       
                                                </div> -->
                                              
                                                <div class="col-md-4 custom">
                                                    @if ($thread->locked == 0)
                                                        <form action="/admin/locked-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-sm btn-danger align-self-start ml-2">Lock</button>
                                                        </form>

                                                    @else
                                                        <form action="/admin/unlocked-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-sm btn-primary align-self-start ml-2">Unlock</button>
                                                        </form>
                                                    @endif
                                                </div>
                                             
                                                <div class="col-md-4 custom">
                                                    @if ($thread->archived == 0)
                                                        <form action="/admin/archive-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-sm btn-danger align-self-start">Archive</button>
                                                        </form>

                                                    @else
                                                        <form action="/admin/unarchive-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-sm btn-primary align-self-start">Unarchive</button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div class="col-md-4 custom mr-4">
                                                    @if ($thread->deleted_at == null)
                                                        <form action="/admin/delete-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-sm btn-danger align-self-start">Delete</button>
                                                        </form>

                                                    @else
                                                        <form action="/admin/restore-thread/{{$thread->id}}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button type="submit" class="btn btn-sm btn-primary align-self-start mr-2">Restore</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @isset($threads)
            {{ $threads->links() }}
            @endisset
        </div>
    </div>
</div>
@endsection
