<?php

namespace Modules\DMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\DMS\App\Models\DMS;
use Modules\DMS\App\Models\DMSAlarm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Notification;
use Modules\DMS\App\Http\Resources\DMSResource;

class ApiDMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = DMS::all();
        if (is_null($all)) {
            return $this->sendError('Data not found.');
        } else {
            return responseResult([
                'dms' => DMSResource::collection($all),
            ], [], "Success", 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'device_id' => 'required',
            'type' => 'required',
            'img' => 'required',
        ]);

        if ($validator->fails()) {
            return responseResult([$validator->errors()], [], "Validation Error", 200);
        }

        DMS::where('device_id', $request->device_id)->update([
            'alarm' => $request->type,
        ]);

        $image = $request->img;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid() . time() . '.jpg';
        File::put(public_path('/modules/dms/images/alarms') . '/' . $imageName, base64_decode($image));

        $action = '';
        if ($request->type == 'actionEye') {
            $action = 'ผู้ขับขี่หลับตา';
        } elseif ($request->type == 'actionLooking') {
            $action = 'ผู้ขับขี่ละสายตาจากด้านหน้า';
        } elseif ($request->type == 'actionYawning') {
            $action = 'ผู้ขับขี่หาว';
        } elseif ($request->type == 'actionPhone') {
            $action = 'ผู้ขับขี่ใช้โทรศัพท์มือถือ';
        } elseif ($request->type == 'actionCigarette') {
            $action = 'ผู้ขับขี่สูบบุหรี่';
        } else {
            $action = 'ไม่ระบุ';
        }

        $data['detail'] = $action;
        $data['img'] = $imageName;
        DMSAlarm::create($data);

        // สร้าง Notification ให้กับ User ที่มี Permission dms view
        //$users = User::permission('dms view')->get();

        $users = DB::table('permissions')
            ->leftJoin('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->leftJoin('model_has_roles', 'role_has_permissions.role_id', '=', 'model_has_roles.role_id')
            ->where('permissions.name', 'dms view')
            ->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->model_id,
                'title' => 'DMS Alarm',
                'type' => 'warning',
                'url' => url('/dms/monitor'),
                'description' => $action,
            ]);
        }
        return responseResult([], [], "บันทึกข้อมูลเรียบร้อย", 200);
    }
}
