<?php

namespace App\Repositories;

class EntryRepository
{

    public function adjust($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('adjusting_sessions')->insert([
                'pk' => $params['adjusting_session_pk'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('entries')->insert($params['entry']);
        });
    }

    public function discard($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('discarding_sessions')->insert([
                'pk' => $params['discarding_session_pk'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('entries')->insert($params['entry']);
        });
    }

    public function verify_adjusting($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('verifying_sessions')->insert([
                'pk' => $params['verifying_session_pk'],
                'user_pk' => $params['user_pk'],
                'kind' => 'adjusting',
            ]);
            app('db')->table('adjusting_sessions')->where('pk', $params['adjusting_session_pk'])->update([
                'verifying_session_pk' => $params['verifying_session_pk']
            ]);
            app('db')->table('entries')->where('session_pk', $params['adjusting_session_pk'])->update([
                'is_pending' => False,
                'result' => $params['result']
            ]);
        });
    }

    public function verify_discarding($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('verifying_sessions')->insert([
                'pk' => $params['verifying_session_pk'],
                'user_pk' => $params['user_pk'],
                'kind' => 'discarding',
            ]);
            app('db')->table('discarding_sessions')->where('pk', $params['discarding_session_pk'])->update([
                'verifying_session_pk' => $params['verifying_session_pk']
            ]);
            app('db')->table('entries')->where('session_pk', $params['adjusting_session_pk'])->update([
                'is_pending' => False,
                'result' => $params['result']
            ]);
        });
    }

    public function move($params)
    {
        app('db')->transaction(function () {

        });
    }


}
