<?php

namespace Modules\Calendar\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Calendar\Database\factories\CalendarFactory;

class UserEvent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'event_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'event_users';
}
