<?php

namespace Modules\Expenses\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Expenses\Database\factories\ExpenseFactory;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'reference_number',
        'category',
        'payee',
        'payment_method',
        'total',
        'description',
        'notes',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $table = 'expenses';

    /**
     * Get the items for the expense.
     */
    public function items()
    {
        return $this->hasMany(ExpenseItem::class, 'expense_id');
    }

    protected static function newFactory(): ExpenseFactory
    {
        //return ExpenseFactory::new();
    }
}
