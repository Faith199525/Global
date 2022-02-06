<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->q) {
            $users = User::where( function($query) use( $request){

             $query->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('email', 'like', '%' . $request->q . '%')
                ->orWhere('student_id', 'like', '%' . $request->q . '%');
             })   
                ->orderBy('name', 'asc')
                ->paginate(20);

            return view('admin.users.index')->with('users', $users);
        }

        $users = User::whereDoesntHave('role')->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}
