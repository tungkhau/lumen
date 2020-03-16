<?php

namespace App\Repositories;

use App\Interfaces\SupplierInterface;

class SupplierRepository implements SupplierInterface
{

    public function create($params)
    {
        app('db')->table('suppliers')->insert([
            'name' => $params['supplier_name'],
            'id' => $params['supplier_id'],
            'address' => $params['address'],
            'phone' => $params['phone']
        ]);
    }

    public function edit($params)
    {
        app('db')->table('suppliers')->where('pk', $params['supplier_pk'])->update([
            'address' => $params['address'],
            'phone' => $params['phone']
        ]);
    }

    public function delete($key)
    {
        app('db')->table('suppliers')->where('pk', $key)->delete();
    }

    public function deactivate($key)
    {
        app('db')->table('suppliers')->where('pk', $key)->update([
            'is_active' => False
        ]);
    }

    public function reactivate($key)
    {
        app('db')->table('suppliers')->where('pk', $key)->update([
            'is_active' => True
        ]);
    }
}
