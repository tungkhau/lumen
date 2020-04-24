<?php

namespace App\ViewModels;

use App\Http\Controllers\ReceivedGroupController;

class IssuedItem extends ViewModel
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
            $pks = app('db')->table('issued_items')->where('kind', 'consumed')->pluck('pk');
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
        if ($externality != Null && array_key_exists('issued_item_pks', $externality)) {
            $pks = array_intersect($externality['issued_item_pks'], $pks);
        }
        if ($externality != Null && array_key_exists('issuing_pks', $externality)) {
            $pks = array_intersect(app('db')->table('issued_items')->whereIn('issuing_session_pk', $externality['issuing_pks'])->pluck('pk')->toArray(), $pks);
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
                $issued_item = app('db')->table('issued_items')->where('pk', $item['pk'])->first();
                $accessory_pk = app('db')->table('demanded_items')->where('pk', $issued_item->end_item_pk)->value('accessory_pk');
                $input_object[$key] += [
                    'issuedQuantity' => $issued_item->issued_quantity,
                    'accessory_pk' => $accessory_pk,
                ];
            }
        }
        return $this::accessory_translation($input_object);
    }
}
