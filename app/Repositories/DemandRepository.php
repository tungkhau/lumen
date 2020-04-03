<?php

namespace App\Repositories;

use Exception;

class DemandRepository
{
    public function create($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('demands')->insert([
                    'id' => $params['id'],
                    'workplace_pk' => $params['workplace_pk'],
                    'product_quantity' => $params['product_quantity'],
                    'conception_pk' => $params['conception_pk'],
                    'user_pk' => $params['user_pk'],
                    'pk' => $params['demand_pk']
                ]);
                app('db')->table('demanded_items')->insert($params['demanded_items']);
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
                app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
                    'created_date' => date('Y-m-d H:i:s')
                ]);
                app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])->update([
                    'demanded_quantity' => $params['demanded_quantity'],
                    'comment' => $params['comment']
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
                app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])->delete();
                app('db')->table('demands')->where('pk', $params['demand_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function turn_off($params)
    {
        try {
            app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
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
            app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
                'is_opened' => True
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
