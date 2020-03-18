<?php

namespace App\Interfaces;

interface BlockInterface
{
    public function open($params);

    public function close($key);
}
