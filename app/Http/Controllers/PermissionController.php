<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:permission view', ['only' => ['index']]);
        $this->middleware('permission:permission create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:permission delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Cache::remember('permissions', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Permission::get();
        });
        return view('permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('permission.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ], [
            'name' => trans('validation.required'),
        ]);
        Permission::create([
            'name' => $request->name
        ]);
        Cache::forget('permissions');
        echo json_encode(["success" => "บันทึกสำเร็จ"]);
        Session()->flash("success", 'บันทึกสำเร็จ');
    }

    public function edit(Permission $permission)
    {
        return view('permission.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,' . $permission->id
            ]
        ], [
            'name' => trans('validation.required'),
        ]);

        $permission->update([
            'name' => $request->name
        ]);
        Cache::forget('permissions');
        echo json_encode(["success" => "บันทึกสำเร็จ"]);
        Session()->flash("success", 'บันทึกสำเร็จ');
    }

    public function view($id)
    {
        $permission = Permission::find($id);
        return view('permission.view', [
            'permission' => $permission
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $permission = Permission::find($request->id);
        $permission->delete();
        Cache::forget('permissions');
        echo json_encode(["success" => $request->id]);
        //return redirect('roles')->withSuccess('ลบสำเร็จ');
    }
}
