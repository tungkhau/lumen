<?php

namespace App\Preconditions;

class CasePrecondition
{
    public function disable($params)
    {
        $issued_groups = app('db')->table('issued_groups')->where('case_pk', $params['case_pk'])->exists();
        $received_groups = app('db')->table('received_groups')->where('case_pk', $params['case_pk'])->exists();

        $entries = app('db')->table('entries')->where('case_pk', $params['case_pk'])->pluck('quantity');
        $inCased_quantity = 0;
        if (count($entries) > 0) {
            foreach ($entries as $entry) {
                if ($entry == Null) {
                    break;
                }
                $inCased_quantity += $entry;
            }
        }
        $contained = $inCased_quantity != 0;
        return $issued_groups || $received_groups || $contained;
    }

    public function store($params)
    {
        $received_groups = app('db')->table('received_groups')->where('case_pk', $params['case_pk'])->get();
        $passed = True;
        if (count($received_groups)) {
            foreach ($received_groups as $received_group) {
                if ($received_group->kind == 'imported') {
                    if (app('db')->table('imported_items')->join('classified_items', 'imported_items.classified_item_pk', '=', 'classified_items.pk')->where('imported_items.pk', $received_group->received_item_pk)->value('classified_items.quality_state') == 'passed' ? False : True) {
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
        return $start_shelf_pk == $params['end_shelf_pk'];
    }

}
