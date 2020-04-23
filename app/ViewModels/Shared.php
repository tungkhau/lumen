<?php

namespace App\ViewModels;

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\ReceivedGroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;

class Shared extends ViewModel
{
    public function get_activity_log()
    {
        $temp = app('db')->table('activity_logs')->orderBy('created_date', 'desc')->get();
        $response = array();
        foreach ($temp as $item) {
            $user = UserController::info($item->user_pk);
            $response[] = [
                'type' => $this::translate_type($item->type),
                'object' => $this::translate_object($item->object),
                'createdDate' => $item->created_date,
                'userName' => $user['name'],
                'userId' => $user['id'],
                'id' => $item->id,
            ];
        }
        return $response;
    }

    private static function translate_type($type)
    {
        switch ($type) {
            case 'create' :
                return 'Tạo mới';
            case 'update' :
                return 'Cập nhật';
            case 'delete' :
                return 'Xóa';
            case 'deactivate' :
                return 'Ẩn';
            case 'reactivate' :
                return 'Khôi phục';
            case 'link' :
                return 'Liên kết';
            case 'unlink' :
                return 'Hủy liên kết';
            default :
                return 'Cập nhật hình ảnh';
        }
    }

    private static function translate_object($object)
    {
        switch ($object) {
            case 'customer' :
                return 'Khách hàng';
            case 'supplier' :
                return 'Nhà cung cấp';
            case 'accessory' :
                return 'Phụ liệu';
            default :
                return 'Mã hàng';
        }
    }

    public function get_inventory()
    {
        $entry_temp = app('db')->table('entries')->where('quantity', '!=', Null)
            ->select((array('accessory_pk', DB::raw('SUM(quantity) as stored_quantity'))))
            ->groupBy('accessory_pk')->get()->toArray();

        $received_groups = app('db')->table('received_groups')->where('case_pk', '!=', Null)
            ->select('pk', 'received_item_pk', 'grouped_quantity')->get();
        $first_temp = array();
        foreach ($received_groups as $received_group) {
            $first_temp[] = [
                'received_item_pk' => $received_group->received_item_pk,
                'actual_quantity' => ReceivedGroupController::actual_quantity($received_group->pk),
            ];
        }
        $temp = array();
        foreach ($first_temp as $item) {
            $temp[] = [
                'accessory_pk' => ReceivedGroupController::accessory_pk($item['received_item_pk']),
                'prestored_quantity' => $item['actual_quantity'],
            ];
        }
        $mapped_temp = collect($temp)->mapToGroups(function ($item, $key) {
            return [$item['accessory_pk'] => $item['prestored_quantity']];
        });

        $sum_temp = array();
        foreach ($mapped_temp as $accessory_pk => $grouped_quantities) {
            $sum_temp[$accessory_pk] = [
                'prestored_quantity' => $grouped_quantities->sum(),
            ];
        }
        $entries = array();
        foreach ($entry_temp as $item) {
            $entries[$item->accessory_pk] = [
                'stored_quantity' => $item->stored_quantity
            ];
        }
        //For testing
//        $inventory = array();
//        foreach ($sum_temp as $accessory_pk => $value) {
//            $inventory[$accessory_pk] = [
//                'prestored_quantity' => $value['prestored_quantity'],
//                'stored_quantity' => array_key_exists($accessory_pk,$entries) ? $entries[$accessory_pk]['stored_quantity'] : 0,
//            ];
//        }
//        foreach ($entries as $accessory_pk => $value) {
//            if(array_key_exists($accessory_pk, $inventory)) continue;
//            $inventory[$accessory_pk] = [
//                'prestored_quantity' => 0,
//                'stored_quantity' => $value['stored_quantity']
//            ];
//        }
        $inventory = array();
        foreach ($entries as $accessory_pk => $value) {
            $inventory[$accessory_pk] = [
                'stored_quantity' => $value['stored_quantity'],
                'prestored_quantity' => array_key_exists($accessory_pk, $sum_temp) ? $sum_temp[$accessory_pk]['prestored_quantity'] : 0,
            ];
        }
        foreach ($sum_temp as $accessory_pk => $value) {
            if (array_key_exists($accessory_pk, $inventory)) continue;
            $inventory[$accessory_pk] = [
                'stored_quantity' => 0,
                'prestored_quantity' => $value['prestored_quantity'],
            ];
        }
        $response = array();
        foreach ($inventory as $accessory_pk => $value) {
            $info = AccessoryController::info($accessory_pk);
            $response[] = [
                'accessoryPk' => $accessory_pk,
                'accessoryId' => $info['id'],
                'accessoryName' => $info['name'],
                'accessoryItem' => $info['item'],
                'accessoryArt' => $info['art'],
                'accessoryColor' => $info['color'],
                'accessorySize' => $info['size'],
                'accessoryUnitName' => $info['unit'],
                'accessoryTypeName' => $info['type'],
                'accessoryCustomerName' => $info['customer'],
                'accessorySupplierName' => $info['supplier'],
                'prestoredQuantity' => $value['prestored_quantity'],
                'storedQuantity' => $value['stored_quantity'],
                'totalQuantity' => $value['prestored_quantity'] + $value['stored_quantity'],
            ];
        }
        return $response;
    }

    public function get_block()
    {
        $blocks = app('db')->table('blocks')->get();
        $object = array();
        foreach ($blocks as $block) {
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

    public function get_history()
    {

    }

    public function get_type()
    {
        $types = app('db')->table('types')->get();
        $object = array();
        foreach ($types as $type) {
            $object[] = [
                'pk' => $type->pk,
                'name' => $type->name,
            ];
        }
        return $object;
    }

    public function get_unit()
    {
        $units = app('db')->table('units')->get();
        $object = array();
        foreach ($units as $unit) {
            $object[] = [
                'pk' => $unit->pk,
                'name' => $unit->name,
            ];
        }
        return $object;
    }

    public function get_mediator()
    {
        $mediators = app('db')->table('users')->where('role', 'mediator')->get();
        $object = array();
        foreach ($mediators as $mediator) {
            $workplace_name = app('db')->table('workplaces')->where('pk', $mediator->workplace_pk)->value('name');
            $object[] = [
                'pk' => $mediator->pk,
                'name' => $mediator->name,
                'id' => $mediator->id,
                'workplaceName' => $workplace_name
            ];
        }
        return $object;
    }

}
