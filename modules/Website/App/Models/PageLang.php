<?php

namespace Modules\Website\App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLang extends Model
{
    protected $fillable = [
        'page_id',
        'code',
        'name',
        'slug',
        'content',
        'keywords',
        'description',

    ];

    protected $table = 'page_langs';
}
