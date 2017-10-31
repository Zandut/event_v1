<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{

    use Authenticatable, Authorizable;

    //
    protected $fillable = [
        'user_name', 'email', 'password' ,'role_id', 'api_key'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

     // define column to hidden
    protected $hidden = [
        'password',
    ];
}
