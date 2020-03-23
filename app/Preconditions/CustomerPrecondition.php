<?php

namespace App\Preconditions;

class CustomerPrecondition
{
    public function delete($params)
    {
        $accessories = app('db')->table('accessories')->where('customer_pk', $params['customer_pk'])->exists();
        $conceptions = app('db')->table('conceptions')->where('customer_pk', $params['customer_pk'])->exists();
        return $accessories || $conceptions;
    }
}
