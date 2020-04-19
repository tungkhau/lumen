<?php

namespace App\ViewModels;

class Conception extends ViewModel
{
    public function get($params)
    {
        $status = $params['status'];
        $externality = $params['externality'];
        $status_filtered_object = $this->_status_filter($status);
        $externality_filtered_object = $this->_externality_filter($externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _status_filter($status)
    {
        $input_object = array();
        $conception_pks = app('db')->table('conceptions')->pluck('pk')->toArray();
        foreach ($conception_pks as $conception_pk) {
            $input_object[] = [
                'pk' => $conception_pk
            ];
        }
        $object = array();
        if ($status != Null) {
            foreach ($input_object as $item) {
                if ($this::status($item['pk']) == $status) $object[] = [
                    'pk' => $item['pk'],
                    'status' => $status,
                ];
            }
            return $object;
        }
        foreach ($input_object as $item) {
            $object[] = [
                'pk' => $item['pk'],
                'status' => $this::status($item['pk'])
            ];
        }
        return $object;
    }

    public static function status($conception_pk)
    {
        return app('db')->table('conceptions')->where('pk', $conception_pk)->value('is_active') ? 'active' : 'inactive';
    }

    private function _externality_filter($externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('accessory_pks', $externality)) {
            $pks = array_intersect(app('db')->table('accessories_conceptions')->whereIn('accessory_pk', $externality['accessory_pks'])->pluck('conception_pk')->toArray(), $pks);
        }

        if ($externality != Null && array_key_exists('conception_pks', $externality)) {
            $pks = array_intersect($externality['conception_pks'], $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            $conception = app('db')->table('conceptions')->where('pk', $item['pk'])->first();
            $customer_name = app('db')->table('customers')->where('pk', $conception->customer_pk)->value('name');
            $input_object[$key] += [
                'id' => $conception->id,
                'name' => $conception->name,
                'isMutable' => $this::is_mutable($item['pk']),
                'comment' => $conception->comment,
                'year' => $conception->year,
                'customerName' => $customer_name
            ];
        }
        return $input_object;
    }

    private static function is_mutable($conception_pk)
    {
        if (app('db')->table('accessories_conceptions')->where('conception_pk', $conception_pk)->exists()) return False;
        return True;
    }
}
