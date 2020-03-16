<?php

namespace App\Repositories;

use App\Interfaces\WorkplaceInterface;

class WorkplaceRepository implements WorkplaceInterface
{

    public function create($key)
    {
        app('db')->table('workplaces')->insert(['name' => $key]);
    }

    public function delete($key)
    {
        app('db')->table('workplaces')->where('pk', $key)->delete();
    }
}
