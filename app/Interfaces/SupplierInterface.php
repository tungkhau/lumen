<?php

namespace App\Interfaces;

interface SupplierInterface
{
    public function create($params);

    public function edit($params);

    public function delete($key);

    public function deactivate($key);

    public function reactivate($key);
}
