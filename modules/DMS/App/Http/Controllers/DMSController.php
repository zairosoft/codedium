<?php

namespace Modules\DMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DMS\App\Models\DMS;
use Modules\DMS\App\Models\Sim;
use Modules\DMS\App\Models\SimType;
use Modules\DMS\App\Models\DMSAlarm;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class DMSController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:dms view', ['only' => ['index', 'view']]);
        $this->middleware('permission:dms create', ['only' => ['create', 'store']]);
        $this->middleware('permission:dms update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:dms delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dms = Cache::remember('dms', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return DMS::get();
        });
        return view('dms::index', [
            'dms' => $dms,
        ]);
    }

    public function overview()
    {

        $today = DMSAlarm::whereDate('created_at', Carbon::today())->count();
        $yesterday = DMSAlarm::whereDate('created_at', Carbon::yesterday())->count();
        $thisweek = DMSAlarm::where('created_at', '>', Carbon::now()->startOfWeek())->where('created_at', '<', Carbon::now()->endOfWeek())->count();
        $lastweek = DMSAlarm::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

        $thismonth = DMSAlarm::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->count();
        $lastmonth = DMSAlarm::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();

        $thisyear = DMSAlarm::whereYear('created_at', Carbon::now()->year)->count();
        $lastyear = DMSAlarm::whereYear('created_at', Carbon::now()->subYear()->year)->count();


        $actionEye = DMSAlarm::where('type', 'actionEye')->whereYear('created_at', Carbon::now()->year)->count();
        $actionLooking = DMSAlarm::where('type', 'actionLooking')->whereYear('created_at', Carbon::now()->year)->count();
        $actionYawning = DMSAlarm::where('type', 'actionYawning')->whereYear('created_at', Carbon::now()->year)->count();
        $actionPhone = DMSAlarm::where('type', 'actionPhone')->whereYear('created_at', Carbon::now()->year)->count();
        $actionCigarette = DMSAlarm::where('type', 'actionCigarette')->whereYear('created_at', Carbon::now()->year)->count();

        $dmsCount = DMS::count();
        $userDms = DB::table('permissions')
            ->leftJoin('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('permissions.name', 'dms view')
            ->count();

        $lists = DMS::orderBy('updated_at', 'DESC')->get();

        return view('dms::overview', [
            'today' => $today,
            'yesterday' => $yesterday,
            'thisweek' => $thisweek,
            'lastweek' => $lastweek,
            'thismonth' => $thismonth,
            'lastmonth' => $lastmonth,
            'thisyear' => $thisyear,
            'lastyear' => $lastyear,
            'dms' => (int)DMS::count(),
            'actionEye' => $actionEye,
            'actionLooking' => $actionLooking,
            'actionYawning' => $actionYawning,
            'actionPhone' => $actionPhone,
            'actionCigarette' => $actionCigarette,
            'dmsCount' => $dmsCount,
            'userDms' => $userDms,
            'lists' => $lists,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sims = Sim::get();
        $types = SimType::get();
        return view('dms::create', [
            'sims' => $sims,
            'types' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
            ],
            [
                'name' => trans('validation.required'),
            ]
        );
        $data = ['name' => $request->name];
        if (!empty($request->device_name))
            $data += ['device_name' => $request->device_name];
        if (!empty($request->device_id))
            $data += ['device_id' => $request->device_id];
        if (!empty($request->tel))
            $data += ['tel' => $request->tel];
        if (!empty($request->car_type))
            $data += ['car_type' => $request->car_type];
        if (!empty($request->car_plate_number))
            $data += ['car_plate_number' => $request->car_plate_number];
        if (!empty($request->car_plate_number_sub))
            $data += ['car_plate_number_sub' => $request->car_plate_number_sub];
        if (!empty($request->sim_number))
            $data += ['sim_number' => $request->sim_number];
        if (!empty($request->sim_network))
            $data += ['sim_network' => $request->sim_network];
        if (!empty($request->sim_type))
            $data += ['sim_type' => $request->sim_type];
        if (!empty($request->start_date))
            $data += ['start_date' => $request->start_date];
        if (!empty($request->end_date))
            $data += ['end_date' => $request->end_date];
        if (!empty($request->other))
            $data += ['other' => $request->other];
        if (!empty($request->image)) {
            $imageName = date("Ymd") . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/modules/dms/images'), $imageName);
            $data += ['img' => $imageName];
        }
        DMS::create($data);

        Cache::forget('dms');
        return redirect('dms')->withSuccess('บันทึกสำเร็จ');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $dms = DMS::find($id);
        return view('dms::show', [
            'dms' => $dms,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dms = DMS::find($id);
        return view('dms::edit', [
            'dms' => $dms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'id' => 'required',
            ],
            [
                'name' => trans('validation.required'),
            ]
        );

        $data = ['name' => $request->name];
        if (!empty($request->device_name))
            $data += ['device_name' => $request->device_name];
        if (!empty($request->device_id))
            $data += ['device_id' => $request->device_id];
        if (!empty($request->tel))
            $data += ['tel' => $request->tel];
        if (!empty($request->car_type))
            $data += ['car_type' => $request->car_type];
        if (!empty($request->car_plate_number))
            $data += ['car_plate_number' => $request->car_plate_number];
        if (!empty($request->car_plate_number_sub))
            $data += ['car_plate_number_sub' => $request->car_plate_number_sub];
        if (!empty($request->image)) {
            $imageName = date("Ymd") . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/modules/dms/images'), $imageName);
            $data += ['img' => $imageName];
        }
        DMS::find($request->id)->update($data);
        Cache::forget('dms');
        return redirect('dms')->withSuccess('บันทึกสำเร็จ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $dms = DMS::find($request->id);
        if ($dms->img != null) {
            @unlink(public_path('/assets/modules/dms/images/') . $dms->img);
        }
        $dms->delete();
        Cache::forget('dms');
        echo json_encode([
            "type" => "success",
            "message" => "ลบสำเร็จ"
        ]);
    }

    public function monitor()
    {
        $dms = DMS::orderBy('updated_at', 'DESC')->get();
        return view('dms::monitor', [
            'dms' => $dms,
        ]);
    }

    public function ajax($id)
    {
        $alarms = DMSAlarm::where('device_id', $id)->orderBy('id', 'DESC')->limit(1)->get();
        $logs = DMSAlarm::where('device_id', $id)->orderBy('id', 'DESC')->limit(5)->get();

        $dms = DMS::where('device_id', $id)->get();
        DMS::where('device_id', $id)->update([
            'alarm' => null,
        ]);
        return view('dms::ajax', [
            'alarms' => $alarms,
            'dms' => $dms,
            'logs' => $logs,
        ]);
    }

    public function history()
    {
        $dms = Cache::remember('history', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return DMS::get();
        });

        return view('dms::history', [
            'dms' => $dms,
        ]);
    }
    public function log($id)
    {
        $logs = DMSAlarm::where('device_id', $id)->get();
        return view('dms::log', [
            'logs' => $logs,
        ]);
    }
}
