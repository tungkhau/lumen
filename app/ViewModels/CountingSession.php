<?php

namespace App\ViewModels;

class CountingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('counting_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('counting_session_pks', $externality)) {
            $pks = array_intersect($externality['counting_session_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('received_group_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('pk', $externality['received_group_pks'])->where('counting_session_pk', '!=', Null)->pluck('counting_session_pk')->toArray(), $pks);
        }

        foreach ($object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($object[$key]);
        }
        return $object;
    }

    private function _translation($input_object)
    {
        $object = array();
        foreach ($input_object as $item) {
            $counting_session = app('db')->table('counting_sessions')->where('pk', $item['pk'])->first();
            $received_group = app('db')->table('received_groups')->where('counting_session_pk', $item['pk'])->select('received_item_pk', 'grouped_quantity')->first();
            $object[] = [
                'pk' => $item['pk'],
                'isMutable' => $this::is_mutable($counting_session->pk),
                'countedQuantity' => $counting_session->counted_quantity,
                'executedDate' => $counting_session->executed_date,
                'receivedGroupGroupedQuantity' => $received_group->grouped_quantity,
                'user_pk' => $counting_session->user_pk,
                'received_item_pk' => $received_group->received_item_pk
            ];

        }
        return $this::received_item_translation($this::user_translation($object));
    }

    private static function is_mutable($counting_session_pk)
    {
        $received_group = app('db')->table('received_groups')->where('counting_session_pk', $counting_session_pk)->select('received_item_pk', 'kind', 'case_pk')->first();
        if ($received_group->kind == 'imported') return app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('classified_item_pk') == Null;
        if ($received_group->kind == 'restored') return $received_group->case_pk != Null;
        return Null;
    }

}
