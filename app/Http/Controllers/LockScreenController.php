<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LockScreenController extends Controller
{

    public function lockscreen()
    {
        // only if user is logged in
        if (Auth::check()) {
            Session::put('locked', true);
            return view('auth.lockscreen');
        }
        return redirect('/auth/login');
    }

    public function authenticate(Request $request)
    {
        // if user in not logged in
        $request->validate([
            'password' => 'required'
        ]);
        if (!Auth::check())
            return redirect('/login');
        $password = $request->password;
        if (Hash::check($password, Auth::user()->password)) {
            Session::forget('locked');
            return redirect('/profile');
        } else {
            return back()->withErrors(['error' => trans('auth.error.incorrect'),]);
        }
    }
}
