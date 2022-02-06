@extends('layouts.app')
<link href="{{ asset('css/image.css') }}" rel="stylesheet">
<script src="{{ asset('js/preview-image.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (count($errors))
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">One or more errors occured</h4>

                    <ul class="mb-0 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header mt-2">
                <h4>Edit: {{$thread->title}}</h4>
                </div>

                <div class="card-body">
                    <form enctype='multipart/form-data'  method="POST" action="{{$thread->path() }}">
                        @csrf
                        @method("PATCH")
                        <div class="form-group mb-2">
                            <label for="category_id">Category:</label>

                            <select name="category_id" class="form-control" required>
                                <option value="">Choose a Category...</option>

                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"{{ $category->id === $thread->category_id ? ' selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="title">Title:</label>

                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $thread->title) }}" editable required />
                        </div>

                        <div class="form-group mb-2">
                            <label for="body">Body</label>

                            <textarea class="form-control" id="body" name="body" rows="8" required>{{old('body', $thread->body)}}</textarea>
                        </div>
 
                        <div class="container mb-6">
                    <input type="file" id="file-input" name="files[]" onchange="preview()" multiple value="{{old($thread->files)}}"
                    accept="image/png, image/jpeg, image/jpg">
                    <label for="file-input" class="mb-3"
                    style="display:block; position:relative; background-color:#025bee; color:#ffffff; font-size:18px; text-align:center; width:300px; padding:18px 0; margin:auto; border-radius:5px; cursor:pointer;"> 
                        <i class="fas fa-upload"></i> &nbsp; Choose An Image
                    </label>
                    @if ($thread->files != 'null' ) 
                        @foreach(explode(',', str_replace(array('[',']'), '', str_replace('"', '', str_replace('key', '', str_replace('{', '', str_replace(':', '', str_replace('}', '', $thread->files))))))) as $path)
                            <div style="display:inline-block">
                                <img src="images/{{$path}}" style="width:150px; height:100px;">
                            </div>
                        @endforeach
                    @endif
                    <p id="num-of-files">No Of Files Chosen </p>
                    @error('file-input')
                        <p class="text-red-500 text-xsmt-2">{{ $message }}</p>
                    @enderror                   
                </div>
                        
                        <button type="submit" class="btn btn-primary">Publish</button>
                        
                    </form>
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
@endsection

