<?php

namespace App\ViewModels;

use App\Http\Controllers\ReceivedGroupController;

class IssuedGroup extends ViewModel
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
        if ($kind == 'consumed' || $kind == Null) {
            $pks = app('db')->table('issued_groups')->where('kind', 'consumed')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'consumed'
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
        if ($externality != Null && array_key_exists('issuing_pks', $externality)) {
            $pks = array_intersect(app('db')->table('issued_groups')->whereIn('issuing_session_pk', $externality['issuing_pks'])->pluck('pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('case_pks', $externality)) {
            $pks = array_intersect(app('db')->table('issued_groups')->whereIn('case_pk', $externality['case_pks'])->pluck('pk')->toArray(), $pks);
        }
        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'consumed') {
                $issued_group = app('db')->table('issued_groups')->where('pk', $item['pk'])->first();
                $case_id = app('db')->table('cases')->where('pk', $issued_group->case_pk)->value('id');
                $input_object[$key] += [
                    'caseId' => $case_id,
                    'groupedQuantity' => $issued_group->grouped_quantity,
                    'received_item_pk' => $issued_group->received_item_pk,
                ];
            }
        }
        return $this::received_item_translation($input_object);
    }
}
