<?php

namespace App\Http\Controllers;

use App\ViewModels\Accessory;
use App\ViewModels\Conception;
use App\ViewModels\Partner;
use App\ViewModels\ReceivedGroup;
use App\ViewModels\ReceivedItem;
use App\ViewModels\Receiving;
use App\ViewModels\RootIssuedItem;
use App\ViewModels\RootIssuing;
use App\ViewModels\RootReceivedItem;
use App\ViewModels\RootReceiving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AngularController extends Controller
{
    private $receiving;
    private $accessory;
    private $received_item;
    private $received_group;
    private $root_received_item;
    private $root_receiving;
    private $partner;
    private $root_issued_item;
    private $conception;
    private $root_issuing;

    public function __construct(RootIssuing $root_issuing, RootIssuedItem $root_issued_item, Partner $partner, Receiving $receiving, Accessory $accessory, ReceivedItem $received_item, ReceivedGroup $received_group, RootReceivedItem $root_received_item, RootReceiving $root_receiving, Conception $conception)
    {
        $this->receiving = $receiving;
        $this->accessory = $accessory;
        $this->received_item = $received_item;
        $this->received_group = $received_group;
        $this->root_received_item = $root_received_item;
        $this->root_receiving = $root_receiving;
        $this->conception = $conception;
        $this->partner = $partner;
        $this->root_issued_item = $root_issued_item;
        $this->root_issuing = $root_issuing;
    }

    public function get_partner(Request $request)
    {
        $response = $this->partner->get($request);
        $response = array_values($response);
        return response()->json(['partners' => $response], 201);
    }

    public function get_receiving(Request $request)
    {
        $response = $this->receiving->get($request);
        $response = array_values($response);
        return response()->json(['receivings' => $response], 201);
    }

    public function get_accessory(Request $request)
    {
        $response = $this->accessory->get($request);
        $response = array_values($response);
        return response()->json(['accessories' => $response], 201);
    }

    public function get_received_item(Request $request)
    {
        $response = $this->received_item->get($request);
        $response = array_values($response);
        return response()->json(['received-items' => $response], 201);
    }

    public function get_received_group(Request $request)
    {
        $response = $this->received_group->get($request);
        $response = array_values($response);
        return response()->json(['received_groups' => $response], 201);
    }

    public function get_root_received_item(Request $request)
    {
        $response = $this->root_received_item->get($request);
        $response = array_values($response);
        return response()->json(['root-received-items' => $response], 201);
    }

    public function get_root_receiving(Request $request)
    {
        $response = $this->root_receiving->get($request);
        $response = array_values($response);
        return response()->json(['root-receivings' => $response], 201);
    }

    public function get_conception(Request $request)
    {
        $response = $this->conception->get($request);
        $response = array_values($response);
        return response()->json(['conceptions' => $response], 201);
    }

    public function get_root_issued_item(Request $request)
    {
        $response = $this->root_issued_item->get($request);
        $response = array_values($response);
        return response()->json(['root-received_items' => $response], 201);
    }

    public function get_root_issuing(Request $request)
    {
        $response = $this->root_issuing->get($request);
        $response = array_values($response);
        return response()->json(['root_issuings' => $response], 201);
    }


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
        $response = array_values($response);
        return response()->json(['activity-logs' => $response], 201);
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

    public function get_inventories(Request $request)
    {
        $entry_temp = app('db')->table('entries')->where($request->all())->where('quantity', '!=', Null)
            ->select((array('accessory_pk', DB::raw('SUM(quantity) as stored_quantity'))))
            ->groupBy('accessory_pk')->get()->toArray();

        $received_groups = app('db')->table('received_groups')->where($request->all())->where('case_pk', '!=', Null)
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
                'accessoryId' => $info['id'],
                'accessoryName' => $info['name'],
                'accessoryItem' => $info['item'],
                'accessoryArt' => $info['art'],
                'accessoryColor' => $info['color'],
                'accessorySize' => $info['size'],
                'accessoryUnit' => $info['unit'],
                'accessoryCustomerName' => $info['customer'],
                'accessorySupplierName' => $info['supplier'],
                'inventoryPrestored' => $value['prestored_quantity'],
                'inventoryStored' => $value['stored_quantity'],
                'inventoryTotal' => $value['prestored_quantity'] + $value['stored_quantity'],
            ];
        }
        return response()->json(['inventories' => $response], 201);
    }

}

