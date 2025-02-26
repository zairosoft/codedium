<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Session;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
    }

    public function profile()
    {
        $account = Cache::remember('account' . Auth::user()->id, now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Profile::where('user_id', Auth::user()->id)->first();
        });
        return view('profile.profile', compact('account'));
    }

    public function edit()
    {
        $account = Cache::remember('account' . Auth::user()->id, now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Profile::where('user_id', Auth::user()->id)->first();
        });
        return view('profile.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Profile::where('user_id', Auth::user()->id)->update([
            'birthday' => date("Y-m-d", strtotime($request->birthday)),
            'gender' => $request->gender,
            'phone' => $request->phone,
            'website' => $request->website,
            'profession' => $request->profession,
            'address' => $request->address

        ]);
        if (!empty($request->image)) {
            if (Auth::user()->img != null) {
                @unlink(public_path('/assets/images/users/') . Auth::user()->img);
            }
            $imageName = Auth::user()->id . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/assets/images/users'), $imageName);
            User::where('id', Auth::user()->id)->update([
                'img' => $imageName,
            ]);
        }
        User::where('id', Auth::user()->id)->update([
            'name' => strtolower($request->name),
        ]);
        Cache::forget('account' . Auth::user()->id);
        return redirect()->route('profile')->withSuccess('บันทึกสำเร็จ');
    }

    public function resetpassword(Request $request)
    {
        $request->validate(
            [
                'password' => 'required|min:8|confirmed'
            ],
            [
                'required' => trans('validation.required'),
            ]
        );
        User::where('id', Auth::user()->id)->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->back()->withSuccess('บันทึกสำเร็จ');
    }
}
