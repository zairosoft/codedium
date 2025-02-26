<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Database\factories\ProductFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'product_categories';

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