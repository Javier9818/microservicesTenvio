<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model 
{
    
    protected $fillable = [
        'username', 'password', 'persona_id', 'email', 'isAdmin', 'isCustomer'
    ];

    

    
}
