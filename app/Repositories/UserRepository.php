<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    public function create($params)
    {
        $default_password = app('hash')->make('PDG@123');
        return app('db')->table('users')->insert([
            'id' => $params->id,
            'name' => $params->name,
            'role' => $params->role,
            'workplace_pk' => $params->workplace_pk,
            'password' => $default_password,
        ]);
    }

    public function reset_password($user_pk)
    {
        $default_password = app('hash')->make('PDG@123');
        return app('db')->table('users')
            ->where('user_pk', $user_pk)
            ->update(['password' => $default_password]);
    }

    public function deactivate($user_pk)
    {
        $is_active = app('db')
            ->table('user')
            ->where('pk', $user_pk)
            ->value('is_active');
        if ($is_active == true) {
            return app('db')
                ->table('users')
                ->where('user_pk', $user_pk)
                ->update(['is_active' => false]);
        } else return false;
    }

    public function reactivate($user_pk)
    {
        $is_active = app('db')
            ->table('user')
            ->where('pk', $user_pk)
            ->value('is_active');
        if ($is_active == false) {
            return app('db')
                ->table('users')
                ->where('user_pk', $user_pk)
                ->update(['is_active' => true]);
        } else return false;
    }

    public function change_workplace($user_pk, $workplace_pk)
    {
        $role = app('db')
            ->table('user')
            ->where('pk', $user_pk)
            ->value('role');
        if ($role == 'mediator') {
            return app('db')
                ->table('users')
                ->where('user_pk', $user_pk)
                ->update(['workplace_pk' => $workplace_pk]);
        } else return false;
    }
}
