<?php

namespace App\ViewModels;

class MovingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('moving_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }
        if ($externality != Null && array_key_exists('moving_session_pks', $externality)) {
            $pks = array_intersect($externality['moving_session_pks'], $pks);
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
            $moving_session = app('db')->table('moving_sessions')->where('pk', $item['pk'])->first();
            $case_ids = app('db')->table('cases')->whereIn('pk', [$moving_session->start_case_pk, $moving_session->end_case_pk])->select('id', 'pk')->get()->toArray();
            $object[] = [
                'pk' => $item['pk'],
                'executedDate' => $moving_session->executed_date,
                'user_pk' => $moving_session->user_pk,
                'startCaseId' => $case_ids[0]->pk == $moving_session->start_case_pk ? $case_ids[0]->id : $case_ids[1]->id,
                'endCaseId' => $case_ids[0]->pk == $moving_session->end_case_pk ? $case_ids[0]->id : $case_ids[1]->id,
            ];

        }
        return $this::user_translation($object);
    }

}
