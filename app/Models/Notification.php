<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Notification extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */


    protected $fillable = [
        'id',
        'user_id',
        'title',
        'type',
        'url',
        'is_readed',
        'description',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];
    protected $table = 'notifications';
}
