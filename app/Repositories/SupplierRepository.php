<?php

namespace App\Repositories;

use Exception;

class SupplierRepository
{

    public function create($params)
    {
        try {
            app('db')->table('suppliers')->insert([
                'name' => $params['supplier_name'],
                'id' => $params['supplier_id'],
                'address' => $params['address'],
                'phone' => $params['phone']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function edit($params)
    {
        try {
            app('db')->table('suppliers')->where('pk', $params['supplier_pk'])->update([
                'address' => $params['address'],
                'phone' => $params['phone']
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function delete($params)
    {
        try {
            app('db')->table('suppliers')->where('pk', $params['supplier_pk'])->delete();
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            app('db')->table('suppliers')->where('pk', $params['supplier_pk'])->update([
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
            app('db')->table('suppliers')->where('pk', $params['supplier_pk'])->update([
                'is_active' => True
            ]);
        } catch (Exception $e) {
            return $e;
        }
        return False;
    }
}
