<?php

namespace App\ViewModels;

class ReplacingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('replacing_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }
        if ($externality != Null && array_key_exists('replacing_session_pks', $externality)) {
            $pks = array_intersect($externality['replacing_session_pks'], $pks);
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
            $replacing_session = app('db')->table('replacing_sessions')->where('pk', $item['pk'])->first();
            $case_id = app('db')->table('cases')->where('pk', $replacing_session->case_pk)->value('id');
            $shelf_ids = app('db')->table('shelves')->whereIn('pk', [$replacing_session->start_shelf_pk, $replacing_session->end_shelf_pk])->select('name', 'pk')->get()->toArray();
            $object[] = [
                'pk' => $item['pk'],
                'executedDate' => $replacing_session->executed_date,
                'user_pk' => $replacing_session->user_pk,
                'startShelfId' => $shelf_ids[0]->pk == $replacing_session->start_shelf_pk ? $shelf_ids[0]->name : $shelf_ids[1]->name,
                'endShelfId' => $shelf_ids[0]->pk == $replacing_session->end_shelf_pk ? $shelf_ids[0]->name : $shelf_ids[1]->name,
                'caseId' => $case_id
            ];

        }
        return $this::user_translation($object);
    }

}
