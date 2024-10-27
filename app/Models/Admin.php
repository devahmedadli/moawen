<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'country',
        'phone',
        'password',
        'image',
        'bio',
        'status',
        'last_login_at',
        'last_login_ip',
        'role',
    ];
}
