<?php

namespace Modules\Website\App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'id',
        'is_published',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'pages';
}
