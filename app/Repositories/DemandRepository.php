<?php

namespace App\Repositories;

class DemandRepository
{
    public function create($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('demands')->insert([
                'id' => $params['id'],
                'workplace_pk' => $params['workplace_pk'],
                'conception_pk' => $params['conception_pk'],
                'user_pk' => $params['user_pk'],
                'pk' => $params['demand_pk']
            ]);
            app('db')->table('demanded_items')->insert($params['demanded_items']);
        });
    }

    public function edit($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
                'created_date' => date('Y-m-d H:i:s')
            ]);
            app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])->udpate([
                'demanded_quantity' => $params['demanded_quantity'],
                'comment' => $params['comment']
            ]);
        });
    }

    public function delete($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('demands')->where('pk', $params['demand_pk'])->delete();
            app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])->delete();
        });
    }

    public function turn_off($params)
    {
        app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
            'is_opened' => False
        ]);
    }

    public function turn_on($params)
    {
        app('db')->table('demands')->where('pk', $params['demand_pk'])->update([
            'is_opened' => True
        ]);
    }
}
