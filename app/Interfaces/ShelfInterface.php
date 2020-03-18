<?php

namespace App\Interfaces;

interface ShelfInterface
{
    public function create($params);

    public function delete($key);

}
