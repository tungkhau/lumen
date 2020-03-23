<?php

namespace App\Preconditions;

class ConceptionPrecondition
{
    public function create($params)
    {
        return app('db')->table('conceptions')
            ->where('customer_pk', $params['customer_pk'])
            ->where('id', $params['id'])
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
        $accessory_customer = app('db')->table('accessories')->where('pk', $params['accessory_pk'])->value('customer_pk');
        $conception_customer = app('db')->table('conceptions')->where('pk', $params['conception_pk'])->value('customer_pk');
        $equal = $accessory_customer == $conception_customer ? True : False;
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
