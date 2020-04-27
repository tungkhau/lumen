<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\Crypt;

class RootReceiving extends ViewModel
{
    private $root_received_item;

    public function __construct(RootReceivedItem $root_received_item)
    {
        $this->root_received_item = $root_received_item;
    }

    public function get($params)
    {
        $api = $params->header('api_token');
        $user_pk = Crypt::decrypt($api)['pk'];
        $kind = $params['kind'];
        $status = $params['status'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $status_filtered_object = $this->_status_filter($status, $kind_filtered_object);
        $externality_filtered_object = $this->_externality_filter($externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object, $user_pk);

    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'order' || $kind == Null) {
            $pks = app('db')->table('orders')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'order'
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

    public static function status($root_receiving_pk, $kind)
    {
        if ($kind == 'order') {
            return $is_opened = app('db')->table('orders')->where('pk', $root_receiving_pk)->value('is_opened') ? 'opened' : 'closed';
        }
        return Null;
    }

    private function _externality_filter($externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('root_receiving_pks', $externality)) {
            $pks = array_intersect($externality['root_receiving_pks'], $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object, $user_pk)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'order') {
                $order = app('db')->table('orders')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'id' => $order->id,
                    'createdDate' => $order->created_date,
                    'isMutable' => $this::is_mutable($item['pk'], $item['kind'], $user_pk),
                    'isSwitchable' => $this::is_switchable($item['pk'], $item['kind'], $user_pk),
                    'sourceName' => $this::source_name($item['pk'], $item['kind']),
                    'user_pk' => $order->user_pk,
                    'completedPercentage' => $this->completed_percentage($item['pk'], $item['kind'])
                ];
            }
        }
        return $this::user_translation($input_object);
    }

    private static function is_mutable($root_receiving_pk, $kind, $user_pk)
    {
        if ($kind == 'order') {
            $owner = app('db')->table('orders')->where('pk', $root_receiving_pk)->value('user_pk') == $user_pk;
            return !app('db')->table('imports')->where('order_pk', $root_receiving_pk)->exists() && $owner;
        }
        return Null;
    }

    private static function is_switchable($root_receiving_pk, $kind, $user_pk)
    {
        if ($kind == 'order') {
            $order = app('db')->table('orders')->where('pk', $root_receiving_pk)->select('user_pk', 'is_opened')->first();
            $owner = $order->user_pk == $user_pk;
            if (!$owner) return False;
            if ($order->is_opened == False) return True;
            return app('db')->table('imports')->where('order_pk', $root_receiving_pk)->exists();
        }
        return Null;
    }

    private static function source_name($root_receiving_pk, $kind)
    {
        if ($kind == 'order') {
            $supplier_pk = app('db')->table('orders')->where('pk', $root_receiving_pk)->value('supplier_pk');
            return app('db')->table('suppliers')->where('pk', $supplier_pk)->value('name');
        }
        return Null;
    }

    private function completed_percentage($root_receiving_pk, $kind)
    {
        if ($kind == 'order') {
            $ordered_item_pks = app('db')->table('ordered_items')->where('order_pk', $root_receiving_pk)->pluck('pk')->toArray();
            $sum = 0;
            foreach ($ordered_item_pks as $ordered_item_pk) {
                $sum += $this->root_received_item->completed_percentage($ordered_item_pk, 'ordered');
            }
            return $sum / count($ordered_item_pks);
        }
        return Null;
    }

}
