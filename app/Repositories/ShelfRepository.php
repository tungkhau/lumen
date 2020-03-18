<?php

namespace App\Repositories;

use App\Interfaces\ShelfInterface;

class ShelfRepository implements ShelfInterface
{

    public function create($params)
    {
        app('db')->table('shelves')->insert([
            'name' => $params['shelf_name']
        ]);
    }

    public function delete($key)
    {
        app('db')->table('shelves')->where('pk', $key)->delete();
    }
}
