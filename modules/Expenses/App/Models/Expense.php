<?php

namespace Modules\Expenses\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Expenses\Database\factories\ExpenseFactory;

class expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): ExpenseFactory
    {
        //return ExpenseFactory::new();
    }
}
