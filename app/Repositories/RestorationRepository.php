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
                    'user_pk' => $params['user_pk']
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
                app('db')->table('restorations')->where('pk', $params['restoration_pk'])->delete();
                app('db')->table('restored_items')->where('restoration_pk', $params['restoration_pk'])->delete();
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
                app('db')->table('restorations')->where('pk', $params['restoration_pk'])->delete();
                app('db')->table('restored_items')->where('restoration_pk', $params['restoration_pk'])->delete();
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
                    'pk' => $params['restoring_session_pk']
                ]);
                foreach ($params['restored_groups'] as $restored_group) {
                    app('db')->table('received_groups')->insert([
                        'kind' => 'restored',
                        'received_item_pk' => $restored_group['restored_item_pk'],
                        'grouped_quantity' => $restored_group['grouped_quantity'],
                        'receiving_session_pk' => $params['restoring_session_pk'],
                        'case_pk' => $restored_group['case_pk']
                    ]);
                }
                app('db')->table('cases')->whereIn('pk', $params['used_case_pks'])->update([
                    'shelf_pk' => Null
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
