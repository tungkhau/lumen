<?php

namespace App\Preconditions;

class ReceivedGroupPrecondition
{
    public function store($params)
    {
        $received_groups = app('db')->table('received_groups')->whereIn('pk', array_values($params['received_groups']))->get()->toArray();
        $passed = True;
        foreach ($received_groups as $received_group) {
            if ($received_group['kind'] == 'imported') {
                if (app('db')->table('imported_items')->join('classified_items', 'imported_items.classified_item_pk', '=', 'classified_items.pk')->where('imported_items.pk', $received_group['received_item_pk'])->value('classified_items.quality_state') == 'passed' ? False : True) {
                    $passed = False;
                    break;
                }
            }
        }
        return !$passed;
    }

}
