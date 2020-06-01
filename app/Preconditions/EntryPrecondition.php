<?php

namespace App\Preconditions;

use App\Http\Controllers\EntryController;

class EntryPrecondition
{
    public function verify_adjusting($params)
    {
        return !$verified = app('db')->table('adjusting_sessions')->where('pk', $params['adjusting_session_pk'])->value('verifying_session_pk') == Null;
    }

    public function verify_discarding($params)
    {
        return !$verified = app('db')->table('discarding_sessions')->where('pk', $params['discarding_session_pk'])->value('verifying_session_pk') == Null;
    }

    public function move($params)
    {
        $inCased_items = $params['inCased_items'];
        foreach ($inCased_items as $inCased_item) {
            $current_quantity = EntryController::inCased_quantity($inCased_item['received_item_pk'], $params['start_case_pk']);
            if (!$current_quantity || $current_quantity < $inCased_item['quantity']) return True;
        }
        return False;
    }
}
