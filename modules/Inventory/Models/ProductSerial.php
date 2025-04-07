<?php

namespace Modules\Inventory\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSerial extends Model
{
    use HasFactory;
    protected $table = 'product_serials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'serial_number',
        'status', // Available, Sold, Reserved, etc.
        'notes'
    ];

    /**
     * Get the product that owns the serial.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
