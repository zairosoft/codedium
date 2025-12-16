<?php

namespace Modules\IntentForms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'type_id',
        'intentform_id',
        'quantity',
        'sub_total',
        'description'
    ];

    protected $table = 'intentform_donations';

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function intentform()
    {
        return $this->belongsTo(Intentform::class, 'intentform_id');
    }
}
