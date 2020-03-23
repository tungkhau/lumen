<?php

namespace App\Repositories;

use Exception;

class WorkplaceRepository
{
    public function create($params)
    {
        try {
            app('db')->table('workplaces')->insert(['name' => $params['workplace_name']]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->table('workplaces')->where('pk', $params['workplace_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
