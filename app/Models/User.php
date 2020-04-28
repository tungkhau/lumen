<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'users';
    protected $hidden = [
        'password', 'api_token'
    ];
    protected $fillable = ['id', 'name', 'role', 'password'];
    private $id;
    private $name;
    private $password;
    private $role;
    private $is_active;

    public function workplace()
    {
        return $this->belongsTo('App\Models\Workplace');
    }

}
