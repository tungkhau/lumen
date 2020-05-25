<?php

namespace App\ViewModels;

class ArrangingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('arranging_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }
        if ($externality != Null && array_key_exists('arranging_session_pks', $externality)) {
            $pks = array_intersect($externality['arranging_session_pks'], $pks);
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
            $arranging_session = app('db')->table('arranging_sessions')->where('pk', $item['pk'])->first();
            $case_ids = app('db')->table('cases')->whereIn('pk', [$arranging_session->start_case_pk, $arranging_session->end_case_pk])->select('id', 'pk')->get()->toArray();
            $object[] = [
                'pk' => $item['pk'],
                'executedDate' => $arranging_session->executed_date,
                'user_pk' => $arranging_session->user_pk,
                'startCaseId' => $case_ids[0]->pk == $arranging_session->start_case_pk ? $case_ids[0]->id : $case_ids[1]->id,
                'endCaseId' => $case_ids[0]->pk == $arranging_session->end_case_pk ? $case_ids[0]->id : $case_ids[1]->id,
            ];

        }
        return $this::user_translation($object);
    }
}
