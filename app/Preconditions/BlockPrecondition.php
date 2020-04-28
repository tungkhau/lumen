<?php

namespace App\Preconditions;

class BlockPrecondition
{
    public function close($params)
    {
        $shelf_pks = app('db')->table('shelves')->where('block_pk', $params['block_pk'])->pluck('pk')->toArray();
        return app('db')->table('cases')->whereIn('shelf_pk', $shelf_pks)->exists();
    }

}
