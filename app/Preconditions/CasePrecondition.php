<?php

namespace App\Preconditions;

class CasePrecondition
{
    public function disable($params)
    {
        //TODO implement preconditions
        return True;
    }

    public function store($params)
    {
        $received_groups = app('db')->table('received_groups')->where('case_pk', $params['case_pk'])->get();
        $passed = True;
        if (count($received_groups)) {
            foreach ($received_groups as $received_group) {
                if ($received_group->kind == 'imported') {
                    if (app('db')->table('imported_items')->join('classified_items', 'imported_items.classified_item_pk', '=', 'classified_items.pk')->where('imported_items.pk', $received_group->pk)->value('classified_items.quality_state') == 'passed' ? False : True) {
                        $passed = False;
                        break;
                    }
                }
            }
        }
        return !$passed;
    }

    public function replace($params)
    {
        $start_shelf_pk = app('db')->table('cases')->where('pk', $params['case_pk'])->value('shelf_pk');
        return $start_shelf_pk == $params['end_shelf_pk'] ? True : False;
    }

}
