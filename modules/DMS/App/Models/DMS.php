<?php

namespace Modules\DMS\App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DMS extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'id',
        'name',
        'device_name',
        'device_id',
        'tel',
        'car_type',
        'car_plate_number',
        'car_plate_number_sub',
        'img',

        'sim_number',
        'sim_network',
        'start_date',
        'end_date',
        'sim_type',
        'other',

        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'dms';

}
