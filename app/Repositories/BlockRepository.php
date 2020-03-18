<?php

namespace App\Repositories;

use App\Interfaces\BlockInterface;

class BlockRepository implements BlockInterface
{

    public function open($params)
    {
        $block_id = app('db')->table('blocks')->where('pk', $params['block_pk'])->value('id');
        for ($col = 1; $col < $params['col']; $col++) {
            for ($row = 1; $row < $params['row']; $row++)
                $shelves[] = [
                    'name' => $block_id . "-" . $row . "-" . $col,
                    'block_pk' => $params['block_pk']
                ];
        }
        app('db')->transaction(function () use ($params, $shelves) {
            app('db')->table('blocks')->where('pk', $params['block_pk'])->update([
                'col' => $params['col'],
                'row' => $params['row'],
                'is_active' => True
            ]);
            app('db')->table('shelves')->insert($shelves);
        });

    }

    public function close($key)
    {
        app('db')->transaction(function () use ($key) {
            app('db')->table('blocks')->where('pk', $key)->update([
                'col' => Null,
                'row' => Null,
                'is_active' => False
            ]);
            app('db')->table('shelves')->where('block_pk', $key)->delete();
        });
    }
}
