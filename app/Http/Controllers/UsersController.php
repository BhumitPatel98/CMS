<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.index')->with('users',User::all());
    }

    public function makeAdmin(User $user)
    {
       $user->role = 'admin';

       $user->save();

       session()->flash('success', 'User Made Admin Successfully');

       return redirect(route('users.index'));
    }

    public function edit()
    {

        return view('users.edit')->with('user',auth()->user());

    }


    public function update(UpdateProfileRequest $request, User $user)
    {
        $user = auth()->user();

        $user->update([

            'name' => $request->name,
            'about' => $request->about

        ]);

        session()->flash('success', 'User Update Successfully');

        return redirect()->back();
    }
    
}
