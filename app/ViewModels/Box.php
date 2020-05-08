<?php

namespace App\ViewModels;

class Box extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this::sort_response($this->_translation($externality_filtered_object), 'createdDate');
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

        if ($externality != Null && array_key_exists('shelf_ids', $externality)) {
            $shelf_pks = app('db')->table('shelves')->whereIn('name', $externality['shelf_ids'])->pluck('pk');
            $pks = array_intersect(app('db')->table('cases')->whereIn('shelf_pk', $shelf_pks)->pluck('pk')->toArray(), $pks);
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
                'id' => $case->id,
                'isEmpty' => $this::is_empty($case->pk),
                'createdDate' => $case->created_at,
            ];

        }
        return $object;
    }

    public static function is_empty($case_pk)
    {
        $issued_groups = app('db')->table('issued_groups')->where('case_pk', $case_pk)->exists();
        if ($issued_groups) return False;
        $received_groups = app('db')->table('received_groups')->where('case_pk', $case_pk)->exists();
        if ($received_groups) return False;
        $pending = app('db')->table('entries')->where('case_pk', $case_pk)->where('quantity', Null)->exists();
        if ($pending) return False;
        $sum = app('db')->table('entries')->where('case_pk', $case_pk)->where('quantity', '!=', Null)->sum('quantity');
        if ($sum != 0) return False;
        return True;
    }

    public function get_unstored_case()
    {
        $pks = app('db')->table('cases')->where('is_active', True)->where('shelf_pk', Null)->pluck('pk')->toArray();
        $sealed_case_pks = app('db')->table('issued_groups')->distinct('case_pk')->pluck('case_pk')->toArray();
        $pks = array_diff($pks, $sealed_case_pks);
        $unstored_cases = app('db')->table('cases')->whereIn('pk', $pks)->get();

        $object = array();
        foreach ($unstored_cases as $unstored_case) {
            $object[] = [
                'pk' => $unstored_case->pk,
                'id' => $unstored_case->id,
                'isEmpty' => $this::is_empty($unstored_case->pk),
            ];
        }
        return $object;
    }
}
