<?php

namespace App\ViewModels;

class Issuing extends ViewModel
{
    public function get($params)
    {
        $kind = $params['kind'];
        $status = $params['status'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $status_filtered_object = $this->_status_filter($status, $kind_filtered_object);
        $externality_filtered_object = $this->_externality_filter($kind, $externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'consuming' || $kind == Null) {
            $pks = app('db')->table('issuing_sessions')->where('kind','consuming')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'consuming'
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

    public static function status($issuing_pk, $kind)
    {
        if ($kind == 'consuming' || $kind == 'outTransferring') {
            $issuing = app('db')->table('issuing_sessions')->where('pk', $issuing_pk)->first();
            if($issuing->progressing_session_pk == Null && $issuing->returning_session_pk == Null) return 'ready';
            if($issuing->progressing_session_pk != Null) return 'delivered';
            if($issuing->returning_session_pk != Null) return 'returned';
        }
        return Null;
    }

    private function _externality_filter($kind, $externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('issuing_pks', $externality)) {
            $pks = array_intersect($externality['issuing_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('returning_session_pks', $externality)) {
            $pks = array_intersect(app('db')->table('issuing_sessions')->whereIn('returning_session_pk', $externality['returning_session_pks'])->pluck('pk')->toArray(), $pks);
        }
        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        $object = array();
        foreach ($input_object as $item) {
            if ($item['kind'] == 'consuming') {
                $issuing = app('db')->table('issuing_sessions')->where('pk', $item['pk'])->first();
                $demand = app('db')->table('demands')->where('pk', $issuing->container_pk)->first();
                $conception_id = app('db')->table('conceptions')->where('pk', $demand->conception_pk)->value('id');
                $destination_name = app('db')->table('workplaces')->where('pk', $demand->workplace_pk)->value('name');
                $object[] = [
                    'pk' => $item['pk'],
                    'kind' => $item['kind'],
                    'status' => $item['status'],
                    'rootIssuingId' => $demand->id,
                    'destinationName' => $destination_name,
                    'createdDate' => $issuing->executed_date,
                    'conceptionId'=> $conception_id,
                    'user_pk' => $issuing->user_pk,
                ];
            }
        }
        return $this::user_translation($object);
    }
}
