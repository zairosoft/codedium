<?php

namespace Modules\PageBuilder\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PageBuilder\Database\factories\PageBuilderFactory;

class PageBuilder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): PageBuilderFactory
    {
        //return PageBuilderFactory::new();
    }
}
