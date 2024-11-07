<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DmcUsers extends Model
{
    use SoftDeletes;
    protected $table = 'dmc_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'uid',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'phone_2',
        'postal_code',
        'birth_date',
        'gender',
    ];

}
