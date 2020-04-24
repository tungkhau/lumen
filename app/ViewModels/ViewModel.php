<?php

namespace App\ViewModels;

class ViewModel
{
    public static function user_translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            $user = app('db')->table('users')->where('pk', $item['user_pk'])->select('name', 'id')->first();
            $temp = ['userName' => $user->name, 'userId' => $user->id];
            $input_object[$key] += $temp;
            unset($input_object[$key]['user_pk']);
        }
        return $input_object;
    }

    public static function accessory_translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            $accessory = app('db')->table('accessories')->where('pk', $item['accessory_pk'])->first();
            $unit_name = app('db')->table('units')->where('pk', $accessory->unit_pk)->value('name');
            $type_name = app('db')->table('types')->where('pk', $accessory->type_pk)->value('name');
            $temp = ['accessoryId' => $accessory->id,
                'accessoryName' => $accessory->name,
                'accessoryItem' => $accessory->item,
                'accessoryArt' => $accessory->art,
                'accessoryColor' => $accessory->color,
                'accessorySize' => $accessory->size,
                'accessoryUnitName' => $unit_name,
                'accessoryTypeName' => $type_name];
            $input_object[$key] += $temp;
            unset($input_object[$key]['accessory_pk']);
        }
        return $input_object;
    }

    public static function received_item_translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            $received_item = app('db')->table('imported_items')->where('pk', $item['received_item_pk'])->first();
            if (count((array)$received_item) == 0) {
                $received_item = app('db')->table('restored_items')->where('pk', $item['received_item_pk'])->first();
                $receiving = app('db')->table('restorations')->where('pk', $received_item->restoration_pk)->first();
                $receiving_id = $receiving->id;
                $receiving_created_date = $receiving->created_date;
                $workplace_pk = app('db')->table('users')->where('pk', $receiving->user_pk)->value('workplace_pk');
                $receiving_source_name = app('db')->table('workplaces')->where('pk', $workplace_pk)->value('name');
                $accessory_pk = $received_item->accessory_pk;
            } else {
                $receiving = app('db')->table('imports')->where('pk', $received_item->import_pk)->first();
                $receiving_id = $receiving->id;
                $receiving_created_date = $receiving->created_date;
                $supplier_pk = app('db')->table('orders')->where('pk', $receiving->order_pk)->value('supplier_pk');
                $receiving_source_name = app('db')->table('suppliers')->where('pk', $supplier_pk)->value('name');
                $accessory_pk = app('db')->table('ordered_items')->where('pk', $received_item->ordered_item_pk)->value('accessory_pk');
            }
            $accessory = app('db')->table('accessories')->where('pk', $accessory_pk)->first();
            $unit_name = app('db')->table('units')->where('pk', $accessory->unit_pk)->value('name');
            $type_name = app('db')->table('types')->where('pk', $accessory->type_pk)->value('name');
            $temp = [
                'receivingId' => $receiving_id,
                'receivingSourceName' => $receiving_source_name,
                'receivingCreatedDate' => $receiving_created_date,
                'accessoryId' => $accessory->id,
                'accessoryName' => $accessory->name,
                'accessoryItem' => $accessory->item,
                'accessoryArt' => $accessory->art,
                'accessoryColor' => $accessory->color,
                'accessorySize' => $accessory->size,
                'accessoryUnitName' => $unit_name,
                'accessoryTypeName' => $type_name];
            $input_object[$key] += $temp;
            unset($input_object[$key]['received_item_pk']);
        }
        return $input_object;
    }

    public static function sort_response($input_object, $field) {
        $array_map = array_map(function ($element) use ($field) {
            return $element[$field];
        }, $input_object);
        array_multisort($array_map, SORT_DESC, $input_object);
        return $input_object;
    }

}
