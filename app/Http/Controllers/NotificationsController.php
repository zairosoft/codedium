<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
    }

    public function index(Request $request)
    {
        $response = @new StreamedResponse(function () use ($request) {
            $startTime = time();
            while (time() - $startTime < 30) {
                $notifications = Notification::where('is_readed', 0)
                    ->where('user_id', Auth::user()->id)
                    ->where('is_seen', 0)
                    ->get();
                if ($notifications->isNotEmpty()) {
                    foreach ($notifications as $notification) {
                        Notification::where('id', $notification->id)->where('user_id', Auth::user()->id)->update(['is_seen' => 1]);
                        echo "data: " . $notifications . "\n\n";
                    }
                }
                ob_flush();
                flush();
                sleep(5);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        return $response;
    }

    public function show()
    {
        // create cache for 1 minute
        $data = [];
        $notifications = Cache::remember('notifications'.Auth::user()->id.'', now()->addMinutes(1), function () {
            return Notification::where('user_id', Auth::user()->id)
                ->where('is_readed', 0)
                ->orderBy('created_at', 'desc')
                //->groupBy(['type','title','description'])
                ->limit(15)
                ->get();
        });
        foreach ($notifications as $notification) {
            if ($notification->type == "danger") {
                $icon = '<span class="grid place-content-center w-9 h-9 rounded-full bg-danger-light dark:bg-danger text-danger dark:text-danger-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>';
            } elseif ($notification->type == "success") {
                $icon = '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>';
            } elseif ($notification->type == "warning") {
                $icon = '<span class="grid place-content-center w-9 h-9 rounded-full bg-warning-light dark:bg-warning text-warning dark:text-warning-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>';
            } else {
                $icon = '<span class="grid place-content-center w-9 h-9 rounded-full bg-info-light dark:bg-info text-info dark:text-info-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span>';
            }
            $data[] = [
                'id' => $notification->id,
                'user_id' => $notification->user_id,
                'type' => $notification->type,
                'title' => $notification->title,
                'icon' => $icon,
                'url' => $notification->url,
                'message' => '<strong class="text-sm mr-1">' . $notification->title . ': </strong>' . $notification->description,
                'time' => timeAgo($notification->created_at),
            ];
        }
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        Notification::where('id', $request->id)
            ->update([
                'is_readed' => 1,
                'readed_at' => now()
            ]);
        Cache::forget('notifications'.Auth::user()->id.'');
        return response()->json(['success' => 'Notification has been deleted.']);
    }
}
