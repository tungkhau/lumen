<?php

namespace App\Preconditions;

class UserPrecondition
{
    public function create($params)
    {
        $workplace = app('db')->table('workplaces')->where('pk', $params['workplace_pk'])->value('name');
        switch ($params['role']) {
            case 'merchandiser':
            {
                if ($workplace != 'Văn phòng') return True;
                return False;
            }
            case 'manager':
            case 'staff':
            case 'inspector':
            {
                if ($workplace != 'Kho phụ liệu') return True;
                return False;
            }
            default :
            {
                if ($workplace == 'Văn phòng' || $workplace == 'Kho phụ liệu') return True;
                return False;
            }
        }
    }

    public function change_workplace($params)
    {
        $current_workplace_pk = app('db')->table('users')->where('pk', $params['user_pk'])->value('workplace_pk');
        if ($params['workplace_pk'] == $current_workplace_pk) return True;

        $workplace = app('db')->table('workplaces')->where('pk', $params['workplace_pk'])->value('name');
        if ($workplace == 'Văn phòng' || $workplace == 'Kho phụ liệu') return True;
        return False;
    }

    public function change_password($params)
    {
        $current_password = app('db')->table('users')->where('pk', $params['user_pk'])->value('password');
        if (!app('hash')->check($params['current_password'], $current_password)) return True;
        return False;
    }

}
