<?php

namespace App\Preconditions;

class SupplierPrecondition
{
    public function delete($params)
    {
        return app('db')->table('accessories')->where('supplier_pk', $params['supplier_pk'])->exists();
    }
}
