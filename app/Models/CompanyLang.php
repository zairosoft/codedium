<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyLang extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */



    protected $fillable = [
        'id',
        'company_id',
        'code',
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip'
    ];
    protected $table = 'company_langs';
}
