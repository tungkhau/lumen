<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\DB;

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

    public function get_suitable_case($params)
    {
        $externality = $params['externality'];
        $demand_pk = $externality['demand_pk'];
        $tmp = app('db')->table('demanded_items')->where('demand_pk', $demand_pk)->select('demanded_quantity', 'accessory_pk', 'pk')->get();
        $tmp1 = array(); //list demanded_item_pk
        foreach ($tmp as $i) {
            array_push($tmp1, $i->pk);
        }
        $tmp2 = app('db')->table('issued_items')->whereIn('end_item_pk', $tmp1)->where('is_returned', 0)
            ->select((array('end_item_pk', DB::raw('SUM(issued_quantity) as issued_quantity'))))
            ->groupBy('end_item_pk')->get()->toArray();
        $eli = array(); //eliminated accessory_pk list
        foreach ($tmp as $demanded_item) {
            foreach ($tmp2 as $issued_item) {
                if ($issued_item->end_item_pk == $demanded_item->pk && $issued_item->issued_quantity == $demanded_item->demanded_quantity) {
                    array_push($eli, $demanded_item->accessory_pk);
                }
            }
        }
        $accessory_pks = array();
        foreach ($tmp as $item) {
            if (!in_array($item->accessory_pk, $eli)) array_push($accessory_pks, $item->accessory_pk);
        }


        $entries = app('db')->table('entries')
            ->where('quantity', '!=', Null)
            ->whereIn('accessory_pk', $accessory_pks)
            ->select((array('received_item_pk', 'case_pk', DB::raw('SUM(quantity) as inCasedQuantity'))))
            ->groupBy('case_pk', 'received_item_pk')->get();
        $tmp = array();
        foreach ($entries as $entry) {
            if ($entry->inCasedQuantity != 0)
                $tmp[] = [
                    'received_item_pk' => $entry->received_item_pk,
                    'case_pk' => $entry->case_pk
                ];
        }

        $filters = app('db')->table('entries')
            ->where('quantity', Null)
            ->select((array(DB::raw('CONCAT(received_item_pk,"",case_pk) as filter'))))->pluck('filter')->toArray();

        $case_pks = array();
        foreach ($tmp as $key => $item) {
            $temp = $item['received_item_pk'] . $item['case_pk'];
            if (!in_array($temp, $filters)) array_push($case_pks, $item['case_pk']);
        }
        $unique_case_pks = array_unique($case_pks);

        $tmp = array();
        foreach ($unique_case_pks as $unique_case_pk) {
            $case = app('db')->table('cases')->where('pk', $unique_case_pk)->first();
            $shelf_id = app('db')->table('shelves')->where('pk', $case->shelf_pk)->value('name');
            $tmp[] = [
                'pk' => $unique_case_pk,
                'shelfId' => $shelf_id,
                'id' => $case->id,
            ];
        }
        return $tmp;
    }
}
