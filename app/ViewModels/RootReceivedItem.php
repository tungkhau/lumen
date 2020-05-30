<?php

namespace App\ViewModels;

class RootReceivedItem extends ViewModel
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
        if ($kind == 'ordered' || $kind == Null) {
            $pks = app('db')->table('ordered_items')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'ordered'
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
        if ($externality != Null && array_key_exists('root_receiving_pks', $externality)) {
            $pks = array_intersect(app('db')->table('ordered_items')->whereIn('order_pk', $externality['root_receiving_pks'])->pluck('pk')->toArray(), $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'ordered') {
                $ordered_item = app('db')->table('ordered_items')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'expectedQuantity' => $ordered_item->ordered_quantity,
                    'expectedComment' => $ordered_item->comment,
                    'sumReceivedQuantity' => $this::sum_received_quantity($item['pk'], $item['kind']),
                    'sumActualReceivedQuantity' => $this::sum_actual_received_quantity($item['pk'], $item['kind']),
                    'sumAdjustedQuantity' => $this::sum_adjusted_quantity($item['pk'], $item['kind']),
                    'sumDiscardedQuantity' => $this::sum_discarded_quantity($item['pk'], $item['kind']),
                    'completedPercentage' => $this::completed_percentage($item['pk'], $item['kind']),
                    'accessory_pk' => $ordered_item->accessory_pk,
                ];
            }
        }
        return $this::accessory_translation($input_object);
    }

    private static function sum_received_quantity($root_received_item_pk, $kind)
    {
        $sum = 0;
        if ($kind == 'ordered') {
            $imported_quantities = app('db')->table('imported_items')->where('ordered_item_pk', $root_received_item_pk)->pluck('imported_quantity');
            foreach ($imported_quantities as $imported_quantity) {
                $sum += $imported_quantity;
            }
        }
        return $sum;
    }

    public static function sum_actual_received_quantity($root_received_item_pk, $kind)
    {
        $sum = 0;
        if ($kind == 'ordered') {
            $imported_item_pks = app('db')->table('imported_items')->where('ordered_item_pk', $root_received_item_pk)->pluck('pk');
            foreach ($imported_item_pks as $imported_item_pk) {
                $sum += ReceivedItem::sum_actual_received_quantity($imported_item_pk);
            }
        }
        return $sum;
    }

    private static function sum_adjusted_quantity($root_received_item_pk, $kind)
    {
        $sum = 0;
        if ($kind == 'ordered') {
            $imported_item_pks = app('db')->table('imported_items')->where('ordered_item_pk', $root_received_item_pk)->pluck('pk');
            $adjusted_entry_quantities = app('db')->table('entries')->whereIn('received_item_pk', $imported_item_pks)->where('entry_kind', 'adjusting')->pluck('quantity');
            foreach ($adjusted_entry_quantities as $adjusted_entry_quantity) {
                if ($adjusted_entry_quantity != Null) $sum += $adjusted_entry_quantity;
            }
        }
        return $sum;
    }

    private static function sum_discarded_quantity($root_received_item_pk, $kind)
    {
        $sum = 0;
        if ($kind == 'ordered') {
            $imported_item_pks = app('db')->table('imported_items')->where('ordered_item_pk', $root_received_item_pk)->pluck('pk');
            $discarded_entry_quantities = app('db')->table('entries')->whereIn('received_item_pk', $imported_item_pks)->where('entry_kind', 'discarding')->pluck('quantity');
            foreach ($discarded_entry_quantities as $discarded_entry_quantity) {
                if ($discarded_entry_quantity != Null) $sum += $discarded_entry_quantity;
            }
        }
        return $sum;
    }

    public function completed_percentage($root_received_item_pk, $kind)
    {
        if ($kind == 'ordered') {
            $expected_quantity = app('db')->table('ordered_items')->where('pk', $root_received_item_pk)->value('ordered_quantity');
            return ($this::sum_actual_received_quantity($root_received_item_pk, $kind) / $expected_quantity);
        }
        return 0;
    }
}
