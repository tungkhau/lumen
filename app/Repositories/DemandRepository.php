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
                app('db')->table('demanded_items')->where('pk', $params['demanded_item_pk'])->update([
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

    public function confirm_issuing($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('progressing_sessions')->insert([
                    'pk' => $params['progressing_session_pk'],
                    'user_pk' => $params['user_pk'],
                    'kind' => 'confirming'
                ]);
                app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->update([
                    'progressing_session_pk' => $params['progressing_session_pk']
                ]);
                app('db')->table('issued_groups')->whereIn('pk', $params['issued_group_pks'])->update([
                    'case_pk' => Null
                ]);
                if (count($params['enabled_cases']) != 0) {
                    app('db')->table('cases')->whereIn('pk', $params['enabled_cases'])->update([
                        'is_active' => False
                    ]);
                }
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function return_issuing($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('returning_sessions')->insert([
                    'pk' => $params['returning_session_pk'],
                    'user_pk' => $params['user_pk'],
                ]);
                app('db')->table('entries')->insert($params['entries']);
                foreach ($params['pairs'] as $pair) {
                    app('db')->table('cases')->where('pk', $pair['case_pk'])->update(['shelf_pk' => $pair['shelf_pk']]);
                }
                app('db')->table('issued_groups')->whereIn('pk', $params['issued_group_pks'])->update([
                    'case_pk' => Null
                ]);
                app('db')->table('issued_items')->whereIn('pk', $params['issued_item_pks'])->delete();
                app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->update([
                    'returning_sesison_pk' => $params['returning_session_pk']
                ]);
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }


}
