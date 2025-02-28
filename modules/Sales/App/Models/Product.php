<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Database\factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'sale_products';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'type',
        'product_code',
        'product_name',
        'unit',
        'created_at',
        'updated_at',
    ];
    
    protected static function newFactory(): ProductFactory
    {
        //return SaleFactory::new();
    }
}
