<?php

namespace Modules\Calendar\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\Calendar\App\Models\UserEvent;
use Modules\Calendar\App\Models\Calendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('is_active', 1)->where('id', '!=', Auth::user()->id)->get();
        $events = Cache::remember('events'.Auth::user()->id.'', now()->addMinutes(10), function () {
            return Calendar::join('event_users', 'events.id', '=', 'event_users.event_id')
            ->select('events.*', 'event_users.user_id')
            ->where('event_users.user_id', Auth::user()->id)
            ->get();
        });
        return view('calendar::index', [
            'users' => $users,
            'events' => $events,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $event = Calendar::create($request->all());
        $event = UserEvent::create([
            'event_id' => $event->id,
            'user_id' => Auth::user()->id
        ]);

        Cache::forget('events'.Auth::user()->id.'');

        echo json_encode([
            "type" => "success",
            "id" => $event->id,
            "message" => "บันทึกสําเร็จ"
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        $data = [
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'link' => $request->link,
            'description' => $request->description,
            'badge' => $request->badge
        ];
        Calendar::where('id', $request->id)->update($data);
        Cache::forget('events'.Auth::user()->id.'');

        echo json_encode([
            "type" => "success",
            "id" => $request->id,
            "message" => "บันทึกสําเร็จ"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $calendar = Calendar::find($request->id);
        $calendar->delete();

        UserEvent::where('event_id', $request->id)->delete();
        Cache::forget('events'.Auth::user()->id.'');

        echo json_encode([
            "type" => "success",
            "id" => $request->id,
            "message" => "บันทึกสําเร็จ"
        ]);
    }
}
