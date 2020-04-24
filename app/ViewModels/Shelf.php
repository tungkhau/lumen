<?php

namespace App\ViewModels;

class Shelf extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('shelves')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('block_pks', $externality)) {
            $pks = array_intersect(app('db')->table('shelves')->whereIn('block_pk', $externality['block_pks'])->pluck('pk')->toArray(), $pks);
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
            $shelf_id = app('db')->table('shelves')->where('pk', $item['pk'])->value('name');
            $object[] = [
                'pk' => $item['pk'],
                'id' => $shelf_id,
            ];

        }
        return $object;
    }

}
