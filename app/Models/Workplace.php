<?php

namespace App\Models;

class Workplace extends BaseModel
{
    protected $name;

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

}
