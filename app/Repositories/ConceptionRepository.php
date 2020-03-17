<?php

namespace App\Repositories;

use App\Interfaces\ConceptionInterface;

class ConceptionRepository implements ConceptionInterface
{

    public function create($params)
    {
        app('db')->table('conceptions')->insert([
            'customer_pk' => $params['customer_pk'],
            'id' => $params['id'],
            'name' => $params['conception_name'],
            'year' => $params['year'],
            'comment' => $params['comment'],
        ]);
    }

    public function delete($key)
    {
        app('db')->table('conceptions')->where('pk', $key)->delete();
    }

    public function deactivate($key)
    {
        app('db')->table('conceptions')->where('pk', $key)->update([
            'is_active' => False
        ]);
    }

    public function reactivate($key)
    {
        app('db')->table('conceptions')->where('pk', $key)->update([
            'is_active' => True
        ]);
    }

    public function link_accessory($params)
    {
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => $params['customer_pk'],
            'conception_pk' => $params['conception_pk']
        ]);
    }

    public function unlink_accessory($params)
    {
        app('db')->table('accessories_conceptions')->where('accessory_pk', $params['accessory_pk'])
            ->where('conception_pk', $params['conception_pk'])
            ->delete();
    }
}
