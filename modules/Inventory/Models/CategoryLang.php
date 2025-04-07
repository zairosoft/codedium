<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryLang extends Model
{
    use HasFactory;
    protected $table = 'product_category_langs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'slug',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the category that owns the translation.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
