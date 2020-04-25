<?php

namespace App\ViewModels;

class ModifyingSession extends ViewModel
{
    public function get($params)
    {
        $kind = $params['kind'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $externality_filtered_object = $this->_externality_filter($externality, $kind_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'adjusting' || $kind == Null) {
            $pks = app('db')->table('adjusting_sessions')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'adjusting'
                ];
            }
        }
        if ($kind == 'discarding' || $kind == Null) {
            $pks = app('db')->table('discarding_sessions')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'discarding'
                ];
            }
        }
        return $objects;
    }

    private function _externality_filter($externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('modifying_session_pks', $externality)) {
            $temp = app('db')->table('adjusting_sessions')->whereIn('pk', $externality['modifying_session_pks'])->pluck('pk')->toArray();
            $temp = array_merge(app('db')->table('adjusting_sessions')->whereIn('pk', $externality['modifying_session_pks'])->pluck('pk')->toArray(), $temp);
            $pks = array_intersect($temp, $pks);
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
            if ($item['kind'] == 'adjusting') {
                $adjusting_session = app('db')->table('adjusting_sessions')->where('pk', $item['pk'])->first();
                $entry = app('db')->table('entries')->where('session_pk', $item['pk'])->select('received_item_pk', 'case_pk')->first();
                $case_id = app('db')->table('cases')->where('pk', $entry->case_pk)->value('id');
                if ($adjusting_session->verifying_session_pk == Null)
                    $object [] = [
                        'pk' => $item['pk'],
                        'kind' => $item['kind'],
                        'result' => Null,
                        'status' => 'unverified',
                        'user_pk' => $adjusting_session->user_pk,
                        'executedDate' => $adjusting_session->executed_date,
                        'modifiedQuantity' => $adjusting_session->quantity,
                        'caseId' => $case_id,
                        'received_item_pk' => $entry->received_item_pk
                    ]; else {
                    $result = app('db')->table('verifying_sessions')->where('pk', $adjusting_session->verifying_session_pk)->value('result');
                    $object [] = [
                        'pk' => $item['pk'],
                        'kind' => $item['kind'],
                        'result' => $result,
                        'status' => 'verified',
                        'user_pk' => $adjusting_session->user_pk,
                        'executedDate' => $adjusting_session->executed_date,
                        'modifiedQuantity' => $adjusting_session->quantity,
                        'caseId' => $case_id,
                        'received_item_pk' => $entry->received_item_pk
                    ];
                }
            }
            if ($item['kind'] == 'discarding') {
                $discarding_session = app('db')->table('discarding_sessions')->where('pk', $item['pk'])->first();
                $entry = app('db')->table('entries')->where('session_pk', $item['pk'])->select('received_item_pk', 'case_pk')->first();
                $case_id = app('db')->table('cases')->where('pk', $entry->case_pk)->value('id');
                if ($discarding_session->verifying_session_pk == Null)
                    $object [] = [
                        'pk' => $item['pk'],
                        'kind' => $item['kind'],
                        'result' => Null,
                        'status' => 'unverified',
                        'user_pk' => $discarding_session->user_pk,
                        'executedDate' => $discarding_session->executed_date,
                        'modifiedQuantity' => $discarding_session->quantity,
                        'caseId' => $case_id,
                        'received_item_pk' => $entry->received_item_pk
                    ]; else {
                    $result = app('db')->table('verifying_sessions')->where('pk', $discarding_session->verifying_session_pk)->value('result');
                    $object [] = [
                        'pk' => $item['pk'],
                        'kind' => $item['kind'],
                        'result' => $result,
                        'status' => 'verified',
                        'user_pk' => $discarding_session->user_pk,
                        'executedDate' => $discarding_session->executed_date,
                        'modifiedQuantity' => $discarding_session->quantity,
                        'caseId' => $case_id,
                        'received_item_pk' => $entry->received_item_pk
                    ];
                }
            }
        }
        return $this::user_translation($this::received_item_translation($object));
    }

    public function get_unverified_modifying_session($params)
    {
        $received_item_pk = $params['externality']['received_item_pk'];
        $case_pk = $params['externality']['case_pk'];
        $entry = app('db')->table('entries')->where([['case_pk', $case_pk], ['received_item_pk', $received_item_pk], ['quantity', Null]])->select('entry_kind', 'session_pk')->first();
        $object[] = [
            'pk' => $entry->session_pk,
            'kind' => $entry->entry_kind,
        ];
        return $this::_translation($object);
    }
}
