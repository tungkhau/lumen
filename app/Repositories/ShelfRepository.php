<?php

namespace App\Repositories;

use Exception;

class ShelfRepository
{

    public function create($params)
    {
        try {
            app('db')->table('shelves')->insert([
                'name' => $params['shelf_name']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->table('shelves')->where('pk', $params['shelf_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
