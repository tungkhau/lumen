<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //Set default attributes
    public $timestamps = false;
    protected $pk;
    protected $primaryKey = 'pk';

}
