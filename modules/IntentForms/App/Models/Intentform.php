<?php

namespace Modules\IntentForms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\IntentForms\Database\factories\IntentformFactory;

class Intentform extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): IntentformFactory
    {
        //return IntentformFactory::new();
    }
}
