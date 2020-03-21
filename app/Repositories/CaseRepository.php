<?php

namespace App\Repositories;

use App\Interfaces\CaseInterface;

class CaseRepository implements CaseInterface
{

    public function create($key)
    {
        app('db')->table('cases')->insert([
            'id' => $key
        ]);
    }

    public function disable($key)
    {
        app('db')->table('cases')->where('pk', $key)->update([
            'is_active' => false
        ]);
    }

}
