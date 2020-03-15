<?php

namespace App\Interfaces;

interface UserInterface
{
    public function create($params);

    public function reset_password($user_pk);

    public function deactivate($user_pk);

    public function reactivate($user_pk);

    public function change_workplace($user_pk, $workplace_pk);


}
