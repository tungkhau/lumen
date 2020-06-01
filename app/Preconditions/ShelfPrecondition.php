<?php

namespace App\Preconditions;

class ShelfPrecondition
{
    public function delete($params)
    {
        $block = app('db')->table('shelves')->where('pk', $params['shelf_pk'])->value('block_pk') == Null;
        $cases = app('db')->table('cases')->where('shelf_pk', $params['shelf_pk'])->exists();
        return $block || $cases;
    }
}
