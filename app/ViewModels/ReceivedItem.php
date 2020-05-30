<?php

namespace App\ViewModels;

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Crypt;

class ReceivedItem extends ViewModel
{
    public function get($params)
    {
        $api = $params->header('api_token');
        $user_pk = Crypt::decrypt($api)['pk'];
        $kind = $params['kind'];
        $status = $params['status'];
        $externality = $params['externality'];
        $kind_filtered_object = $this->_kind_filter($kind);
        $status_filtered_object = $this->_status_filter($status, $kind_filtered_object);
        $externality_filtered_object = $this->_externality_filter($kind, $externality, $status_filtered_object);
        return $this->_translation($externality_filtered_object);
    }

    private function _kind_filter($kind)
    {
        $objects = array();
        if ($kind == 'imported' || $kind == Null) {
            $pks = app('db')->table('imported_items')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'imported'
                ];
            }
        }
        if ($kind == 'restored' || $kind == Null) {
            $pks = app('db')->table('restored_items')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'restored'
                ];
            }
        }
        return $objects;
    }

    private function _status_filter($status, $input_object)
    {
        $object = array();
        if ($status != Null) {
            foreach ($input_object as $item) {
                if ($this::status($item['pk'], $item['kind']) == $status) $object[] = [
                    'pk' => $item['pk'],
                    'kind' => $item['kind'],
                    'status' => $status,
                ];
            }
            return $object;
        }
        foreach ($input_object as $item) {
            $object[] = [
                'pk' => $item['pk'],
                'kind' => $item['kind'],
                'status' => $this::status($item['pk'], $item['kind'])
            ];
        }
        return $object;
    }

    public static function status($received_item_pk, $kind)
    {
        if ($kind == 'imported') {
            $imported_item = app('db')->table('imported_items')->where('pk', $received_item_pk)->select('import_pk', 'classified_item_pk')->first();
            $is_opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');
            if ($is_opened == True) return 'receiving';
            if ($imported_item->classified_item_pk == Null) return 'inspecting';
            $received_groups = app('db')->table('received_groups')->where('received_item_pk', $received_item_pk)->get();
            if (count($received_groups) == 0) return 'done';
            foreach ($received_groups as $received_group) {
                if ($received_group->case_pk == null) return 'done';
            }
            return 'classified';
        }
        if ($kind == 'restored') {
            $restoration_pk = app('db')->table('restored_items')->where('pk', $received_item_pk)->value('restoration_pk');
            $is_received = app('db')->table('restorations')->where('pk', $restoration_pk)->value('receiving_session_pk') == Null ? False : True;
            if ($is_received) return 'received';
            return 'pending';
        }
        return Null;
    }

    private function _externality_filter($kind, $externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('receiving_pks', $externality)) {
            if ($kind != Null) {
                $table = $kind == 'imported' ? 'imported_items' : 'restored_items';
                $column = $kind == 'imported' ? 'import_pk' : 'restoration_pk';
                $pks = array_intersect(app('db')->table("$table")->whereIn($column, $externality['receiving_pks'])->pluck('pk')->toArray(), $pks);

            } else {
                $temp = array_merge(app('db')->table('imported_items')->whereIn('import_pk', $externality['receiving_pks'])->pluck('pk')->toArray(),
                    app('db')->table('restored_items')->whereIn('restoration_pk', $externality['receiving_pks'])->pluck('pk')->toArray());
                $pks = array_intersect($temp, $pks);
            }
        }
        if ($externality != Null && array_key_exists('sendbacking_session_pks', $externality)) {
            $classified_item_pks = app('db')->table('classified_items')->whereIn('sendbacking_session_pk', $externality['sendbacking_session_pks'])->pluck('pk')->toArray();
            $pks = array_intersect(app('db')->table('imported_items')->whereIn('classified_item_pk', $classified_item_pks)->pluck('pk')->toArray(), $pks);
        }
        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'imported') {
                $imported_item = app('db')->table('imported_items')->where('pk', $item['pk'])->first();
                $failed = False;
                if ($imported_item->classified_item_pk != Null) {
                    $failed = app('db')->table('classified_items')->where('pk', $imported_item->classified_item_pk)->value('quality_state') == 'failed';
                }
                $accessory_pk = app('db')->table('ordered_items')->where('pk', $imported_item->ordered_item_pk)->value('accessory_pk');
                $input_object[$key] += [
                    'receivedQuantity' => $imported_item->imported_quantity,
                    'receivedComment' => $imported_item->comment,
                    'sumActualQuantity' => $failed ? 0 : $this::sum_actual_received_quantity($item['pk']),
                    'sumAdjustedQuantity' => $this::sum_adjusted_quantity($item['pk']),
                    'sumDiscardedQuantity' => $this::sum_discarded_quantity($item['pk']),
                    'accessory_pk' => $accessory_pk,
                    'imported_item_pk' => $item['pk']
                ];
            }
            if ($item['kind'] == 'restored') {
                $restored_item = app('db')->table('restored_items')->where('pk', $item['pk'])->first();
                $input_object[$key] += [
                    'receivedQuantity' => $restored_item->restored_quantity,
                    'sumActualQuantity' => $this::sum_actual_received_quantity($item['pk']),
                    'sumAdjustedQuantity' => $this::sum_adjusted_quantity($item['pk']),
                    'sumDiscardedQuantity' => $this::sum_discarded_quantity($item['pk']),
                    'accessory_pk' => $restored_item->accessory_pk
                ];
            }
        }
        $input_object = $this::imported_inspecting_translation($input_object);
        return $this::accessory_translation($input_object);
    }

    public static function sum_actual_received_quantity($received_item_pk)
    {
        $received_group_pks = app('db')->table('received_groups')->where('received_item_pk', $received_item_pk)->pluck('pk');
        $sum = 0;
        foreach ($received_group_pks as $received_group_pk) {
            $sum += ReceivedGroup::actual_quantity($received_group_pk);
        }
        return $sum;
    }

    public static function sum_adjusted_quantity($received_item_pk)
    {
        $adjusted_quantities = app('db')->table('entries')->where('received_item_pk', $received_item_pk)->where('entry_kind', 'adjusting')->pluck('quantity')->toArray();
        $sum = 0;
        foreach ($adjusted_quantities as $quantity) {
            if ($quantity != Null) $sum += $quantity;
        }
        return $sum;
    }

    public static function sum_discarded_quantity($received_item_pk)
    {
        $discarded_quantities = app('db')->table('entries')->where('received_item_pk', $received_item_pk)->where('entry_kind', 'discarding')->pluck('quantity')->toArray();
        $sum = 0;
        foreach ($discarded_quantities as $quantity) {
            if ($quantity != Null) $sum += $quantity;
        }
        return $sum;
    }

    private function imported_inspecting_translation($input_object)
    {
        foreach ($input_object as $key => $item) {
            if ($item['kind'] == 'imported') {
                $checking_info = ImportController::checking_info($item['imported_item_pk']);
                $sum_checking_quantity = $this::imported_sum_checking_quantity($item['imported_item_pk']);
                $input_object[$key] += [
                    'sample' => $checking_info['sample'],
                    'acceptance' => $checking_info['acceptance'],
                    'qualityState' => $this::imported_quality_state($item['imported_item_pk']),
                    'sumCheckedQuantity' => $sum_checking_quantity['checked_quantity'],
                    'sumUnqualifiedQuantity' => $sum_checking_quantity['unqualified_quantity'],
                ];
                unset($input_object[$key]['imported_item_pk']);
            }
        }
        return $input_object;
    }

    private static function imported_sum_checking_quantity($imported_item_pk)
    {
        $received_group_pks = app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->pluck('pk');
        $sum['checked_quantity'] = 0;
        $sum['unqualified_quantity'] = 0;
        foreach ($received_group_pks as $received_group_pk) {
            $checking_quantity = ReceivedGroup::imported_checking_quantity($received_group_pk);
            $sum['checked_quantity'] += $checking_quantity['checked_quantity'];
            $sum['unqualified_quantity'] += $checking_quantity['unqualified_quantity'];
        }
        return $sum;
    }

    public static function imported_quality_state($imported_item_pk)
    {
        $classified_item_pk = app('db')->table('imported_items')->where('pk', $imported_item_pk)->value('classified_item_pk');
        return $classified_item_pk == Null ? Null : app('db')->table('classified_items')->where('pk', $classified_item_pk)->value('quality_state');
    }

    public function get_failed_item()
    {
        $failed_items = app('db')->table('classified_items')->where('quality_state', 'failed')->where('sendbacking_session_pk', Null)->get();
        $object = array();
        foreach ($failed_items as $failed_item) {
            $received_item = app('db')->table('imported_items')->where('classified_item_pk', $failed_item->pk)->first();
            $received_groups = app('db')->table('received_groups')->where('received_item_pk', $received_item->pk)->get();
            $cases = array();
            foreach ($received_groups as $received_group) {
                $case_id = app('db')->table('cases')->where('pk', $received_group->case_pk)->value('id');
                $actual_quantity = ReceivedGroup::actual_quantity($received_group->pk);
                $cases[] = [
                    'id' => $case_id,
                    'pk' => $received_group->case_pk,
                    'quantity' => $actual_quantity
                ];
            }
            $object[] = [
                'classifiedItemPk' => $failed_item->pk,
                'receivedQuantity' => $received_item->imported_quantity,
                'cases' => $cases,
                'received_item_pk' => $received_item->pk,
            ];
        }
        return $this::received_item_translation($object);
    }
}
