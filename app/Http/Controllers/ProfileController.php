<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
        return view('profiles.show',[
            'user' => $user,
        ]);
        
    }

    public function store (Request $request)
    { 
        if ($request->hasFile('avatar'))
        {
            $file = $request->file('avatar');
            $filename = $file->getClientOriginalName();
            $filename = time(). '.' . $filename;
            $filePath = '/images/' . $filename;
            Storage::disk('public')->put($filePath, file_get_contents($file));

            $user = auth()->user(); //update database
            $user->update(['avatar'=> $filename]);
            
        }
        
        return redirect()->back();
        
    } 


}
