<?php

namespace Modules\IntentForms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\IntentForms\Database\factories\IntentformFactory;


class Intentform extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'volume',
        'number',
        'account_name',
        'account_number',
        'account_bank',
        'refer',
        'name',
        'status',
        'date',
        'other',
        'payee',
        'payment_methods',
        'total',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $table = 'intentforms';

    public function donations()
    {
        return $this->hasMany(Donation::class, 'intentform_id');
    }
}
