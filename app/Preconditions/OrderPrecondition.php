<?php

namespace App\Preconditions;

use App\Http\Controllers\OrderController;

class OrderPrecondition
{
    public function create($params)
    {
        $accessory_pks = array();
        foreach ($params['ordered_items'] as $ordered_item) {
            array_push($accessory_pks, $ordered_item['accessory_pk']);
        }
        $suppliers_count = app('db')->table('accessories')->whereIn('pk', $accessory_pks)->distinct('supplier_pk')->count('suppler_pk');
        return $suppliers_count == 1 ? False : True;
    }

    public function edit($params)
    {
        $ordered_item_order_pk = app('db')->table('ordered_items')->where('pk', $params['ordered_item_pk'])->value('order_pk');
        $unique = $ordered_item_order_pk == $params['order_pk'] ? True : False;
        $imports = app('db')->table('imports')->where('order_pk', $params['order_pk'])->exists();
        $owner = app('db')->table('orders')->where('pk', $params['order_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$unique || $imports || !$owner;
    }

    public function delete($params)
    {
        return !OrderController::is_mutable($params['order_pk']);

    }

    public function turn_off($params)
    {
        $imports = app('db')->table('imports')->where('order_pk', $params['order_pk'])->exists();
        $owner = app('db')->table('orders')->where('pk', $params['order_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$imports || !$owner;
    }

    public function turn_on($params)
    {
        $owner = app('db')->table('orders')->where('pk', $params['order_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;
    }
}
