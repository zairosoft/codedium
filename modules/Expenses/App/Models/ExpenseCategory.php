<?php

namespace Modules\Expenses\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $table = 'expense_categories';

    /**
     * Get the items using this category.
     */
    public function items()
    {
        return $this->hasMany(ExpenseItem::class, 'category_id');
    }
}
