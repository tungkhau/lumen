<?php

namespace App\Interfaces;

interface RestorationInterface
{
    public function register($params);

    public function delete($key);

    public function confirm($key);

    public function cancel($key);

    public function receive($params);
}
