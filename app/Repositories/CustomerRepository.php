<?php

namespace App\Repositories;

use App\Interfaces\CustomerInterface;

class CustomerRepository implements CustomerInterface
{

    public function create($params)
    {
        app('db')->table('customers')->insert([
            'name' => $params['customer_name'],
            'id' => $params['customer_id'],
            'address' => $params['address'],
            'phone' => $params['phone']
        ]);
    }

    public function edit($params)
    {
        app('db')->table('customers')->where('pk', $params['customer_pk'])->update([
            'address' => $params['address'],
            'phone' => $params['phone']
        ]);
    }

    public function delete($key)
    {
        app('db')->table('customers')->where('pk', $key)->delete();
    }

    public function deactivate($key)
    {
        app('db')->table('customers')->where('pk', $key)->update([
            'is_active' => False
        ]);
    }

    public function reactivate($key)
    {
        app('db')->table('customers')->where('pk', $key)->update([
            'is_active' => True
        ]);
    }
}
