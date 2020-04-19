<?php

namespace App\ViewModels;

class Inventory extends ViewModel
{
    public function get($params)
    {
        $externality = $params['externality'];
        $status_filtered_object = $this->_status_filter($status);
        $externality_filtered_object = $this->_externality_filter($externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);
    }
    private function _externality_filter($externality, $input_object)
    {
        if ($externality != Null && array_key_exists('accessory_pks', $externality)) {
            $pks = array_intersect(app('db')->table('accessories_conceptions')->whereIn('accessory_pk', $externality['accessory_pks'])->pluck('conception_pk')->toArray(), $pks);
        }

        if ($externality != Null && array_key_exists('conception_pks', $externality)) {
            $pks = array_intersect($externality['conception_pks'], $pks);
        }

        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }
}
