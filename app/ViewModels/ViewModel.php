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
}
