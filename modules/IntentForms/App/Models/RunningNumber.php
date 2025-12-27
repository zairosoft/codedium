<?php

namespace Modules\IntentForms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RunningNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'volume',
        'number',
    ];
}
