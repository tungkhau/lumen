<?php

namespace App\Repositories;

use Exception;

class ConceptionRepository
{

    public function create($params)
    {
        try {
            app('db')->table('conceptions')->insert([
                'customer_pk' => $params['customer_pk'],
                'id' => $params['conception_id'],
                'name' => $params['conception_name'],
                'year' => $params['year'],
                'comment' => $params['comment'],
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->table('conceptions')->where('pk', $params['conception_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->table('conceptions')->where('pk', $params['conception_pk'])->update([
                'is_active' => False
            ]);

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            app('db')->table('conceptions')->where('pk', $params['conception_pk'])->update([
                'is_active' => True
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function link_accessory($params)
    {
        try {
            app('db')->table('accessories_conceptions')->insert([
                'accessory_pk' => $params['accessory_pk'],
                'conception_pk' => $params['conception_pk']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function unlink_accessory($params)
    {
        try {
            app('db')->table('accessories_conceptions')
                ->where('accessory_pk', $params['accessory_pk'])
                ->where('conception_pk', $params['conception_pk'])
                ->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
