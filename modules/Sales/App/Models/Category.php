<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Database\factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'sale_product_types';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'type_name',
        'created_at',
        'updated_at',
    ];
    
    protected static function newFactory(): CategoryFactory
    {
        //return SaleFactory::new();
    }
}
