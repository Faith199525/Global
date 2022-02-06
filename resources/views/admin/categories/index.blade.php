@extends('admin.layouts.app')
@section('content')
<div class="container">
    <div class="content-header row">
        <div class="content-header-left breadcrumbs-left breadcrumbs-top col-md-6 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
            <li class="breadcrumb-item active">All Categories
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
                                    <input class="col-md-10" type="text" name="q" placeholder="Enter name or email address" class="form-control pull-right">
                                    <button class="btn btn-sm btn-primary col-md-2">Search</button>
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
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($categories) @foreach ($categories as $key => $category)
                                    <tr>
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>{{$category->name}}</td>
                                        <td>
                                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-success">Edit</a>
                                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type='submit' class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
