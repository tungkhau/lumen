<?php

namespace App\Repositories;

use Exception;

class OrderRepository
{

    public function create($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('orders')->insert([
                    'pk' => $params['order_pk'],
                    'id' => $params['order_id'],
                    'supplier_pk' => $params['supplier_pk'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('ordered_items')->insert($params['ordered_items']);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function edit($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('ordered_items')->where('pk', $params['ordered_item_pk'])->update([
                    'ordered_quantity' => $params['ordered_quantity'],
                    'comment' => $params['comment']
                ]);
                app('db')->table('orders')->where('pk', $params['order_pk'])->update([
                    'created_date' => date('Y-m-d H:i:s')
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('ordered_items')->where('order_pk', $params['order_pk'])->delete();
                app('db')->table('orders')->where('pk', $params['order_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function turn_off($params)
    {
        try {
            app('db')->table('orders')->where('pk', $params['order_pk'])->update([
                'is_opened' => False
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function turn_on($params)
    {
        try {
            app('db')->table('orders')->where('pk', $params['order_pk'])->update([
                'is_opened' => True
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
