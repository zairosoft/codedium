<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImg extends Model
{
    use HasFactory;
    protected $table = 'product_imgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'img',
        'sort_order'
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
