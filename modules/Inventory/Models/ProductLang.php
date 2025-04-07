<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLang extends Model
{
    use HasFactory;
    protected $table = 'product_langs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'code',
        'name',
        'manufacturer_name',
        'manufacturer_brand',
        'brand',
        'description',
        'short_description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'note'
    ];

    /**
     * Get the product that owns the translation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
