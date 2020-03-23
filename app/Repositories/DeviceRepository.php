<?php

namespace App\Repositories;

use Exception;

class DeviceRepository
{
    public function register($params)
    {
        try {
            app('db')->table('devices')->insert([
                'id' => $params['device_id'],
                'name' => $params['device_name']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->table('devices')->where('pk', $params['device_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
