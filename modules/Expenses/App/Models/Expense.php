<?php

namespace Modules\Expenses\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'due_date',
        'reference_number',
        'payee',
        'vendor_name',
        'payment_method',
        'currency',
        'vat_exempt',
        'subtotal',
        'discount_percentage',
        'discount_amount',
        'vat_percentage',
        'vat_amount',
        'withholding_tax_percentage',
        'withholding_tax_amount',
        'total',
        'grand_total',
        'description',
        'notes',
        'project_id',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $table = 'expenses';

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'vat_exempt' => 'boolean',
        'subtotal' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_percentage' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'withholding_tax_percentage' => 'decimal:2',
        'withholding_tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Get the items for the expense.
     */
    public function items()
    {
        return $this->hasMany(ExpenseItem::class, 'expense_id');
    }

    /**
     * Get all attachments for the expense.
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }



}
