<?php

namespace App\ViewModels;

class Report extends ViewModel
{
    private static function sumOrderedQuantity($accessory_pk)
    {
        return app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->sum('ordered_quantity');
    }

    private static function sumInDistributedQuantity()
    {
        return 0;
    }

    private static function sumImportedQuantity($accessory_pk)
    {
        $ordered_item_pks = app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();
        if (count($ordered_item_pks) != 0) return app('db')->table('imported_items')->whereIn('ordered_item_pk', $ordered_item_pks)->sum('imported_quantity');
        return 0;
    }

    private static function sumInTransferredQuantity()
    {
        return 0;
    }

    private static function sumRestoredQuantity($accessory_pk)
    {
        return app('db')->table('restored_items')->where('accessory_pk', $accessory_pk)->sum('restored_quantity');
    }

    private static function sumActualImportedQuantity($accessory_pk)
    {
        $ordered_item_pks = app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();
        if (count($ordered_item_pks) == 0) return 0;
        $sum = 0;
        foreach ($ordered_item_pks as $ordered_item_pk) {
            $sum += RootReceivedItem::sum_actual_received_quantity($ordered_item_pk, 'ordered');
        }
        return $sum;
    }

    private static function sumActualInTransferredQuantity()
    {
        return 0;
    }

    private static function sumActualRestoredQuantity($accessory_pk)
    {
        $restored_item_pks = app('db')->table('restored_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();
        if (count($restored_item_pks) == 0) return 0;
        $sum = 0;
        foreach ($restored_item_pks as $restored_item_pk) {
            $sum += ReceivedItem::sum_actual_received_quantity($restored_item_pk);
        }
        return $sum;
    }

    private static function sumAdjustedQuantity($accessory_pk)
    {
        return app('db')->table('entries')->where('accessory_pk', $accessory_pk)->where('entry_kind', 'adjusting')->where('quantity', '!=', Null)->sum('quantity');
    }

    private static function sumDiscardedQuantity($accessory_pk)
    {
        return app('db')->table('entries')->where('accessory_pk', $accessory_pk)->where('entry_kind', 'discarding')->where('quantity', '!=', Null)->sum('quantity');
    }

    private static function sumDemandedQuantity($accessory_pk)
    {
        return app('db')->table('demanded_items')->where('accessory_pk', $accessory_pk)->sum('demanded_quantity');
    }

    private static function sumOutDistributedQuantity()
    {
        return 0;
    }

    private static function sumConsumedQuantity($accessory_pk)
    {
        $demanded_item_pks = app('db')->table('demanded_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();
        if (count($demanded_item_pks) == 0) return 0;
        return app('db')->table('issued_items')->whereIn('end_item_pk', $demanded_item_pks)->where('is_returned', False)->sum('issued_quantity');
    }

    private static function sumOutTransferredQuantity()
    {
        return 0;
    }

    private static function prestoredQuantity($accessory_pk)
    {
        $ordered_item_pks = app('db')->table('ordered_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();
        $in_distributed_item_pks = app('db')->table('in_distributed_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();

        if (count($ordered_item_pks) == 0) $imported_item_pks = array();
        else $imported_item_pks = app('db')->table('imported_items')->whereIn('ordered_item_pk', $ordered_item_pks)->pluck('pk')->toArray();

        if (count($in_distributed_item_pks) == 0) $in_transferred_item_pks = array();
        else $in_transferred_item_pks = app('db')->table('in_transferred_items')->whereIn('in_distributed_item_pk', $in_distributed_item_pks)->pluck('pk')->toArray();

        $restored_item_pks = app('db')->table('restored_items')->where('accessory_pk', $accessory_pk)->pluck('pk')->toArray();

        $received_item_pks = array_merge($imported_item_pks, $in_transferred_item_pks, $restored_item_pks);

        if (count($received_item_pks) == 0) return 0;
        return app('db')->table('received_groups')->whereIn('received_item_pk', $received_item_pks)->where('case_pk', '!=', Null)->sum('grouped_quantity');
    }

    private static function storedQuantity($accessory_pk)
    {
        return app('db')->table('entries')->where('accessory_pk', $accessory_pk)->where('quantity', '!=', Null)->sum('quantity');
    }

    public function get($params)
    {
        $externality = $params['externality'];
        $externality_filtered_object = $this->_externality_filter($externality);
        return $this->_translation($externality_filtered_object);
    }

    private function _externality_filter($externality)
    {
        $pks = app('db')->table('accessories')->pluck('pk')->toArray();
        $object = array();
        foreach ($pks as $pk) {
            $object[] = [
                'accessory_pk' => $pk
            ];
        }

        if ($externality != Null && array_key_exists('accessory_pks', $externality)) {
            $pks = array_intersect($externality['accessory_pks'], $pks);
        }

        if ($externality != Null && array_key_exists('conception_pks', $externality)) {
            $pks = array_intersect(app('db')->table('accessories_conceptions')->whereIn('conception_pk', $externality['conception_pks'])->pluck('accessory_pk')->toArray(), $pks);
        }

        foreach ($object as $key => $item) {
            if (!in_array($item['accessory_pk'], $pks)) unset($object[$key]);
        }
        return $object;
    }

    private function _translation($input_object)
    {
        $object = array();
        foreach ($input_object as $item) {
            $prestored_quantity = $this::prestoredQuantity($item['accessory_pk']);
            $stored_quantity = $this::storedQuantity($item['accessory_pk']);
            $object[] = [
                'accessory_pk' => $item['accessory_pk'],
                'sumOrderedQuantity' => $this::sumOrderedQuantity($item['accessory_pk']),
                'sumInDistributedQuantity' => $this::sumInDistributedQuantity(),
                'sumImportedQuantity' => $this::sumImportedQuantity($item['accessory_pk']),
                'sumInTransferredQuantity' => $this::sumInTransferredQuantity(),
                'sumRestoredQuantity' => $this::sumRestoredQuantity($item['accessory_pk']),
                'sumActualImportedQuantity' => $this::sumActualImportedQuantity($item['accessory_pk']),
                'sumActualInTransferredQuantity' => $this::sumActualInTransferredQuantity(),
                'sumActualRestoredQuantity' => $this::sumActualRestoredQuantity($item['accessory_pk']),
                'sumAdjustedQuantity' => $this::sumAdjustedQuantity($item['accessory_pk']),
                'sumDiscardedQuantity' => $this::sumDiscardedQuantity($item['accessory_pk']),
                'sumDemandedQuantity' => $this::sumDemandedQuantity($item['accessory_pk']),
                'sumOutDistributedQuantity' => $this::sumOutDistributedQuantity(),
                'sumConsumedQuantity' => $this::sumConsumedQuantity($item['accessory_pk']),
                'sumOutTransferredQuantity' => $this::sumOutTransferredQuantity(),
                'prestoredQuantity' => $prestored_quantity,
                'storedQuantity' => $stored_quantity,
                'totalQuantity' => $prestored_quantity + $stored_quantity,
            ];

        }
        return $this::accessory_translation($object);
    }
}
