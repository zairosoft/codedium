<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Database\factories\SaleFactory;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'invoice',
        'date',
        'refer',
        'status',
        'contact_code',
        'customer_name',
        'product_code',
        'product_name',
        'amount',
        'unit',
        'price',
        'discount',
        'pre_tax',
        'tax',
        'total_vat',
        'total',
        'sale_name',
        'created_at',
        'updated_at',
    ];
    
    protected static function newFactory(): SaleFactory
    {
        //return SaleFactory::new();
    }
}
