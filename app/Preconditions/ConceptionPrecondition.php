<?php

namespace App\Preconditions;

class ConceptionPrecondition
{
    public function create($params)
    {
        return app('db')->table('conceptions')
            ->where('customer_pk', $params['customer_pk'])
            ->where('id', $params['conception_id'])
            ->where('year', $params['year'])->exists();
    }

    public function delete($params)
    {
        $demands = app('db')->table('demands')->where('conception_pk', $params['conception_pk'])->exists();
        $accessories = app('db')->table('accessories_conceptions')->where('conception_pk', $params['conception_pk'])->exists();
        return ($demands || $accessories);
    }

    public function link_accessory($params)
    {
        $accessory_customer = app('db')->table('accessories')->whereIn('pk', $params['accessory_pks'])->distinct('customer_pk')->pluck('customer_pk');
        if (count($accessory_customer) != 1) return true;
        $conception_customer = app('db')->table('conceptions')->where('pk', $params['conception_pk'])->value('customer_pk');
        $equal = $accessory_customer[0] == $conception_customer;
        return !$equal;
    }

    public function unlink_accessory($params)
    {
        $demand_pks = app('db')->table('demands')->where('conception_pk', $params['conception_pk'])->pluck('pk')->toArray();
        $failed = False;
        foreach ($demand_pks as $demand_pk) {
            if (app('db')->table('demanded_items')->where('demand_pk', $demand_pk)->where('accessory_pk', $params['accessory_pk'])->exists()) {
                $failed = True;
                break;
            }
        }
        return $failed;
    }
}
