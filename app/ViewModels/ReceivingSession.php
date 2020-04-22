<?php

namespace App\ViewModels;

class ReceivingSession extends ViewModel
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
        if ($kind == 'importing' || $kind == Null) {
            $pks = app('db')->table('receiving_sessions')->where('kind', 'importing')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'importing'
                ];
            }
        }
        if ($kind == 'restoring' || $kind == Null) {
            $pks = app('db')->table('receiving_sessions')->where('kind', 'restoring')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'restoring'
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
        if ($externality != Null && array_key_exists('receiving_pks', $externality)) {
            $temp = array();
            foreach ($externality['receiving_pks'] as $receiving_pk) {
                $received_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk');
                if (count($received_item_pks) == 0) {
                    $temp = array_merge([app('db')->table('restorations')->where('pk', $receiving_pk)->value('receiving_session_pk')], $temp);
                } else {
                    $temp = array_merge(app('db')->table('received_groups')->whereIn('received_item_pk', $received_item_pks)->where('receiving_session_pk', '!=', Null)->distinct('receiving_session_pk')->pluck('receiving_session_pk')->toArray(), $temp);
                }
            }
            $pks = array_intersect($temp, $pks);
        }
        if ($externality != Null && array_key_exists('received_group_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('pk', $externality['received_group_pks'])->where('receiving_session_pk', '!=', Null)->pluck('receiving_session_pk')->toArray(), $pks);
        }
        if ($externality != Null && array_key_exists('receiving_session_pks', $externality)) {
            $pks = array_intersect($externality['receiving_session_pks'], $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'importing') {
                $received_item_pk = app('db')->table('received_groups')->where('receiving_session_pk', $item['pk'])->first()->received_item_pk;
                $import_pk = app('db')->table('imported_items')->where('pk', $received_item_pk)->value('import_pk');
                $import = app('db')->table('imports')->where('pk', $import_pk)->first();
                $supplier_pk = app('db')->table('orders')->where('pk', $import->order_pk)->value('supplier_pk');
                $source_name = app('db')->table('suppliers')->where('pk', $supplier_pk)->value('name');
                $receiving_session = app('db')->table('receiving_sessions')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'executedDate' => $receiving_session->executed_date,
                    'user_pk' => $receiving_session->user_pk,
                    'receivingId' => $import->id,
                    'receivingSourceName' => $source_name,
                    'receivingCreatedDate' => $import->created_date
                ];
            }
            if ($item['kind'] == 'restoring') {
                $receiving_session = app('db')->table('receiving_sessions')->where('pk', $item['pk'])->first();
                $restoration = app('db')->table('restorations')->where('receiving_session_pk', $item['pk'])->first();
                $workplace_pk = app('db')->table('users')->where('pk', $restoration->user_pk)->value('workplace_pk');
                $source_name = app('db')->table('workplaces')->where('pk', $workplace_pk)->value('name');
                $input_object[$key] += [
                    'executedDate' => $receiving_session->executed_date,
                    'user_pk' => $receiving_session->user_pk,
                    'receivingId' => $restoration->id,
                    'receivingSourceName' => $source_name,
                    'receivingCreatedDate' => $restoration->created_date
                ];
            }
        }
        return $this::user_translation($input_object);
    }
}
