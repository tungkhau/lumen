<?php

namespace App\Preconditions;

class DemandPrecondition
{
    public function create($params)
    {
        //TODO implement preconditions
        return False;
    }

    public function edit($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exist();
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return $consuming_sessions || !$owner;
    }

    public function delete($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exist();
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return $consuming_sessions || !$owner;

    }

    public function turn_off($params)
    {
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        $issued = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->count() >= 1 ? True : False;
        return !$owner || !$issued;

    }

    public function turn_on($params)
    {
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;

    }
}
