<?php

namespace App\Interfaces;

interface UserInterface
{
    public function create($params);

    public function reset_password($key);

    public function deactivate($key);

    public function reactivate($key);

    public function change_workplace($params);


}
