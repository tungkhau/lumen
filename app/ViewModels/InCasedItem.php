<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\DB;

class InCasedItem extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {

        if ($externality != Null && array_key_exists('accessory_pks', $externality)) {
            $entries = app('db')->table('entries')
                ->where('quantity', '!=', Null)
                ->whereIn('accessory_pk', $externality['accessory_pks'])
                ->select((array('received_item_pk', 'accessory_pk', 'case_pk', DB::raw('SUM(quantity) as inCasedQuantity'))))
                ->groupBy('case_pk', 'received_item_pk')->get();
        } elseif ($externality != Null && array_key_exists('case_pks', $externality)) {
            $entries = app('db')->table('entries')
                ->where('quantity', '!=', Null)
                ->whereIn('case_pk', $externality['case_pks'])
                ->select((array('received_item_pk', 'accessory_pk', 'case_pk', DB::raw('SUM(quantity) as inCasedQuantity'))))
                ->groupBy('case_pk', 'received_item_pk')->get();
        } elseif ($externality != Null && array_key_exists('moving_session_pks', $externality)) {
            $entries = app('db')->table('entries')
                ->where('quantity', '!=', Null)
                ->where('entry_kind', 'in')
                ->whereIn('session_pk', $externality['moving_session_pks'])
                ->select((array('received_item_pk', 'accessory_pk', 'case_pk', DB::raw('SUM(quantity) as inCasedQuantity'))))
                ->groupBy('case_pk', 'received_item_pk')->get();
        } else {
            $entries = app('db')->table('entries')
                ->where('quantity', '!=', Null)
                ->select((array('received_item_pk', 'accessory_pk', 'case_pk', DB::raw('SUM(quantity) as inCasedQuantity'))))
                ->groupBy('case_pk', 'received_item_pk')->get();
        }
        $object = array();
        foreach ($entries as $entry) {
            if ($entry->inCasedQuantity != 0)
                $object[] = [
                    'received_item_pk' => $entry->received_item_pk,
                    'accessoryPk' => $entry->accessory_pk,
                    'case_pk' => $entry->case_pk,
                    'inCasedQuantity' => $entry->inCasedQuantity,
                    'receivedItemPk' => $entry->received_item_pk,
                    'casePk' => $entry->case_pk,
                ];
        }
        return $object;
    }

    private function _translation($input_object)
    {
        $input_object = $this::is_pending($input_object);
        $input_object = $this::position_id($input_object);
        return $this::received_item_translation($input_object);
    }

    private static function is_pending($input_object)
    {
        $filters = app('db')->table('entries')
            ->where('quantity', Null)
            ->select((array(DB::raw('CONCAT(received_item_pk,"",case_pk) as filter'))))->pluck('filter')->toArray();

        foreach ($input_object as $key => $item) {
            $temp = $item['received_item_pk'] . $item['case_pk'];
            if (in_array($temp, $filters)) $input_object[$key] += ['isPending' => True];
            $input_object[$key] += ['isPending' => False];
        }
        return $input_object;
    }

    private static function position_id($input_object)
    {
        foreach ($input_object as $key => $item) {
            $case = app('db')->table('cases')->where('pk', $item['case_pk'])->first();
            $shelf_id = app('db')->table('shelves')->where('pk', $case->shelf_pk)->value('name');
            $input_object[$key] += [
                'caseId' => $case->id,
                'shelfId' => $shelf_id
            ];
            unset($input_object[$key]['case_pk']);
        }
        return $input_object;
    }


}
