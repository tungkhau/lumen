<?php

namespace App\Preconditions;

class RestorationPrecondition
{
    public function register($params)
    {
        $mediator = app('db')->table('users')->where('pk', $params['user_pk'])->value('role') == 'mediator' ? True : False;
        return !$mediator;
    }

    public function confirm($params)
    {
        $owner = app('db')->table('restorations')->where('pk', $params['restoration_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;
    }

    public function cancel($params)
    {
        $owner = app('db')->table('restorations')->where('pk', $params['restoration_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$owner;
    }

    public function receive($params)
    {
        $restored_groups = collect($params['restored_groups']);
        $restored_groups = $restored_groups->mapToGroups(function ($item, $key) {
            return [$item['restored_item_pk'] => $item['grouped_quantity']];
        })->toArray();

        $sum = array();
        foreach ($restored_groups as $received_item_pk => $grouped_quantities) {
            $sum[$received_item_pk] = array_sum($grouped_quantities);
        }
        $collection = collect($sum);
        $restored_items = app('db')->table('restored_items')->where('restoration_pk', $params['restoration_pk'])->pluck('restored_quantity', 'pk')->toArray();
        $temp = array();
        foreach ($restored_items as $key => $value) {
            $temp[$key] = $value;
        }
        $restored_items = collect($temp);
        $diff = $collection->diffAssoc($restored_items);
        return $diff->isNotEmpty();
    }
}
