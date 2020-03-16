<?php

namespace App\Repositories;

use App\Interfaces\DeviceInterface;

class DeviceRepository implements DeviceInterface
{

    public function register($params)
    {
        app('db')->table('devices')->insert([
            'id' => $params['device_id'],
            'name' => $params['device_name']
        ]);
    }

    public function delete($key)
    {
        app('db')->table('devices')->where('pk', $key)->delete();
    }
}
