<?php

namespace App\Repositories;

use Exception;

class CaseRepository
{

    public function create($params)
    {
        try {
            app('db')->table('cases')->insert([
                'id' => $params['case_id']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function disable($params)
    {
        try {
            app('db')->table('cases')->where('pk', $params['case_pk'])->update([
                'is_active' => false
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function store($params)
    {
        try {
            if ($params['count'] != 0) {
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

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function replace($params)
    {
        try {
            app('db')->table('replacing_sessions')->insert([
                'case_pk' => $params['case_pk'],
                'start_shelf_pk' => $params['start_shelf_pk'],
                'end_shelf_pk' => $params['end_shelf_pk'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('cases')->where('pk', $params['case_pk'])->update([
                'shelf_pk' => $params['end_shelf_pk']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
