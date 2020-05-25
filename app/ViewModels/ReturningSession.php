<?php

namespace App\ViewModels;

class ReturningSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('returning_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('returning_session_pks', $externality)) {
            $pks = array_intersect($externality['returning_session_pks'], $pks);
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
            $returning_session = app('db')->table('returning_sessions')->where('pk', $item['pk'])->first();
            $issuing_id = app('db')->table('issuing_sessions')->where('returning_session_pk', $item['pk'])->value('id');
            $object[] = [
                'pk' => $returning_session->pk,
                'executedDate' => $returning_session->executed_date,
                'user_pk' => $returning_session->user_pk,
                'issuingId' => $issuing_id
            ];
        }
        return $this::user_translation($object);
    }
}
