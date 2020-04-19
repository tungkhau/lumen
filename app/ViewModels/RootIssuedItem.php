<?php

namespace App\ViewModels;

class RootIssuedItem extends ViewModel
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
        if ($kind == 'demanded' || $kind == Null) {
            $pks = app('db')->table('demanded_items')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'demanded'
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
        if ($externality != Null && array_key_exists('root_issuing_pks', $externality)) {
            $pks = array_intersect(app('db')->table('demanded_items')->whereIn('demand_pk', $externality['root_issuing_pks'])->pluck('pk')->toArray(), $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'demanded') {
                $demanded_item = app('db')->table('demanded_items')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'expectedQuantity' => $demanded_item->demanded_quantity,
                    'expectedComment' => $demanded_item->comment,
                    'sumIssuedQuantity' => $this::sum_issued_quantity($item['pk'], $item['kind']),
                    'completedPercentage' => $this->completed_percentage($item['pk'], $item['kind']),
                    'accessory_pk' => $demanded_item->accessory_pk
                ];
            }
        }
        return $this::accessory_translation($input_object);
    }

    private static function sum_issued_quantity($root_issued_item_pk, $kind)
    {
        if ($kind == 'demanded') return app('db')->table('issued_items')->where('end_item_pk', $root_issued_item_pk)->where('is_returned', False)->sum('issued_quantity');
        return Null;
    }

    public function completed_percentage($root_issued_item_pk, $kind)
    {
        if ($kind == 'demanded') {
            $demanded_quantity = app('db')->table('demanded_items')->where('pk', $root_issued_item_pk)->value('demanded_quantity');
            $sum_issued_quantity = $this::sum_issued_quantity($root_issued_item_pk, $kind);
            return $sum_issued_quantity / $demanded_quantity;
        }
        return Null;
    }
}
