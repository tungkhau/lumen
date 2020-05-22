<?php

namespace App\ViewModels;

class InBlockedItem extends ViewModel
{
    private $in_shelved_item;

    public function __construct(InShelvedItem $in_shelved_item)
    {
        $this->in_shelved_item = $in_shelved_item;
    }

    public function get($params)
    {
        $block_pks = $params['externality']['block_pks'];
        $shelf_pks = app('db')->table('shelves')->whereIn('block_pk', $block_pks)->pluck('pk')->toArray();
        $tmp['externality']['shelf_pks'] = $shelf_pks;
        $in_shelved_items = $this->in_shelved_item->get($tmp);
        $pending_array = array();
        foreach ($in_shelved_items as $in_shelved_item) {
            if ($in_shelved_item['isPending'] == true) array_push($pending_array, $in_shelved_item['receivedItemPk']);
        }
        $pending_array = array_unique($pending_array);
        $tmp2 = collect($in_shelved_items)->mapToGroups(function ($item, $key) {
            return [$item['receivedItemPk'] => $item['inShelvedQuantity']];
        });
        $tmp3 = array();
        foreach ($tmp2 as $received_item_pk => $grouped_quantities) {
            $tmp3[] = [
                'received_item_pk' => $received_item_pk,
                'receivedItemPk' => $received_item_pk,
                'inBlockedQuantity' => $grouped_quantities->sum()
            ];
        }
        foreach ($tmp3 as $key => $item) {
            if (in_array($item['received_item_pk'], $pending_array)) $tmp3[$key] += ['isPending' => true];
            $tmp3[$key] += ['isPending' => false];
        }
        return $this::received_item_translation($tmp3);
    }
}
