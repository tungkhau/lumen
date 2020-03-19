<?php

namespace App\Interfaces;

interface OrderInterface
{
    public function create($params);

    public function edit($params);

    public function delete($key);

    public function turn_off($key);

    public function turn_on($key);
}
