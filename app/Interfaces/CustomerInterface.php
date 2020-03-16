<?php

namespace App\Interfaces;

interface CustomerInterface
{
    public function create($params);

    public function edit($params);

    public function delete($key);

    public function deactivate($key);

    public function reactivate($key);
}
