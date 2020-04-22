<?php

namespace App\ViewModels;

class CheckingSession extends ViewModel
{
    private static function is_mutable($checking_session_pk)
    {
        $imported_item_pk = app('db')->table('received_groups')->where('checking_session_pk', $checking_session_pk)->value('received_item_pk');
        return app('db')->table('imported_items')->where('pk', $imported_item_pk)->value('classified_item_pk') == Null;
    }

    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('checking_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('checking_session_pks', $externality)) {
            $pks = array_intersect($externality['checking_sessions_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('received_group_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('pk', $externality['received_group_pks'])->where('checking_session_pk', '!=', Null)->pluck('checking_session_pk')->toArray(), $pks);
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
            $checking_session = app('db')->table('checking_sessions')->where('pk', $item['pk'])->first();
            $received_group = app('db')->table('received_groups')->where('checking_session_pk', $item['pk'])->select('received_item_pk', 'grouped_quantity')->first();
            $object[] = [
                'pk' => $item['pk'],
                'isMutable' => $this::is_mutable($checking_session->pk),
                'checkedQuantity' => $checking_session->checked_quantity,
                'unqualifiedQuantity' => $checking_session->unqualified_quantity,
                'executedDate' => $checking_session->executed_date,
                'receivedGroupGroupedQuantity' => $received_group->grouped_quantity,
                'user_pk' => $checking_session->user_pk,
                'received_item_pk' => $received_group->received_item_pk
            ];

        }
        return $this::received_item_translation($this::user_translation($object));
    }
}
