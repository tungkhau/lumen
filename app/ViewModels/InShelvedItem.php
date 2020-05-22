<?php

namespace App\ViewModels;

class InShelvedItem extends ViewModel
{
    private $in_cased_item;

    public function __construct(InCasedItem $in_cased_item)
    {
        $this->in_cased_item = $in_cased_item;
    }

    public function get($params)
    {
        $shelf_pks = $params['externality']['shelf_pks'];
        $case_pks = app('db')->table('cases')->whereIn('shelf_pk', $shelf_pks)->pluck('pk')->toArray();
        $tmp['externality']['case_pks'] = $case_pks;
        $in_cased_items = $this->in_cased_item->get($tmp);
        $pending_array = array();
        foreach ($in_cased_items as $in_cased_item) {
            if ($in_cased_item['isPending'] == true) array_push($pending_array, $in_cased_item['receivedItemPk']);
        }
        $pending_array = array_unique($pending_array);
        $tmp2 = collect($in_cased_items)->mapToGroups(function ($item, $key) {
            return [$item['receivedItemPk'] => $item['inCasedQuantity']];
        });
        $tmp3 = array();
        foreach ($tmp2 as $received_item_pk => $grouped_quantities) {
            $tmp3[] = [
                'received_item_pk' => $received_item_pk,
                'receivedItemPk' => $received_item_pk,
                'inShelvedQuantity' => $grouped_quantities->sum()
            ];
        }
        foreach ($tmp3 as $key => $item) {
            if (in_array($item['received_item_pk'], $pending_array)) $tmp3[$key] += ['isPending' => true];
            $tmp3[$key] += ['isPending' => false];
        }
        return $this::received_item_translation($tmp3);
    }
}
