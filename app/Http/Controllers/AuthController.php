<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'profile',
        ]);
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $remember = $request->has('remember') ? true : false;
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $account = Account::where([['user_id', '=', $user->id]])->first();

            $settings = Settings::get();
            // Check if user is enabled
            if ((int)$user->is_active == 1) {
                // Regenerate session
                $request->session()->regenerate();
                Session::put('company_id', $account->company_id);

                foreach ($settings as $setting) {
                    Session::put($setting->key, $setting->value);
                }

                // update last logged datetime
                $last_logged = array(
                    "OS" => getOS(),
                    "Browser" => getBrowser(),
                    "IP" => getIP(),
                );
                $user->last_logged_activities = json_encode($last_logged);
                $user->last_logged_at = date('Y-m-d H:i:s');
                $user->save();
                //return redirect()->route('profile')->withSuccess('You have successfully logged in!');
                return redirect()->route('profile');
            } else {
                Auth::logout();
                return back()->withErrors(['error' => trans('auth.error.disabled'),]);
            }
        }
        return back()->withErrors(['error' => trans('auth.error.incorrect'),]);
    }

    /**
     * Display a forgot form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function forgot()
    {
        return view('auth.forgot');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotpassword(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('email', '=', $credentials['email'])->first();
        if (!empty($user)) {
            $user->remember_token = Str::random(40);
            $user->save();
            Mail::to($user->email)->send(new ForgotPassword($user));
            return redirect()->route('checkemail')->withSuccess('You have successfully!');
        } else {
            return back()->withErrors(['email' => 'ไม่พบอีเมลนี้ในระบบ',])->onlyInput('email');
        }
    }

    /**
     * Display a reset form.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function reset($token)
    {
        $user = User::where('remember_token', '=', $token)->first();
        if (!empty($user)) {
            $data['user'] = $user;
            return view('auth.reset', $data);
        } else {
            abort(404);
        }
    }

    public function resetpassword($token, Request $request)
    {
        $credentials = $request->validate([
            'password' => 'required|min:8',
            'cpassword' => 'required'
        ]);
        $user = User::where('remember_token', '=', $token)->first();
        if (!empty($user)) {
            if ($credentials['password'] == $credentials['cpassword']) {
                $user->remember_token = Str::random(40);
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect()->route('login')->withSuccess('You have successfully registered & logged in!');
            } else {
                return back()->withErrors(['error' => 'รหัสผ่านไม่ตรงกัน',]);
            }
        } else {
            abort(404);
        }
    }

    public function check()
    {
        return view('auth.check');
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget('company_id');
        Session::flush();
        //Cookie::forget('remember');
        return redirect()->route('main')->withSuccess('You have logged out successfully!');;
    }
}
