<?php

namespace App\Preconditions;

class ReceivedGroupPrecondition
{
    public function count($params)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $params['received_group_pk'])->first();
        $opened = False;
        $classified = False;
        $imported = $received_group->kind == 'imported' ? True : False;
        if ($imported) {
            $imported_item_pk = $received_group->received_item_pk;
            $imported_item = app('db')->table('imported_items')->where('pk', $imported_item_pk)->select('import_pk', 'classified_item_pk')->first();
            $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');

            $classified = $imported_item->classified_item_pk == Null ? False : True;
        }
        $counted = $received_group->counting_session_pk == Null ? False : True;
        $stored = $received_group->storing_session_pk == Null ? False : True;

        return $opened || $classified || $counted || $stored;
    }

    public function edit_counting($params)
    {
        $owner = app('db')->table('counting_sessions')->where('pk', $params['counting_session_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        $received_group = app('db')->table('received_groups')->where('counting_session_pk', $params['counting_session_pk'])->first();
        $classified = False;
        if ($received_group->kind == 'imported' ? True : False) {
            $classified = app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('classified_item_pk') ? True : False;
        }
        $stored = $received_group->storing_session_pk == Null ? False : True;
        return !$owner || $classified || $stored;
    }

    public function delete_counting($params)
    {
        $owner = app('db')->table('counting_sessions')->where('pk', $params['counting_session_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        $received_group = app('db')->table('received_groups')->where('counting_session_pk', $params['counting_session_pk'])->first();
        $classified = False;
        if ($received_group->kind == 'imported' ? True : False) {
            $classified = app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('classified_item_pk') ? True : False;
        }
        $stored = $received_group->storing_session_pk == Null ? False : True;
        return !$owner || $classified || $stored;
    }

    public function check($params)
    {
        $imported_group = app('db')->table('received_groups')->where('pk', $params['imported_group_pk'])->first();
        $stored = $imported_group->storing_session_pk == Null ? False : True;
        $checked = $imported_group->checking_session_pk == Null ? False : True;

        $imported_item = app('db')->table('imported_items')->where('pk', $imported_group->received_item_pk)->select('import_pk', 'classified_item_pk')->first();
        $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');
        $classified = $imported_item->classified_item_pk == Null ? False : True;

        return $stored || $checked || $opened || $classified;
    }

    public function edit_checking($params)
    {
        $imported_group = app('db')->table('received_groups')->where('checking_session_pk', $params['checking_session_pk'])->first();
        $stored = $imported_group->storing_session_pk == Null ? False : True;
        $classified = app('db')->table('imported_items')->where('pk', $imported_group->received_item_pk)->value('classified_item_pk') ? True : False;
        $owner = app('db')->table('checking_sessions')->where('pk', $params['checking_session_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return $stored || $classified || !$owner;
    }

    public function delete_checking($params)
    {
        $imported_group = app('db')->table('received_groups')->where('checking_session_pk', $params['checking_session_pk'])->first();
        $stored = $imported_group->storing_session_pk == Null ? False : True;
        $classified = app('db')->table('imported_items')->where('pk', $imported_group->received_item_pk)->value('classified_item_pk') ? True : False;
        $owner = app('db')->table('checking_sessions')->where('pk', $params['checking_session_pk'])->value('user_pk') == $params['user_pk'];
        return $stored || $classified || !$owner;
    }

    public function arrange($params)
    {
        $received_group_pks = array();
        foreach ($params['received_groups'] as $received_group) {
            array_push($received_group_pks, $received_group['received_group_pk']);
        }
        return app('db')->table('received_groups')->whereIn('pk', $received_group_pks)->where('storing_session_pk', '!=', Null)->exists();
    }

    public function store($params)
    {
        $received_groups = app('db')->table('received_groups')->whereIn('pk', array_values($params['received_groups']))->get();
        $passed = True;
        foreach ($received_groups as $received_group) {
            if ($received_group->kind == 'imported') {
                if (app('db')->table('imported_items')->join('classified_items', 'imported_items.classified_item_pk', '=', 'classified_items.pk')->where('imported_items.pk', $received_group->received_item_pk)->value('classified_items.quality_state') == 'passed' ? False : True) {
                    $passed = False;
                    break;
                }
            }
        }
        return !$passed;
    }
}
