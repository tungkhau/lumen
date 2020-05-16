<?php

namespace App\ViewModels;

class ReceivedGroup extends ViewModel
{
    public function get($params)
    {
        $kind = $params['kind'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $externality_filtered_object = $this->_externality_filter($externality, $kind_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'imported' || $kind == Null) {
            $pks = app('db')->table('received_groups')->where('kind', 'imported')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'imported'
                ];
            }
        }
        if ($kind == 'restored' || $kind == Null) {
            $pks = app('db')->table('received_groups')->where('kind', 'restored')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'restored'
                ];
            }
        }
        return $objects;
    }

    private function _externality_filter($externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('receiving_pks', $externality)) {
            $temp = array();
            foreach ($externality['receiving_pks'] as $receiving_pk) {
                $received_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk');
                if (count($received_item_pks) == 0) {
                    $received_item_pks = app('db')->table('restored_items')->where('restoration_pk', $receiving_pk)->pluck('pk');
                    $temp = array_merge(app('db')->table('received_groups')->whereIn('received_item_pk', $received_item_pks)->pluck('pk')->toArray(), $temp);
                } else {
                    $temp = array_merge(app('db')->table('received_groups')->whereIn('received_item_pk', $received_item_pks)->pluck('pk')->toArray(), $temp);
                }
            }
            $pks = array_intersect($temp, $pks);
        }
        if ($externality != Null && array_key_exists('received_item_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('received_item_pk', $externality['received_item_pks'])->pluck('pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('case_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('case_pk', $externality['case_pks'])->pluck('pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('receiving_session_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('receiving_session_pk', $externality['receiving_session_pks'])->pluck('pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('arranging_session_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups_arranging_sessions')->whereIn('arranging_session_pk', $externality['arranging_session_pks'])->pluck('received_group_pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('storing_session_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('storing_session_pk', $externality['storing_session_pks'])->pluck('pk')->toArray(), $pks);
        }
        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'imported') {
                $imported_group = app('db')->table('received_groups')->where('pk', $item['pk'])->first();
                $case_id = app('db')->table('cases')->where('pk', $imported_group->case_pk)->value('id');
                $checking_quantity = $this::imported_checking_quantity($imported_group->pk);
                $input_object[$key] += [
                    'caseId' => $case_id,
                    'groupedQuantity' => $imported_group->grouped_quantity,
                    'actualQuantity' => $this::actual_quantity($imported_group->pk),
                    'isCounted' => $imported_group->counting_session_pk == Null ? False : True,
                    'isCountable' => $this::is_countable($imported_group->pk),
                    'isCheckable' => $this::is_checkable($imported_group->pk),
                    'isStorable' => $this::is_storable($imported_group->pk),
                    'checkedQuantity' => $checking_quantity['checked_quantity'],
                    'unqualifiedQuantity' => $checking_quantity['unqualified_quantity'],
                    'received_item_pk' => $imported_group->received_item_pk,
                ];
            }
            if ($item['kind'] == 'restored') {
                $restored_group = app('db')->table('received_groups')->where('pk', $item['pk'])->first();
                $case_id = app('db')->table('cases')->where('pk', $restored_group->case_pk)->value('id');
                $input_object[$key] += [
                    'caseId' => $case_id,
                    'groupedQuantity' => $restored_group->grouped_quantity,
                    'actualQuantity' => $this::actual_quantity($restored_group->pk),
                    'isCounted' => $restored_group->counting_session_pk == Null ? False : True,
                    'isCountable' => $this::is_countable($restored_group->pk),
                    'isStorable' => $this::is_storable($restored_group->pk),
                    'received_item_pk' => $restored_group->received_item_pk,
                ];
            }
        }
        return $this::received_item_translation($input_object);
    }

    public static function imported_checking_quantity($imported_group_pk)
    {
        $checking_session_pk = app('db')->table('received_groups')->where('pk', $imported_group_pk)->value('checking_session_pk');
        if ($checking_session_pk == Null) {
            $checking_quantity['checked_quantity'] = null;
            $checking_quantity['unqualified_quantity'] = null;
        } else {
            $checking_session = app('db')->table('checking_sessions')->where('pk', $checking_session_pk)->first();
            $checking_quantity['checked_quantity'] = $checking_session->checked_quantity;
            $checking_quantity['unqualified_quantity'] = $checking_session->unqualified_quantity;
        }
        return $checking_quantity;
    }

    public static function actual_quantity($received_group_pk)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->first();
        if ($received_group->counting_session_pk == Null) return $received_group->grouped_quantity;
        return app('db')->table('counting_sessions')->where('pk', $received_group->counting_session_pk)->value('counted_quantity');
    }

    public static function is_countable($received_group_pk)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->first();
        $opened = False;
        $classified = False;
        $imported = $received_group->kind == 'imported';
        if ($imported) {
            $imported_item_pk = $received_group->received_item_pk;
            $imported_item = app('db')->table('imported_items')->where('pk', $imported_item_pk)->select('import_pk', 'classified_item_pk')->first();
            $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');

            $classified = $imported_item->classified_item_pk == Null ? False : True;
        }
        $counted = $received_group->counting_session_pk == Null ? False : True;
        $stored = $received_group->storing_session_pk == Null ? False : True;

        return !$opened && !$classified && !$counted && !$stored;
    }

    public static function is_checkable($received_group_pk)
    {
        $imported_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->first();
        if ($imported_group == Null) return False;
        $stored = $imported_group->storing_session_pk == Null ? False : True;
        $checked = $imported_group->checking_session_pk == Null ? False : True;

        $imported_item = app('db')->table('imported_items')->where('pk', $imported_group->received_item_pk)->select('import_pk', 'classified_item_pk')->first();
        $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');
        $classified = $imported_item->classified_item_pk == Null ? False : True;
        return !$opened && !$classified && !$checked && !$stored;
    }

    private static function is_storable($received_group_pk)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->first();
        if ($received_group->kind == 'imported') {
            if (ReceivedItem::imported_quality_state($received_group->received_item_pk) == 'passed') return True;
            return False;
        }
        return True;
    }

    public function get_cased_received_group($params)
    {
        $accessory_pk = $params['externality']['accessory_pk'];
        $ordered_item_pks = app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->pluck('pk');
        $imported_item_pks = app('db')->table('imported_items')->whereIn('ordered_item_pk', $ordered_item_pks)->pluck('pk');
        $pks = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->where('case_pk', '!=', Null)->pluck('pk')->toArray();

        $restored_item_pks = app('db')->table('restored_items')->where('accessory_pk', $accessory_pk)->pluck('pk');
        $pks = array_merge($pks, app('db')->table('received_groups')->whereIn('received_item_pk', $restored_item_pks)->where('case_pk', '!=', Null)->pluck('pk')->toArray());

        $cased_received_groups = app('db')->table('received_groups')->whereIn('pk', $pks)->select('pk', 'kind')->get();

        $object = array();
        foreach ($cased_received_groups as $cased_received_group) {
            $object[] = [
                'pk' => $cased_received_group->pk,
                'kind' => $cased_received_group->kind
            ];
        }
        return $this->_translation($object);
    }


}
