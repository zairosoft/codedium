<?php

namespace Modules\Register\App\Http\Controllers;


use App\Models\User;
use Modules\Register\App\Models\Register;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('register::index');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'email.unique' => trans('customers.error.email'),
                'required' => trans('validation.required'),
            ]
        );
        $user = User::insertGetId([
            'name' => strtolower($request->name),
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => "user",
            'img' => null
        ]);

        $userRoles = User::whereId((int)$user)->first();
        $userRoles->syncRoles('user');

        Register::create([
            'user_id' => (int)$user
        ]);
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('profile');
        //return redirect()->route('profile')->withSuccess('You have successfully registered & logged in!');
    }
}