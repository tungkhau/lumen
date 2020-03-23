<?php

namespace App\Interfaces;

interface CaseInterface
{
    public function create($key);

    public function disable($key);

    public function store($params);

}
