<?php

namespace App\Http\Controllers;

use App\ViewModels\Receiving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AngularController extends Controller
{
    private $receiving;

    public function __construct(Receiving $receiving)
    {
        $this->receiving = $receiving;
    }

    public function get_orders(Request $request)
    {
        $orders = app('db')->table('orders')->where($request->all())->get();
        $response = array();
        foreach ($orders as $order) {
            $user = UserController::info($order->user_pk);
            $supplier = SupplierController::info($order->supplier_pk);
            $response[] = [
                'pk' => $order->pk,
                'id' => $order->id,
                'userName' => $user['name'],
                'userId' => $user['id'],
                'createdDate' => $order->created_date,
                'supplierName' => $supplier['name'],
                'status' => $order->is_opened,
                'isMutable' => OrderController::is_mutable($order->pk),
                'isSwitchable' => OrderController::is_switchable($order->pk),
            ];
        }
        return response()->json(['orders' => $response], 201);
    }

    public function get_ordered_items(Request $request)
    {
        $ordered_items = app('db')->table('ordered_items')->where($request->all())->get();
        $response = array();
        foreach ($ordered_items as $ordered_item) {
            $accessory = AccessoryController::info($ordered_item->accessory_pk);
            $response[] = [
                'pk' => $ordered_item->pk,
                'accessoryId' => $accessory['id'],
                'accessoryName' => $accessory['name'],
                'accessoryItem' => $accessory['item'],
                'accessoryArt' => $accessory['art'],
                'accessoryColor' => $accessory['color'],
                'accessorySize' => $accessory['size'],
                'accessoryUnit' => $accessory['unit'],
                'comment' => $ordered_item->comment,
                'orderedQuantity' => $ordered_item->ordered_quantity,
                'sumImportedQuantity' => OrderController::sum_imported_quantity($ordered_item->pk),
                'sumActualQuantity' => OrderController::sum_actual_quantity($ordered_item->pk),
                'orderPk' => $request['order_pk'],
            ];
        }
        return response()->json(['ordered_items' => $response], 201);
    }

    public function get_partners(Request $request)
    {
        $customers = app('db')->table('customers')->where($request->all())->get();
        $suppliers = app('db')->table('suppliers')->where($request->all())->get();
        $partners = array();
        foreach ($customers as $customer) {
            $partners[] = [
                'kind' => 'Khách hàng',
                'name' => $customer->name,
                'id' => $customer->id,
                'address' => $customer->address,
                'phone' => $customer->phone,
                'isActive' => $customer->is_active,
                'pk' => $customer->pk,
            ];
        }
        foreach ($suppliers as $supplier) {
            $partners[] = [
                'kind' => 'Nhà cung cấp',
                'name' => $supplier->name,
                'id' => $supplier->id,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'isActive' => $supplier->is_active,
                'pk' => $supplier->pk,
            ];
        }
        return response()->json(['partners' => $partners], 201);
    }

    public function get_histories(Request $request)
    {
        $temp = app('db')->table('activity_logs')->where($request->all())->get();
        $histories = array();
        foreach ($temp as $item) {
            $user = UserController::info($item->user_pk);
            $histories[] = [
                'type' => $this::translate_type($item->type),
                'object' => $this::translate_object($item->object),
                'createdDate' => $item->created_date,
                'userName' => $user['name'],
                'userId' => $user['id'],
                'id' => $item->id,
            ];
        }
        return response()->json(['histories' => $histories], 201);
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

    public function get_receiving(Request $request)
    {
        $response = $this->receiving->get($request);
        return response()->json(['receivings' => $response], 201);
    }


}

