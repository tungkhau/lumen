<?php

namespace App\Repositories;

use Exception;

class EntryRepository
{

    public function adjust($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('adjusting_sessions')->insert([
                    'pk' => $params['adjusting_session_pk'],
                    'user_pk' => $params['user_pk'],
                    'quantity' => $params['quantity']
                ]);
                app('db')->table('entries')->insert($params['entry']);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function discard($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('discarding_sessions')->insert([
                    'pk' => $params['discarding_session_pk'],
                    'user_pk' => $params['user_pk'],
                    'quantity' => $params['quantity']
                ]);
                app('db')->table('entries')->insert($params['entry']);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function verify_adjusting($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('verifying_sessions')->insert([
                    'pk' => $params['verifying_session_pk'],
                    'user_pk' => $params['user_pk'],
                    'kind' => 'adjusting',
                    'result' => $params['result']
                ]);
                app('db')->table('adjusting_sessions')->where('pk', $params['adjusting_session_pk'])->update([
                    'verifying_session_pk' => $params['verifying_session_pk']
                ]);
                app('db')->table('entries')->where('session_pk', $params['adjusting_session_pk'])->update([
                    'quantity' => $params['result'] ? $params['quantity'] : 0
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function verify_discarding($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('verifying_sessions')->insert([
                    'pk' => $params['verifying_session_pk'],
                    'user_pk' => $params['user_pk'],
                    'kind' => 'discarding',
                    'result' => $params['result']
                ]);
                app('db')->table('discarding_sessions')->where('pk', $params['discarding_session_pk'])->update([
                    'verifying_session_pk' => $params['verifying_session_pk']
                ]);
                app('db')->table('entries')->where('session_pk', $params['discarding_session_pk'])->update([
                    'quantity' => $params['result'] ? $params['quantity'] : 0
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function move($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('moving_sessions')->insert([
                    'start_case_pk' => $params['start_case_pk'],
                    'end_case_pk' => $params['end_case_pk'],
                    'user_pk' => $params['user_pk'],
                    'pk' => $params['moving_session_pk']
                ]);
                app('db')->table('entries')->insert($params['outEntries']);
                app('db')->table('entries')->insert($params['inEntries']);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }


}
