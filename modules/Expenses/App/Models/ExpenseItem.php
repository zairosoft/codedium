<?php

namespace Modules\Expenses\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'expense_id',
        'category_id',
        'description',
        'quantity',
        'unit_price',
        'sub_total'
    ];

    protected $table = 'expense_items';

    /**
     * Get the category for this item.
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    /**
     * Get the expense that owns this item.
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }
}
