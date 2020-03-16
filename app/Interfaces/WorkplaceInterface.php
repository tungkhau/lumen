<?php

namespace App\Interfaces;

interface WorkplaceInterface
{
    public function create($key);

    public function delete($key);
}
