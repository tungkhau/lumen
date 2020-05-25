<?php

namespace App\ViewModels;

class ProgressingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('progressing_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('progressing_session_pks', $externality)) {
            $pks = array_intersect($externality['progressing_session_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('issuing_session_pks', $externality)) {
            $tmp = app('db')->table('issuing_sessions')->whereIn('pk', $externality['issuing_session_pks'])->pluck('progressing_session_pk')->toArray();
            $pks = array_intersect($tmp, $pks);
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
            $progressing_session = app('db')->table('progressing_sessions')->where('pk', $item['pk'])->first();
            $issuing_id = app('db')->table('issuing_sessions')->where('progressing_session_pk', $item['pk'])->value('id');
            $object[] = [
                'pk' => $progressing_session->pk,
                'executedDate' => $progressing_session->executed_date,
                'user_pk' => $progressing_session->user_pk,
                'issuingId' => $issuing_id
            ];
        }
        return $this::user_translation($object);
    }

}
