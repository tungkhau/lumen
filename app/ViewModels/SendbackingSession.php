<?php

namespace App\ViewModels;

class SendbackingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('sendbacking_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('sendbacking_session_pks', $externality)) {
            $pks = array_intersect($externality['sendbacking_session_pks'], $pks);
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
            $sendbacking_session = app('db')->table('sendbacking_sessions')->where('pk', $item['pk'])->first();
            $classified_item_pk = app('db')->table('classified_items')->where('sendbacking_session_pk', $item['pk'])->value('pk');
            $import_pk = app('db')->table('imported_items')->where('classified_item_pk', $classified_item_pk)->value('import_pk');
            $import = app('db')->table('imports')->where('pk', $import_pk)->select('id', 'created_date')->first();
            $object[] = [
                'pk' => $sendbacking_session->pk,
                'executedDate' => $sendbacking_session->executed_date,
                'user_pk' => $sendbacking_session->user_pk,
                'receivingId' => $import->id,
                'receivingCreatedDate' => $import->created_date
            ];
        }
        return $this::user_translation($object);
    }
}
