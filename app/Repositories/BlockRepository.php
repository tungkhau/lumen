<?php

namespace App\Repositories;

use Exception;

class BlockRepository
{
    public function open($params)
    {
        try {
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
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function close($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('blocks')->where('pk', $params['block_pk'])->update([
                    'col' => Null,
                    'row' => Null,
                    'is_active' => False
                ]);
                app('db')->table('shelves')->where('block_pk', $params['block_pk'])->delete();
            });
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
