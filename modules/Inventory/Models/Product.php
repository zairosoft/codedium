<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Database\factories\ProductFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'category_id',
        'discount_id',
        'barcode',
        'price',
        'cost',
        'model',
        'img',
        'sku',
        'stock',
        'status',
        'publish_schedule',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the translations for the product.
     */
    public function translations()
    {
        return $this->hasMany(ProductLang::class, 'product_id');
    }

    /**
     * Get the product images.
     */
    public function images()
    {
        return $this->hasMany(ProductImg::class, 'product_id');
    }

    /**
     * Get the product serials.
     */
    public function serials()
    {
        return $this->hasMany(ProductSerial::class, 'product_id');
    }

    protected static function newFactory()
    {
        //return ProductFactory::new();
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
