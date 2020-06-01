<?php

namespace App\Repositories;

use Exception;

class RestorationRepository
{
    public function register($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('restorations')->insert([
                    'id' => $params['id'],
                    'pk' => $params['restoration_pk'],
                    'comment' => $params['comment'],
                    'user_pk' => $params['mediator_pk']
                ]);
                app('db')->table('restored_items')->insert($params['restored_items']);
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
                app('db')->table('restored_items')->where('restoration_pk', $params['restoration_pk'])->delete();
                app('db')->table('restorations')->where('pk', $params['restoration_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function confirm($params)
    {
        try {
            app('db')->table('restorations')->where('pk', $params['restoration_pk'])->update(['is_confirmed' => True]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function cancel($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('restored_items')->where('restoration_pk', $params['restoration_pk'])->delete();
                app('db')->table('restorations')->where('pk', $params['restoration_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function receive($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('receiving_sessions')->insert([
                    'kind' => 'restoring',
                    'pk' => $params['receiving_session_pk'],
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('received_groups')->insert($params['received_groups']);
                app('db')->table('cases')->whereIn('pk', $params['case_pks'])->update([
                    'shelf_pk' => Null
                ]);
                app('db')->table('restorations')->where('pk', $params['restoration_pk'])->update([
                    'receiving_session_pk' => $params['receiving_session_pk'],
                    'is_confirmed' => True
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
