<?php

namespace App\Preconditions;

class RestorationPrecondition
{
    public function register($params) {
        $mediator = app('db')->table('users')->where('pk', $params['user_pk'])->value('role') == 'mediator' ? True : False;
        return !$mediator;
    }
    public function confirm($params) {
        $owner = app('db')->table('restorations')->where('pk', $params['restoration_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;
    }
    public function cancel($params) {
        $owner = app('db')->table('restorations')->where('pk', $params['restoration_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;
    }
    public function receive($params) {
        //TODO implement precondition
        return False;
    }
}
