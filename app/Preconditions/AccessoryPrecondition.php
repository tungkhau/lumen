<?php

namespace App\Preconditions;

class AccessoryPrecondition
{
    public function delete($params)
    {
        $conceptions = app('db')->table('accessories_conceptions')->where('accessory_pk', $params['accessory_pk'])->exists();
        $ordered_items = app('db')->table('ordered_items')->where('accessory_pk', $params['accessory_pk'])->exists();
        $in_distributed_items = app('db')->table('in_distributed_items')->where('accessory_pk', $params['accessory_pk'])->exists();
        $demanded_items = app('db')->table('demanded_items')->where('accessory_pk', $params['accessory_pk'])->exists();
        $restored_items = app('db')->table('restored_items')->where('accessory_pk', $params['accessory_pk'])->exists();
        $out_distributed_item = app('db')->table('out_distributed_items')->where('accessory_pk', $params['accessory_pk'])->exists();
        return $conceptions || $ordered_items || $in_distributed_items || $demanded_items || $restored_items || $out_distributed_item;
    }

    public function delete_photo($params)
    {
        return !app('db')->table('accessories')->where('pk', $params['accessory_pk'])->value('photo');
    }
}
