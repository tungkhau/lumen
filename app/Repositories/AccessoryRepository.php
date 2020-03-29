<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Storage;

class AccessoryRepository
{
    public function create($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'create',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
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
            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'delete',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('accessories')->where('pk', $params['accessory_pk'])->delete();
            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'deactivate',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                    'is_active' => False]);

            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'reactivate',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                    'is_active' => True]);
            });

        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function upload_photo($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'photo_update',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
                Storage::put($params['photo'], file_get_contents($params['image']));
                app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                    'photo' => $params['photo']]);
            });

        } catch (Exception $e) {
            Storage::delete($params['photo']);
            return $e;
        }
        if ($params['old_photo']) Storage::delete($params['old_photo']);
        return False;
    }

    public function delete_photo($params)
    {
        try {
            app('db')->transaction(function () use ($params) {
                app('db')->table('activity_logs')->insert([
                    'id' => $params['accessory_id'],
                    'type' => 'photo_update',
                    'object' => 'accessory',
                    'user_pk' => $params['user_pk']
                ]);
                app('db')->table('accessories')->where('pk', $params['accessory_pk'])->update([
                    'photo' => Null]);

            });

        } catch (Exception $e) {
            return $e;
        }
        if ($params['old_photo']) Storage::delete($params['old_photo']);
        return False;
    }
}
