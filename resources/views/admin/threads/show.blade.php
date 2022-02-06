@extends('layouts.app')
@section('css')
<style>
    [v-cloak] { display: none; }
</style>
@endsection
@section('content')
<div class="container">
    <div class="content-header row">
        <div class="content-header-left breadcrumbs-left breadcrumbs-top col-md-6 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">All Users</a>
            </li>
            <li class="breadcrumb-item active">Threads
            </li>
            </ol>
        </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">

                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-header">User Details</div>

                                <div class="card-body">
                                    <div class="card-block">
                                        <div class="row">
                                        <div class="col-4">
                                        @if(! $user->profile->image)
                                        
                                          <img src="{{ asset('storage/img/avatar.png') }}" alt="img" title="" width="150" height="150">
                                        @endif
                                        @if($user->profile->image)
                                        
                                        <img width="200" height="250" src="/{{$user->profile->image}}" class="rounded-circle mr-1" alt="avatar">
                                        @endif
                                        </div>
                                        <div class="col-8">
                                            <span><strong>Name:</strong> {{$user->name}}</span><br>
                                            <span><strong>Email:</strong> {{$user->email}}</span><br>
                                            @isset($user->profile)
                                            <span><strong>Country:</strong> {{$user->profile->country}}</span><br>
                                            <span><strong>State:</strong> {{$user->profile->state}}</span><br>
                                            <span><strong>City:</strong> {{$user->profile->city}}</span><br>
                                            <span><strong>Phone no:</strong> {{$user->profile->phone}}</span><br>
                                            <span><strong>Marital Status:</strong> {{$user->profile->marital_status}}</span><br>
                                            <span><strong>Bio:</strong> {{$user->profile->bio}}</span><br>
                                            <span><strong>Hobbies:</strong> {{$user->profile->hobbies}}</span><br>
                                            @endisset
                                            Role: <span class="btn btn-sm btn-secondary"> {{$user->role ? $user->role->role->name : 'No Role'}}</span> <br>
                                        </div>
                                        </div>  <br><br>

                                        @if (auth()->user()->hasPermission('can_grant_super_admin') || auth()->user()->hasPermission('can_grant_admin') || auth()->user()->hasPermission('can_grant_hod'))
                                        <hr>
                                        <h5 class="text-center">Assign Role</h5>

                                        <div class="card-block">
                                            
                                            <div class="">
                                                <form action="/roles/assign/{{$user->id}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <select name="role" id="" class="form-control">
                                                            <option value="">Select Role</option>
                                                            <option value="remove">Remove Role</option>
                                                            @isset($roles)
                                                                @foreach ($roles as $role)
                                                                <option value="{{$role->id}}" @if ($user->role && $user->role->role_id == $role->id)
                                                                    selected
                                                                @endif>{{$role->name}}</option>
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                        <strong class="red">{{ $errors->first('role') }}</strong>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        <button type="submit" class="btn btn-sm btn-primary pull-right">Update Role</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                            @endif
                                    </div>
                                </div>                               

                            </div>
                        </div>

                
                    </div>
                </div>

            </div>
        </div>
    </div>



</div>
@endsection
