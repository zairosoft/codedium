<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:role view', ['only' => ['index', 'view']]);
        $this->middleware('permission:role create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Cache::remember('role', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Role::get();
        });
        return view('role.index', ['roles' => $roles]);
    }

    public function create()
    {
        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name', 'id')->groupBy('name')->get();
        $permissions = array();
        foreach ($role_permission as $per) {
            $key = substr($per->name, 0, strpos($per->name, " "));
            if (str_starts_with($per->name, $key)) {
                $permissions[$key][] = $per;
            }
        }
        return view('role.add', [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        app()['cache']->forget('spatie.permission.cache');
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ],
            'permission' => 'required'
        ], [
            'name' => trans('validation.required'),
            'permission' => trans('validation.required'),
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);
        $role = Role::findOrFail($role->id);
        //$role->syncPermissions($request->permission);

        $role->permissions()->sync($request->permission);
        Cache::forget('role');
        return redirect('roles')->withSuccess('บันทึกสำเร็จ');
    }

    public function edit(Role $role)
    {
        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name', 'id')->groupBy('name')->get();
        $permissions = array();
        foreach ($role_permission as $per) {
            $key = substr($per->name, 0, strpos($per->name, " "));
            if (str_starts_with($per->name, $key)) {
                $permissions[$key][] = $per;
            }
        }

        $role = Role::findOrFail($role->id);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('role.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $role->id
            ],
            'permission' => 'required'
        ], [
            'name' => trans('validation.required'),
            'permission' => trans('validation.required'),
        ]);

        $role = Role::findOrFail($role->id);
        $role->syncPermissions($request->permission);

        $role->update([
            'name' => $request->name
        ]);
        Cache::forget('role');
        return redirect('roles')->withSuccess('บันทึกสำเร็จ');
    }
    public function show($id)
    {
        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name', 'id')->groupBy('name')->get();
        $permissions = array();
        foreach ($role_permission as $per) {
            $key = substr($per->name, 0, strpos($per->name, " "));
            if (str_starts_with($per->name, $key)) {
                $permissions[$key][] = $per;
            }
        }

        $role = Role::findOrFail($id);
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('role.view', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        if ($request->id == 1) {
            echo json_encode(["message" => "errors"]);
        } else {
            $role = Role::find($request->id);
            $role->delete();
            Cache::forget('role');
            echo json_encode(["success" => $request->id]);
        }
    }
}
