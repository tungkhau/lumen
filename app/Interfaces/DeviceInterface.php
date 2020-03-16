<?php

namespace App\Interfaces;

interface DeviceInterface
{
    public function register($params);

    public function delete($key);
}
