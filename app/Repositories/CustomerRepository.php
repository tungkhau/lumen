<?php

namespace App\Repositories;

use Exception;

class CustomerRepository
{

    public function create($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['customer_id'],
                    'type' => 'create',
                    'object' => 'customer',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('customers')->insert([
                    'name' => $params['customer_name'],
                    'id' => $params['customer_id'],
                    'address' => $params['address'],
                    'phone' => $params['phone']
                ]);
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
                app('db')->table('activity_logs')->insert([
                    'id' => $params['customer_id'],
                    'type' => 'update',
                    'object' => 'customer',
                    'user_pk' => $params['user_pk']
                ]);

            });
            app('db')->table('customers')->where('pk', $params['customer_pk'])->update([
                'address' => $params['address'],
                'phone' => $params['phone']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['customer_id'],
                    'type' => 'delete',
                    'object' => 'customer',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('customers')->where('pk', $params['customer_pk'])->delete();
            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['customer_id'],
                    'type' => 'deactivate',
                    'object' => 'customer',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('customers')->where('pk', $params['customer_pk'])->update([
                    'is_active' => False
                ]);

            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['customer_id'],
                    'type' => 'reactivate',
                    'object' => 'customer',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('customers')->where('pk', $params['customer_pk'])->update([
                    'is_active' => True
                ]);
            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
