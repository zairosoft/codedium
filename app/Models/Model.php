<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Auth;

class Model extends Eloquent
{

    public static function boot()
    {
        parent::boot();

        // create a event to happen on updating
        static::creating(function ($model) {
            $model->created_by = @Auth::user()->id != '' ? @Auth::user()->id : 1;
            $model->updated_by = @Auth::user()->id != '' ? @Auth::user()->id : 1;
        });

        // create a event to happen on updating
        static::updating(function ($model) {
            $model->updated_by = @Auth::user()->id != '' ? @Auth::user()->id : 1;
        });

        // create a event to happen on deleting
        static::deleting(function ($model) {
            $model->deleted_by = @Auth::user()->id != '' ? @Auth::user()->id : 1;
        });
    }
}
