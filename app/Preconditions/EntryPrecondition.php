<?php

namespace App\Preconditions;

class EntryPrecondition
{
    public function verify_adjusting($params)
    {
        return !$verified = app('db')->table('adjusting_sessions')->where('pk', $params['adjusting_session_pk'])->value('verifying_session_pk') == Null ? True : False;
    }

    public function verify_discarding($params)
    {
        return !$verified = app('db')->table('discarding_sessions')->where('pk', $params['discarding_session_pk'])->value('verifying_session_pk') == Null ? True : False;
    }

}
