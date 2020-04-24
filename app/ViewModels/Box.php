<?php

namespace App\ViewModels;

class Box extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('cases')->where('is_active', True)->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('case_pks', $externality)) {
            $pks = array_intersect($externality['case_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('issuing_pks', $externality)) {
            $pks = array_intersect(app('db')->table('issued_groups')->whereIn('issuing_session_pk', $externality['issuing_pks'])->distinct('case_pk')->pluck('case_pk')->toArray(), $pks);
        }

        if ($externality != Null && array_key_exists('received_item_pks', $externality)) {
            $pks = array_intersect(app('db')->table('received_groups')->whereIn('received_item_pk', $externality['received_item_pks'])->distinct('case_pk')->pluck('case_pk')->toArray(), $pks);
        }

        if ($externality != Null && array_key_exists('shelves_pks', $externality)) {
            $pks = array_intersect(app('db')->table('cases')->whereIn('shelf_pk', $externality['shelves_pks'])->pluck('pk')->toArray(), $pks);
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
            $case = app('db')->table('cases')->where('pk', $item['pk'])->first();
            $object[] = [
                'pk' => $item['pk'],
                'id' => $case->id
            ];

        }
        return $object;
    }

    public function get_nonshelf_case()
    {
        $nonshelf_cases = app('db')->table('cases')->where('is_active', True)->where('shelf_pk', Null)->get();
        $object = array();
        foreach ($nonshelf_cases as $nonshelf_case) {
            $received_groups = app('db')->table('received_groups')->where('case_pk', $nonshelf_case->pk)->exists();
            $issued_groups = app('db')->table('issued_groups')->where('case_pk', $nonshelf_case->pk)->exists();
            $is_empty = !$received_groups && !$issued_groups;
            $object[] = [
                'pk' => $nonshelf_case->pk,
                'id' => $nonshelf_case->id,
                'isEmpty' => $is_empty
            ];
        }
        return $object;
    }
}
