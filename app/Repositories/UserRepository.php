<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    public function create($params)
    {
        app('db')->table('users')->insert([
            'id' => $params['user_id'],
            'name' => $params['user_name'],
            'role' => $params['role'],
            'workplace_pk' => $params['workplace_pk'],
            'password' => app('hash')->make(env('DEFAULT_PASSWORD'))
        ]);
    }

    public function reset_password($key)
    {
        app('db')->table('users')
            ->where('pk', $key)
            ->update(['password' => app('hash')->make(env('DEFAULT_PASSWORD'))]);
    }

    public function deactivate($key)
    {
        app('db')
            ->table('users')
            ->where('pk', $key)
            ->update(['is_active' => false]);
    }

    public function reactivate($key)
    {
        app('db')
            ->table('users')
            ->where('pk', $key)
            ->update(['is_active' => true]);
    }

    public function change_workplace($params)
    {
        app('db')
            ->table('users')
            ->where('pk', $params['user_pk'])
            ->update(['workplace_pk' => $params['workplace_pk']]);
    }
}
