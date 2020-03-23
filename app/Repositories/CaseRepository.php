<?php

namespace App\Repositories;

use App\Interfaces\CaseInterface;

class CaseRepository implements CaseInterface
{

    public function create($key)
    {
        app('db')->table('cases')->insert([
            'id' => $key
        ]);
    }

    public function disable($key)
    {
        app('db')->table('cases')->where('pk', $key)->update([
            'is_active' => false
        ]);
    }

    public function store($params)
    {
        if ($params['count']) {
            app('db')->transaction(function () use ($params) {
                app('db')->table('storing_sessions')->insert([
                    'pk' => $params['storing_session_pk'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('entries')->insert($params['entries']);
                app('db')->table('received_groups')->whereIn('pk', $params['received_group_pks'])->update([
                    'case_pk' => Null
                ]);
                app('db')->table('cases')->where('pk', $params['case_pk'])->update([
                    'shelf_pk' => $params['shelf_pk']
                ]);
            });
        }
        app('db')->table('cases')->where('pk', $params['case_pk'])->update([
            'shelf_pk' => $params['shelf_pk']
        ]);
    }
}
