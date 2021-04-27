<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'full_name',
        'address',
        'email',
        'user_id',
        'photo'
    ];
}
