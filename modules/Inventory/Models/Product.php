<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Database\factories\ProductFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'discount_id',
        'barcode',
        'price',
        'cost',
        'model',
        'sku',
        'sku',
        'stock',
        'status',
        'publish_schedule',
    ];
    
    protected static function newFactory(): ProductFactory
    {
        //return InventoryFactory::new();
    }
}

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'img',
    ];
    
    protected static function newFactory(): CategoryFactory
    {
        //return InventoryFactory::new();
    }
}