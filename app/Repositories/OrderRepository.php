<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use Illuminate\Support\Str;

class OrderRepository implements OrderInterface
{

    public function create($params)
    {
        $params['order_pk'] = (string)Str::uuid();
        foreach ($params['ordered_items'] as $key => $value) {
            $params['ordered_items'][$key]['order_pk'] = $params['order_pk'];
        }
        app('db')->transaction(function () use ($params) {
            app('db')->table('orders')->insert([
                'pk' => $params['order_pk'],
                'id' => $params['order_id'],
                'supplier_pk' => $params['supplier_pk'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('ordered_items')->insert($params['ordered_items']);
        });
    }

    public function edit($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('ordered_items')->where('pk', $params['ordered_item_pk'])->update([
                'ordered_quantity' => $params['ordered_quantity'],
                'comment' => $params['comment']
            ]);
            app('db')->table('orders')->where('pk', $params['order_pk'])->update([
                'created_date' => date('Y-m-d H:i:s')
            ]);
        });
    }

    public function delete($key)
    {
        app('db')->transaction(function () use ($key) {
            app('db')->table('ordered_items')->where('order_pk', $key)->delete();
            app('db')->table('orders')->where('pk', $key)->delete();
        });
    }

    public function turn_off($key)
    {
        app('db')->table('orders')->where('pk', $key)->update([
            'is_opened' => False
        ]);
    }

    public function turn_on($key)
    {
        app('db')->table('orders')->where('pk', $key)->update([
            'is_opened' => True
        ]);
    }
}
