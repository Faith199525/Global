@extends('layouts.app')
<link href="{{ asset('css/image.css') }}" rel="stylesheet">
<script src="{{ asset('js/preview-image.js') }}" defer></script>
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
                    <h4>Create a New Thread</h4>
                </div>

                <div class="card-body">
                    <form enctype='multipart/form-data'  method="POST" action="/threads">
                        @csrf

                        <div class="form-group mb-2">
                            <label for="category_id">Category:</label>

                            <select name="category_id" class="form-control" required>
                                <option value="">Choose a Category...</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="title">Title:</label>

                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required />
                        </div>

                        <div class="form-group mb-2">
                            <label for="body">Body:</label>

                            <textarea class="form-control" id="body" name="body" rows="10" required></textarea>
                        </div>
 
                        <div class="container mb-1">
                            <input type="file" id="file-input" name="files[]" onchange="preview()" multiple 
                            accept="image/png, image/jpeg, image/jpg">
                            <label for="file-input" style="display:block; position:relative; background-color:#025bee; color:#ffffff; font-size:18px; text-align:center; width:300px; padding:10px 0; margin:auto; border-radius:5px; cursor:pointer;"> 
                                <i class="fas fa-upload center"></i> &nbsp; Choose An Image
                            </label>      
                            <p id="num-of-files" class="mt-2">No Of Files Chosen </p>
                            @error('files')
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
<div class="container">
        <div 
            class="py-2 px-3" id="images" style="overflow:hidden; width:50%;  box-sizing: border-box;"><!-- do a button to remove selected files-->
        </div>
    </div>
    <script>
    $(document).ready(function() 
    {
        $('#file-input').change(function() 
        {
            if (this.files.length > 4)
                alert('You can only upload 4 files at maximum');
        });
    });
</script>
@endsection
