<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */



    protected $fillable = [
        'user_id',
        'company_id',
        'gender',
        'birthday',
        'phone',
        'about',
        'profession',
        'website',
        'address',
    ];

    protected $table = 'user_accounts';

}
