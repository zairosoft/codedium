<?php

namespace App\Models;

use App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Company extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */



    protected $fillable = [
        'id',
        'tel',
        'mobile',
        'email',
        'websit',
        'tax_id',
        'company_id',
        'currency',
        'img',
        'created_by',
        'updated_by',
    ];
    protected $table = 'companies';
}
