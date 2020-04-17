<?php

namespace App\ViewModels;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //Set default attributes
    public $timestamps = false;
    protected $pk;
    protected $primaryKey = 'pk';

}
