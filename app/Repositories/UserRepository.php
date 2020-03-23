<?php

namespace App\Repositories;

use Exception;

class UserRepository
{
    public function create($params)
    {
        try {
            app('db')->table('users')->insert([
                'id' => $params['user_id'],
                'name' => $params['user_name'],
                'role' => $params['role'],
                'workplace_pk' => $params['workplace_pk'],
                'password' => app('hash')->make(env('DEFAULT_PASSWORD'))
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }

    public function reset_password($params)
    {
        try {
            app('db')->table('users')->where('pk', $params['user_pk'])
                ->update(['password' => app('hash')->make(env('DEFAULT_PASSWORD'))]);

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->table('users')->where('pk', $params['user_pk'])
                ->update(['is_active' => false]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            app('db')->table('users')->where('pk', $params['user_pk'])
                ->update(['is_active' => true]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function change_workplace($params)
    {
        try {
            app('db')->table('users')->where('pk', $params['user_pk'])
                ->update(['workplace_pk' => $params['workplace_pk']]);
        } catch (Exception $e) {
            return $e;
        }
        return False;

    }
}
