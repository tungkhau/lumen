<?php

namespace App\Preconditions;

class DemandPrecondition
{
    public function create($params)
    {
        $accessory_pks = array();
        foreach ($params['demanded_items'] as $demanded_item) {
            array_push($accessory_pks, $demanded_item['accessory_pk']);
        }
        $conception_pks = app('db')->table('accessories_conceptions')->whereIn('accessory_pk', $accessory_pks)->distinct('conception_pk')->pluck('conception_pk');
        foreach ($conception_pks as $conception_pk) {
            if ($params['conception_pk'] == $conception_pk) return False;
        }
        return True;
    }

    public function edit($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exists();
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return $consuming_sessions || !$owner;
    }

    public function delete($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exists();
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

    public function confirm_issuing($params)
    {
        $consuming_session = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->first();
        $returned = $consuming_session->returing_session_pk == Null ? False : True;
        $confirmed = $consuming_session->progressing_session_pk == Null ? False : True;
        $temp = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->join('demands', 'issuing_sessions.container_pk', '=', 'demands.pk')->value('demands.workplace_pk');
        $workplace = app('db')->table('users')->where('pk', $params['user_pk'])->value('workplace_pk') == $temp ? True : False;
        return $returned || $confirmed || !$workplace;
    }

    public function return_issuing($params)
    {
        $consuming_session = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->first();
        $returned = $consuming_session->returing_session_pk == Null ? False : True;
        $confirmed = $consuming_session->progressing_session_pk == Null ? False : True;
        return $returned || $confirmed;
    }
}
