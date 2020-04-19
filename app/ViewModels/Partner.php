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
        return $this->_translation($status_filtered_object);
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
                    'name' => $supplier->name
                ];
            }
            if ($item['kind'] == 'customer') {
                $customer = app('db')->table('customers')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'id' => $customer->id,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'name' => $customer->name
                ];
            }
        }
        return $input_object;
    }
}
