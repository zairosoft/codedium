<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Account;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Str;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:user view', ['only' => ['index']]);
        $this->middleware('permission:user create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:user delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        //echo timeAgo('2025-01-20 14:30:45');
        $users = Cache::remember('users', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return User::join('accounts', 'accounts.user_id', '=', 'users.id')
                ->offset(0)
                ->limit(10)
                ->get([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.img',
                    'users.last_logged_activities',
                    'accounts.gender',
                    'accounts.birthday',
                    'accounts.phone'
                ]);
        });
        return view('users.index', ['users' => $users]);
    }

    // public function show()
    // {
    //     $users = Cache::remember('usershow', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
    //         return User::join('accounts', 'accounts.user_id', '=', 'users.id')
    //             ->offset(0)
    //             ->limit(10)
    //             ->get([
    //                 'users.id',
    //                 'users.name',
    //                 'users.email',
    //                 'users.img',
    //                 'users.last_logged_activities',
    //                 'accounts.gender',
    //                 'accounts.birthday',
    //                 'accounts.phone'
    //             ]);
    //     });
    //     echo json_encode($users);
    // }

    public function show($id)
    {
        $users = User::find((int) $id);
        $account = Account::where('user_id', (int) $id)->first();
        return view('users.view', [
            'user' => $users,
            'account' => $account,
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.add', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'roles' => 'required'
            ],
            [
                'email.unique' => trans('customers.error.email'),
                'password' => trans('validation.required'),
                'name' => trans('validation.required'),
                'roles' => trans('validation.required'),
            ]
        );
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'img' => null,
            'role' => "user",
            'password' => Hash::make($request->password),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);
        $acc = ['user_id' => $user->id];
        if (!empty($request->gender))
            $acc += ['gender' => $request->gender];
        if (!empty($request->birthday))
            $acc += ['birthday' => date("Y-m-d", strtotime($request->birthday))];
        if (!empty($request->phone))
            $acc += ['phone' => $request->phone];
        if (!empty($request->website))
            $acc += ['website' => $request->website];
        if (!empty($request->profession))
            $acc += ['profession' => $request->profession];
        if (!empty($request->address))
            $acc += ['address' => $request->address];
        Account::create($acc);
        Cache::forget('users');
        $user->syncRoles($request->roles);
        return redirect('users')->withSuccess('บันทึกสำเร็จ');
    }

    public function edit(User $user)
    {
        $account = Account::where('user_id', (int) $user->id)->first();
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
            'account' => $account,

        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            //'password' => 'nullable|string|min:8|max:20',
            'roles' => 'required'
        ], [
            'name' => trans('validation.required'),
            'roles' => trans('validation.required'),
        ]);
        $data = [
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
        ];
        if (!empty($request->password)) {
            $data += [
                'password' => Hash::make($request->password),
            ];
        }
        if (!empty($request->image)) {
            if ($user->img != null) {
                @unlink(public_path('/assets/images/users/') . $user->img);
            }
            $imageName = $user->id . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/assets/images/users'), $imageName);
            $data += [
                'img' => $imageName,
            ];
        }
        $user->update($data);
        $user->syncRoles($request->roles);

        $acc = [
            'gender' => $request->gender,
        ];
        if (!empty($request->birthday))
            $acc += ['birthday' => date("Y-m-d", strtotime($request->birthday))];
        if (!empty($request->phone))
            $acc += ['phone' => $request->phone];
        if (!empty($request->website))
            $acc += ['website' => $request->website];
        if (!empty($request->profession))
            $acc += ['profession' => $request->profession];
        if (!empty($request->address))
            $acc += ['address' => $request->address];
        Account::where('user_id', $user->id)->update($acc);
        Cache::forget('users');
        return redirect('users')->withSuccess('บันทึกสำเร็จ');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        if (($request->id == 1) || ($request->id == Auth::user()->id)) {
            echo json_encode([
                "type" => "errors",
                "message" => "ไม่สามารถลบผู้ใช้งานนี้ได้"
            ]);
        } else {
            $user = User::find($request->id);
            if ($user->img != null) {
                @unlink(public_path('/assets/images/companies/') . $user->img);
            }
            $user->delete();
            $account = Account::find($request->id);
            $account->delete();
            Cache::forget('users');
            echo json_encode([
                "type" => "success",
                "message" => "ลบสำเร็จ"
            ]);
        }
    }
}
