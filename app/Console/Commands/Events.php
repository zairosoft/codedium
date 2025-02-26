<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Modules\Calendar\App\Models\Calendar;
use App\Models\Notification;
use Carbon\Carbon;

class Events extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Calendar::join('event_users', 'events.id', '=', 'event_users.event_id')
        ->whereDate('start_date', '<=', today())
        ->whereDate('end_date', '>=', today())
        ->where('is_active', 0)->get();
        foreach ($events as $event) {
            Notification::create([
                'user_id' => $event->user_id,
                'title' => 'Calendar',
                'type' => 'warning',
                'url' => url('/calendar'),
                'description' => $event->title,
            ]);
            Calendar::where('id', $event->id)->update(['is_active' => 1]);
        }
        $this->info('Update has been sent successfully');
    }
}
