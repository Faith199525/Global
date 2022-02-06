@extends('layouts.app')
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

@section('content')

<div class="container">
    <div class="row  mb-5">
        <div class="card-body">
            <form action="{{route('user.upload')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="files" onchange="preview()">
                <input type="submit" value="Upload">
            </form>
        </div>
    </div>
</div>

@endsection