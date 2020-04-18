<?php

namespace App\ViewModels;

class Accessory extends ViewModel
{
    public function get($params)
    {
        $status = $params['status'];
        $externality = $params['externality'];
        $status_filtered_object = $this->_status_filter($status);
        $externality_filtered_object = $this->_externality_filter($externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _status_filter($status)
    {
        $accessories = app('db')->table('accessories')->select('pk', 'is_active')->get();
        $object = array();
        if ($status != Null) {
            foreach ($accessories as $accessory) {
                $accessory_status = $accessory->is_active ? 'active' : 'inactive';
                if ($accessory_status == $status) $object[] = [
                    'pk' => $accessory->pk,
                    'status' => $accessory_status,
                ];
            }
            return $object;
        }
        foreach ($accessories as $accessory) {
            $accessory_status = $accessory->is_active ? 'active' : 'inactive';
            $object[] = [
                'pk' => $accessory->pk,
                'status' => $accessory_status,
            ];
        }
        return $object;
    }

    private function _externality_filter($externality, $input_object)
    {
        if ($externality != Null && array_key_exists('conception_pks', $externality)) {
            $pks = app('db')->table('accessories_conceptions')->whereIn('conception_pk', $externality['conception_pks'])->pluck('accessory_pk')->toArray();
            foreach ($input_object as $key => $item) {
                if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
                if (count($externality['conception_pks']) == 1)
                    if (in_array($item['pk'], $pks)) $input_object[$key] += ['isUnlinkable' => $this::is_unlinkable($item['pk'], $externality['conception_pks'][0])];
            }
            return $input_object;
        }

        if ($externality != Null && array_key_exists('supplier_pks', $externality)) {
            $pks = app('db')->table('accessories')->whereIn('supplier_pk', $externality['supplier_pks'])->pluck('pk')->toArray();
            foreach ($input_object as $key => $item) {
                if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
            }
            return $input_object;
        }

        return $input_object;
    }

    public static function is_unlinkable($accessory_pk, $conception_pk)
    {
        $demand_pks = app('db')->table('demands')->where('conception_pk', $conception_pk)->pluck('pk');
        return app('db')->table('demanded_items')->whereIn('demand_pk', $demand_pks)->where('accessory_pk', $accessory_pk)->doesntExist();
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            $accessory = app('db')->table('accessories')->where('pk', $item['pk'])->first();
            $unit_name = app('db')->table('units')->where('pk', $accessory->unit_pk)->value('name');
            $type_name = app('db')->table('types')->where('pk', $accessory->type_pk)->value('name');
            $customer_name = app('db')->table('customers')->where('pk', $accessory->customer_pk)->value('name');
            $supplier_name = app('db')->table('suppliers')->where('pk', $accessory->supplier_pk)->value('name');
            $input_object[$key] += [
                'id' => $accessory->id,
                'name' => $accessory->name,
                'item' => $accessory->item,
                'art' => $accessory->art,
                'color' => $accessory->color,
                'size' => $accessory->size,
                'typeName' => $type_name,
                'unitName' => $unit_name,
                'customerName' => $customer_name,
                'supplierName' => $supplier_name,
                'comment' => $accessory->comment,
                'isMutable' => $this::is_mutable($item['pk']),
            ];
        }
        return array_values($input_object);
    }

    public static function is_mutable($accessory_pk)
    {
        if (app('db')->table('accessories_conceptions')->where('accessory_pk', $accessory_pk)->exists()) return False;
        if (app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->exists()) return False;
        if (app('db')->table('in_distributed_items')->where('accessory_pk', $accessory_pk)->exists()) return False;
        if (app('db')->table('demanded_items')->where('accessory_pk', $accessory_pk)->exists()) return False;
        if (app('db')->table('restored_items')->where('accessory_pk', $accessory_pk)->exists()) return False;
        if (app('db')->table('out_distributed_items')->where('accessory_pk', $accessory_pk)->exists()) return False;
        return True;
    }
}
