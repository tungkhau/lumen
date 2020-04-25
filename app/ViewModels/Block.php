<?php

namespace App\ViewModels;

class Block extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('blocks')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('block_pks', $externality)) {
            $pks = array_intersect($externality['block_pks'], $pks);
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
            $block = app('db')->table('blocks')->where('pk', $item['pk'])->first();
            $object[] = [
                'pk' => $block->pk,
                'id' => $block->id,
                'col' => $block->col,
                'row' => $block->row,
                'status' => $block->is_active ? 'active' : 'inactive',
            ];

        }
        return $object;
    }
}
