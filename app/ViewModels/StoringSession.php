<?php

namespace App\ViewModels;

class StoringSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('storing_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('storing_session_pks', $externality)) {
            $pks = array_intersect($externality['storing_session_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('received_group_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('pk', $externality['received_group_pks'])->where('storing_session_pk', '!=', Null)->pluck('storing_session_pk')->toArray(), $pks);
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
            $storing_session = app('db')->table('storing_sessions')->where('pk', $item['pk'])->first();
            $object[] = [
                'pk' => $item['pk'],
                'executedDate' => $storing_session->executed_date,
                'user_pk' => $storing_session->user_pk,
            ];

        }
        return $this::user_translation($object);
    }

}
