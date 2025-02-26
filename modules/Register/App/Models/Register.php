<?php

namespace Modules\Register\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Register\Database\factories\RegisterFactory;

class Register extends Model
{
    use HasFactory;
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
    ];
    
    protected static function newFactory(): RegisterFactory
    {
        //return RegisterFactory::new();
    }
}
