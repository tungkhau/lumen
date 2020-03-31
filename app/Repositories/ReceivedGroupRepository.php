<?php

namespace App\Repositories;

use Exception;

class ReceivedGroupRepository
{

    public function count($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('counting_sessions')->insert([
                    'pk' => $params['counting_session_pk'],
                    'counted_quantity' => $params['counted_quantity'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('received_groups')->where('pk', $params['received_group_pk'])->update([
                    'counting_session_pk' => $params['counting_session_pk']]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function edit_counting($params)
    {
        try {
            app('db')->table('counting_sessions')->where('pk', $params['counting_session_pk'])->update([
                'counted_quantity' => $params['counted_quantity'],
                'executed_date' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete_counting($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('received_groups')->where('counting_session_pk', $params['counting_session_pk'])->update([
                    'counting_session_pk' => Null
                ]);
                app('db')->table('counting_sessions')->where('pk', $params['counting_session_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function check($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('checking_sessions')->insert([
                    'pk' => $params['checking_session_pk'],
                    'checked_quantity' => $params['checked_quantity'],
                    'unqualified_quantity' => $params['unqualified_quantity'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('received_groups')->where('pk', $params['imported_group_pk'])->update([
                    'checking_session_pk' => $params['checking_session_pk']
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function edit_checking($params)
    {
        try {
            app('db')->table('checking_sessions')->where('pk', $params['checking_session_pk'])->update([
                'checked_quantity' => $params['checked_quantity'],
                'unqualified_quantity' => $params['unqualified_quantity'],
                'executed_date' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete_checking($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('received_groups')->where('checking_session_pk', $params['checking_session_pk'])->update([
                    'checking_session_pk' => Null
                ]);
                app('db')->table('checking_sessions')->where('pk', $params['checking_session_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function arrange($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('arranging_sessions')->insert([
                    'pk' => $params['arranging_session_pk'],
                    'start_case_pk' => $params['start_case_pk'],
                    'end_case_pk' => $params['end_case_pk'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('received_groups_arranging_sessions')->insert($params['received_groups_arranging_sessions']);
                app('db')->table('received_groups')->whereIn('pk', $params['received_group_pks'])->update([
                    'case_pk' => $params['end_case_pk']
                ]);
                app('db')->table('cases')->where('pk', $params['end_case_pk'])->update([
                    'shelf_pk' => Null
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function store($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('storing_sessions')->insert([
                    'pk' => $params['storing_session_pk'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('entries')->insert($params['entries']);
                app('db')->table('received_groups')->whereIn('pk', array_values($params['received_groups']))->update([
                    'case_pk' => Null
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
