<?php

namespace Modules\DMS\App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DMSAlarm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'id',
        'device_id',
        'type',
        'detail',
        'latitude',
        'longitude',
        'speed',
        'img',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'dms_alarms';

}
