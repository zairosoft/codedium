<?php

namespace Modules\DMS\App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SimType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'id',
        'name',
        'type',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'dms_sim_types';

}
