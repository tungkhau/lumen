<?php

namespace App\ViewModels;

class Partner extends ViewModel
{
    public function get($params)
    {
        $kind = $params['kind'];
        $status = $params['status'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $status_filtered_object = $this->_status_filter($status, $kind_filtered_object);
        return $this::sort_response($this->_translation($status_filtered_object), 'createdDate');
    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'supplier' || $kind == Null) {
            $pks = app('db')->table('suppliers')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'supplier'
                ];
            }
        }
        if ($kind == 'customer' || $kind == Null) {
            $pks = app('db')->table('customers')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'customer'
                ];
            }
        }
        return $objects;
    }

    private function _status_filter($status, $input_object)
    {
        $object = array();
        if ($status != Null) {
            foreach ($input_object as $item) {
                if ($this::status($item['pk'], $item['kind']) == $status) $object[] = [
                    'pk' => $item['pk'],
                    'kind' => $item['kind'],
                    'status' => $status,
                ];
            }
            return $object;
        }
        foreach ($input_object as $item) {
            $object[] = [
                'pk' => $item['pk'],
                'kind' => $item['kind'],
                'status' => $this::status($item['pk'], $item['kind'])
            ];
        }
        return $object;
    }

    public static function status($partner_pk, $kind)
    {
        if ($kind == 'supplier') {
            return app('db')->table('suppliers')->where('pk', $partner_pk)->value('is_active') ? 'active' : 'inactive';
        }
        if ($kind == 'customer') {
            return app('db')->table('customers')->where('pk', $partner_pk)->value('is_active') ? 'active' : 'inactive';
        }
        return Null;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'supplier') {
                $supplier = app('db')->table('suppliers')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'id' => $supplier->id,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                    'name' => $supplier->name,
                    'createdDate' => $supplier->created_date,
                    'isMutable' => $this::is_mutable($supplier->pk, 'supplier')
                ];
            }
            if ($item['kind'] == 'customer') {
                $customer = app('db')->table('customers')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'id' => $customer->id,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'name' => $customer->name,
                    'createdDate' => $customer->created_date,
                    'isMutable' => $this::is_mutable($customer->pk, 'customer')
                ];
            }
        }
        return $input_object;
    }

    private static function is_mutable($partner_pk, $kind)
    {
        if ($kind == 'customer') {
            $accessories = app('db')->table('accessories')->where('customer_pk', $partner_pk)->exists();
            $conceptions = app('db')->table('conceptions')->where('customer_pk', $partner_pk)->exists();
            return !$accessories && !$conceptions;
        }
        if ($kind == 'supplier') {
            $accessories = app('db')->table('accessories')->where('supplier_pk', $partner_pk)->exists();
            return !$accessories;
        }
        return Null;
    }
}
