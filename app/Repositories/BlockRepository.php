<?php

namespace App\Repositories;

use Exception;

class BlockRepository
{
    public function open($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('blocks')->where('pk', $params['block_pk'])->update([
                    'col' => $params['col'],
                    'row' => $params['row'],
                    'is_active' => True
                ]);
                app('db')->table('shelves')->insert($params['shelves']);
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
