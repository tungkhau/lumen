<?php

namespace App\Repositories;

use App\Interfaces\AccessoryInterface;

class AccessoryRepository implements AccessoryInterface
{

    public function create($params)
    {
        app('db')->table('accessories')->insert([
            'id' => $params['id'],
            'customer_pk' => $params['customer_pk'],
            'supplier_pk' => $params['supplier_pk'],
            'type_pk' => $params['type_pk'],
            'unit_pk' => $params['unit_pk'],
            'item' => $params['item'],
            'art' => $params['art'],
            'color' => $params['color'],
            'size' => $params['size'],
            'name' => $params['accessory_name'],
            'comment' => $params['comment'],
        ]);
    }

    public function delete($key)
    {
        app('db')->table('accessories')->where('pk', $key)->delete();
    }

    public function deactivate($key)
    {
        app('db')->table('accessories')->where('pk', $key)->update([
            'is_active' => False
        ]);
    }

    public function reactivate($key)
    {
        app('db')->table('accessories')->where('pk', $key)->update([
            'is_active' => True
        ]);
    }

    public function upload_photo($params)
    {
        // TODO: Implement upload_photo() method.
    }

    public function delete_photo($key)
    {
        // TODO: Implement delete_photo() method.
    }
}
