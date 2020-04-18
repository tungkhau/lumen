<?php

namespace App\ViewModels;

class Receiving extends ViewModel
{
    public function get($params)
    {
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
        if ($kind == 'import' || $kind == Null) {
            $pks = app('db')->table('imports')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'import'
                ];
            }
        }
        if ($kind == 'restoration' || $kind == Null) {
            $pks = app('db')->table('restorations')->pluck('pk');
            foreach ($pks as $pk) {
                $objects[] = [
                    'pk' => $pk,
                    'kind' => 'restoration'
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

    public static function status($receiving_pk, $kind)
    {
        if ($kind == 'import') {
            $is_opened = app('db')->table('imports')->where('pk', $receiving_pk)->value('is_opened');
            if ($is_opened == True) return 'opened';
            $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk');
            foreach ($imported_item_pks as $imported_item_pk) {
                if (ReceivedItem::status($imported_item_pk, 'imported') != 'done') return 'closed';
            }
            return 'done';
        }
        if ($kind == 'restoration') {
            $restoration = app('db')->table('restorations')->where('pk', $receiving_pk)->select('is_confirmed', 'receiving_session_pk')->first();
            if (!$restoration->is_confirmed) return 'unconfirmed';
            if ($restoration->receiving_session_pk == Null) return 'confirmed';
            return 'received';
        }
        return Null;
    }

    private function _externality_filter($kind, $externality, $input_object)
    {
        $pks = array();
        foreach ($input_object as $item) {
            array_push($pks, $item['pk']);
        }
        if ($externality != Null && array_key_exists('user_pks', $externality)) {
            if ($kind != Null) {
                $table = $kind == 'import' ? 'imports' : 'restorations';
                $pks = array_intersect(app('db')->table("$table")->whereIn('user_pk', $externality['user_pks'])->pluck('pk')->toArray(), $pks);

            } else {
                $temp = array_merge(app('db')->table('imports')->whereIn('user_pk', $externality['user_pks'])->pluck('pk')->toArray(),
                    app('db')->table('restorations')->whereIn('user_pk', $externality['user_pks'])->pluck('pk')->toArray());
                $pks = array_intersect($temp, $pks);
            }
        }
        if ($externality != Null && array_key_exists('receiving_pks', $externality)) {
            if ($kind != Null) {
                $table = $kind == 'import' ? 'imports' : 'restorations';
                $pks = array_intersect(app('db')->table("$table")->whereIn('pk', $externality['receiving_pks'])->pluck('pk')->toArray(), $pks);

            } else {
                $temp = array_merge(app('db')->table('imports')->whereIn('pk', $externality['receiving_pks'])->pluck('pk')->toArray(),
                    app('db')->table('restorations')->whereIn('pk', $externality['receiving_pks'])->pluck('pk')->toArray());
                $pks = array_intersect($temp, $pks);
            }
        }
        foreach ($input_object as $key => $item) {
            if (!in_array($item['pk'], $pks)) unset($input_object[$key]);
        }
        return $input_object;
    }

    private function _translation($input_object)
    {
        $object = array();
        foreach ($input_object as $item) {
            if ($item['kind'] == 'import') {
                $import = app('db')->table('imports')->where('pk', $item['pk'])->first();
                $object[] = [
                    'pk' => $item['pk'],
                    'kind' => $item['kind'],
                    'status' => $item['status'],
                    'isMutable' => $this::is_mutable($item['pk'], $item['kind']),
                    'isSwitchable' => $this::is_switchable($item['pk'], $item['kind']),
                    'id' => $import->id,
                    'createdDate' => $import->created_date,
                    'sourceName' => $this::source_name($item['pk'], $item['kind']),
                    'user_pk' => $import->user_pk,
                ];
            }
            if ($item['kind'] == 'restoration') {
                $restoration = app('db')->table('restorations')->where('pk', $item['pk'])->first();
                $object[] = [
                    'pk' => $item['pk'],
                    'kind' => $item['kind'],
                    'status' => $item['status'],
                    'id' => $restoration->id,
                    'createdDate' => $restoration->created_date,
                    'sourceName' => $this::source_name($item['pk'], $item['kind']),
                    'user_pk' => $restoration->user_pk,
                ];
            }
        }
        return $this::user_translation($object);
    }

    public static function is_mutable($receiving_pk, $kind)
    {
        if ($kind == 'import') {
            $received_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk');
            return app('db')->table('received_groups')->whereIn('received_item_pk', $received_item_pks)->doesntExist();
        }
        return Null;
    }

    public static function is_switchable($receiving_pk, $kind)
    {
        if ($kind == 'import') {
            $is_opened = app('db')->table('imports')->where('pk', $receiving_pk)->value('is_opened');
            if ($is_opened) {
                $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk')->toArray();
                return app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
            }

            $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $receiving_pk)->pluck('pk')->toArray();
            $checked_or_counted = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->where([['counting_session_pk', '!=', Null], ['checking_session_pk', '!=', Null]])->exists();
            $classified = app('db')->table('imported_items')->where([['import_pk', $receiving_pk], ['classified_item_pk', '!=', Null]])->exists();
            return !$checked_or_counted || $classified;
        }
        return Null;
    }

    public static function source_name($receiving_pk, $kind)
    {
        if ($kind == 'import') {
            $order_pk = app('db')->table('imports')->where('pk', $receiving_pk)->value('order_pk');
            $supplier_pk = app('db')->table('orders')->where('pk', $order_pk)->value('supplier_pk');
            return app('db')->table('suppliers')->where('pk', $supplier_pk)->value('name');
        }

        if ($kind == 'restoration') {
            $user_pk = app('db')->table('restorations')->where('pk', $receiving_pk)->value('user_pk');
            $workplace_pk = app('db')->table('users')->where('pk', $user_pk)->value('workplace_pk');
            return app('db')->table('workplaces')->where('pk', $workplace_pk)->value('name');
        }
        return Null;
    }

    public function find($params)
    {
        //TODO
    }
}
