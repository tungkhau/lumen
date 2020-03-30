<?php

namespace App\Preconditions;

class UserPrecondition
{
    public function create($params)
    {
        //TODO Implement precondition
        return False;
    }

    public function change_workplace($params)
    {
        //TODO Implement precondition
        return False;
    }

    public function change_password($params)
    {
        $current_password = app('db')->table('users')->where('pk', $params['user_pk'])->value('password');
        if (!app('hash')->check($params['current_password'], $current_password)) return True;
        return False;
    }

}
