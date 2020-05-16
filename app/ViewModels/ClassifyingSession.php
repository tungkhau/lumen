<?php

namespace App\ViewModels;

class ClassifyingSession extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('classifying_sessions')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('classifying_session_pks', $externality)) {
            $pks = array_intersect($externality['classifying_session_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('received_group_pks', $externality)) {
            $received_item_pks = app('db')->table('received_groups')->whereIn('pk', $externality['received_group_pks'])->distinct('received_item_pk')->pluck('received_item_pk');
            $classified_item_pks = app('db')->table('imported_items')->whereIn('pk', $received_item_pks)->where('classified_item_pk', '!=', Null)->pluck('classified_item_pk');
            $pks = array_intersect(app('db')->table('classifying_sessions')->whereIn('classified_item_pk', $classified_item_pks)->pluck('pk')->toArray(), $pks);
        }

        if ($externality != Null && array_key_exists('received_item_pks', $externality)) {
            $classified_item_pks = app('db')->table('imported_items')->whereIn('pk', $externality['received_item_pks'])->where('classified_item_pk', '!=', Null)->pluck('classified_item_pk');
            $pks = array_intersect(app('db')->table('classifying_sessions')->whereIn('classified_item_pk', $classified_item_pks)->pluck('pk')->toArray(), $pks);
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
            $classifying_session = app('db')->table('classifying_sessions')->where('pk', $item['pk'])->first();
            $classified_item = app('db')->table('classified_items')->where('pk', $classifying_session->classified_item_pk)->first();
            $received_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $classified_item->pk)->value('pk');
            $object[] = [
                'pk' => $classifying_session->pk,
                'isMutable' => $this::is_mutable($classifying_session->pk),
                'executedDate' => $classifying_session->executed_date,
                'qualityState' => $classified_item->quality_state,
                'user_pk' => $classifying_session->user_pk,
                'classifiedItemPk' => $classified_item->pk,
                'received_item_pk' => $received_item_pk,
            ];

        }
        return $this::received_item_translation($this::user_translation($object));
    }

    private static function is_mutable($classifying_session_pk)
    {
        $classified_item_pk = app('db')->table('classifying_sessions')->where('pk', $classifying_session_pk)->value('classified_item_pk');
        $imported_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $classified_item_pk)->value('pk');
        return !app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->where('case_pk', Null)->exists();
    }
}
