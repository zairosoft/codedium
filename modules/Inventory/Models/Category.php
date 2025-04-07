<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Database\factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'img',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get the translations for the category.
     */
    public function translations()
    {
        return $this->hasMany(CategoryLang::class, 'category_id');
    }

    protected static function newFactory()
    {
        //return CategoryFactory::new();
    }
}
