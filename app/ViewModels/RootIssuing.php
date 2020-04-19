<?php

namespace App\ViewModels;

class RootIssuing extends ViewModel
{
    private $root_issued_item;

    public function __construct(RootIssuedItem $root_issued_item)
    {
        $this->root_issued_item = $root_issued_item;
    }

    public function get($params)
    {
        $kind = $params['kind'];
        $status = $params['status'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $status_filtered_object = $this->_status_filter($status, $kind_filtered_object);
        $externality_filtered_object = $this->_externality_filter($externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);

    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'demand' || $kind == Null) {
            $pks = app('db')->table('demands')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'demand'
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

    private function _externality_filter($externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('root_issuing_pks', $externality)) {
            $pks = array_intersect($externality['root_issuing_pks'], $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    public static function status($root_issuing_pk, $kind)
    {
        if ($kind == 'demand') {
            return app('db')->table('demands')->where('pk', $root_issuing_pk)->value('is_opened') ? 'opened' : 'closed';
        }
        return Null;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'demand') {
                $demand = app('db')->table('demands')->where('pk', $item['pk'])->first();
                $conception_id = app('db')->table('conceptions')->where('pk', $demand->conception_pk)->value('id');
                $input_object[$key] += [
                    'id' => $demand->id,
                    'createdDate' => $demand->created_date,
                    'conceptionId' => $conception_id,
                    'isMutable' => $this::is_mutable($item['pk'], $item['kind']),
                    'isSwitchable' => $this::is_switchable($item['pk'], $item['kind']),
                    'destinationName' => $this::destination_name($item['pk'], $item['kind']),
                    'user_pk' => $demand->user_pk,
                    'completedPercentage' => $this->completed_percentage($item['pk'], $item['kind'])
                ];
            }
        }
        return $this::user_translation($input_object);
    }

    private static function is_mutable($root_issuing_pk, $kind)
    {
        if ($kind == 'demand') {
            return !app('db')->table('issuing_sessions')->where('container_pk', $root_issuing_pk)->exists();
        }
        return Null;
    }

    private static function is_switchable($root_issuing_pk, $kind)
    {
        if ($kind == 'demand') {
            $is_opened = app('db')->table('demands')->where('pk', $root_issuing_pk)->value('is_opened');
            if (!$is_opened) return True;
            return app('db')->table('issuing_sessions')->where('container_pk', $root_issuing_pk)->exists();
        }
        return Null;
    }

    private function completed_percentage($root_issuing_pk, $kind)
    {
        if ($kind == 'demand') {
            $demanded_item_pks = app('db')->table('demanded_items')->where('demand_pk', $root_issuing_pk)->pluck('pk')->toArray();
            $sum = 0;
            foreach ($demanded_item_pks as $demanded_item_pk) {
                $sum += $this->root_issued_item->completed_percentage($demanded_item_pk, 'demanded');
            }
            return $sum / count($demanded_item_pks);
        }
        return Null;
    }

    private static function destination_name($root_issuing_pk, $kind)
    {
        if ($kind == 'demand') {
            $workplace_pk = app('db')->table('demands')->where('pk', $root_issuing_pk)->value('workplace_pk');
            return app('db')->table('workplaces')->where('pk', $workplace_pk)->value('name');
        }
        return Null;
    }

}
