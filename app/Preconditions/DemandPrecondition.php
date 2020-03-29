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

    }

    public function turn_off($params)
    {

    }

    public function turn_on($params)
    {

    }
}
