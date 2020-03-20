<?php

namespace App\Repositories;

use App\Interfaces\ImportInterface;
use Illuminate\Support\Str;

class ImportRepository implements ImportInterface
{

    public function create($params)
    {
        $params['import_pk'] = (string)Str::uuid();
        foreach ($params['imported_items'] as $key => $value) {
            $params['imported_items'][$key]['import_pk'] = $params['import_pk'];
        }
        app('db')->transaction(function () use ($params) {
            app('db')->table('imports')->insert([
                'pk' => $params['import_pk'],
                'id' => $params['id'],
                'user_pk' => $params['user_pk'],
                'order_pk' => $params['order_pk']
            ]);
            app('db')->table('imported_items')->insert($params['imported_items']);
        });
    }

    public function edit($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('imported_items')->where('pk', $params['imported_item_pk'])->update([
                'imported_quantity' => $params['imported_quantity'],
                'comment' => $params['comment']
            ]);
            app('db')->table('imports')->where('pk', $params['import_pk'])->update([
                'created_date' => date('Y-m-d H:i:s')
            ]);
        });
    }

    public function delete($key)
    {
        app('db')->transaction(function () use ($key) {
            app('db')->table('imported_items')->where('import_pk', $key)->delete();
            app('db')->table('imports')->where('pk', $key)->delete();
        });
    }

    public function turn_off($key)
    {
        app('db')->table('imports')->where('pk', $key)->update([
            'is_opened' => False
        ]);
    }

    public function turn_on($key)
    {
        app('db')->table('imports')->where('pk', $key)->update([
            'is_opened' => True
        ]);
    }

    public function receive($params)
    {
        // TODO: Implement receive() method.
    }

    public function edit_receiving($params)
    {
        // TODO: Implement edit_receiving() method.
    }

    public function delete_receiving($key)
    {
        // TODO: Implement delete_receiving() method.
    }
}
