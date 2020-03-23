<?php

namespace App\Repositories;
use Exception;

class AccessoryRepository
{
    public function create($params)
    {
        try {
            app('db')->table('accessories')->insert([
                'id' => $params['id'],
                'customer_pk' => $params['customer_pk'],
                'supplier_pk' => $params['supplier_pk'],
                'type_pk' => $params['type_pk'],
                'unit_pk' => $params['unit_pk'],
                'item' => $params['item'],
                'art' => $params['art'],
                'color' => $params['color'],
                'size' => $params['size'],
                'name' => $params['accessory_name'],
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
            app('db')->table('accessories')->where('pk', $params['accessory_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                'is_active' => False]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                'is_active' => True]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function upload_photo($params)
    {
        // TODO: Implement upload_photo() method.
    }

    public function delete_photo($key)
    {
        // TODO: Implement delete_photo() method.
    }
}
