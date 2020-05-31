<?php

namespace App\ViewModels;

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\ReceivedGroupController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Crypt;
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
        $mediators = app('db')->table('users')->where('role', 'mediator')->where('is_active', true)->get();
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

    public function get_received_workplace()
    {
        $received_workplaces = app('db')->table('workplaces')->where([['name', '!=', 'Kho phụ liệu'], ['name', '!=', 'Văn phòng']])->get();
        $object = array();
        foreach ($received_workplaces as $received_workplace) {
            $user = app('db')->table('users')->where('workplace_pk', $received_workplace->pk)->exists();
            $object[] = [
                'pk' => $received_workplace->pk,
                'name' => $received_workplace->name,
                'isMutable' => !$user
            ];
        }
        return $object;
    }

    public function get_workplace()
    {
        $workplaces = app('db')->table('workplaces')->get();
        $object = array();
        foreach ($workplaces as $workplace) {
            $user = app('db')->table('users')->where('workplace_pk', $workplace->pk)->exists();
            if ($workplace->name == 'Kho phụ liệu' || $workplace->name == 'Văn phòng') $is_mutable = False;
            else $is_mutable = !$user;
            $object[] = [
                'pk' => $workplace->pk,
                'name' => $workplace->name,
                'isMutable' => $is_mutable
            ];
        }
        return $object;
    }

    public function get_scanner($params)
    {
        $externality = $params['externality'];
        $tmp = $externality['pk'][0];
        $kind = substr($tmp, -1, 1);
        $pk = substr($tmp, 0, 36);
        $object = array();
        switch ($kind) {
            case 1 :
            {
                $case = app('db')->table('cases')->where('pk', $pk)->where('is_active', True)->first();
                if (!$case) {
                    return $object;
                }
                $hasShelf = $case->shelf_pk != Null;
                $issued_groups = app('db')->table('issued_groups')->where('case_pk', $pk)->exists();
                if ($issued_groups) {
                    return $object[] = [
                        'kind' => 'sealedCase',
                        'pk' => $pk,
                        'id' => $case->id
                    ];
                }
                $received_groups = app('db')->table('received_groups')->where('case_pk', $pk)->exists();
                if ($received_groups) {
                    return $object[] = [
                        'kind' => 'unstoredCase',
                        'pk' => $pk,
                        'id' => $case->id
                    ];
                }
                $entries = app('db')->table('entries')->where('case_pk', $pk)->pluck('quantity');
                if (count($entries) == 0) {
                    return $object[] = [
                        'kind' => $hasShelf ? 'emptyInCase' : 'emptyOutCase',
                        'pk' => $pk,
                        'id' => $case->id
                    ];
                }
                $inCased_quantity = 0;
                foreach ($entries as $entry) {
                    if ($entry === Null) {
                        return $object[] = [
                            'kind' => 'storedCase',
                            'pk' => $pk,
                            'id' => $case->id
                        ];
                    }
                    $inCased_quantity += $entry;
                }
                if ($inCased_quantity != 0) {
                    return $object[] = [
                        'kind' => 'storedCase',
                        'pk' => $pk,
                        'id' => $case->id
                    ];
                } else {
                    return $object[] = [
                        'kind' => $hasShelf ? 'emptyInCase' : 'emptyOutCase',
                        'pk' => $pk,
                        'id' => $case->id
                    ];
                }
            }
            case 2 :
            {
                $shelf = app('db')->table('shelves')->where('pk', $pk)->first();
                if (!$shelf) {
                    return $object;
                }
                return $object[] = [
                    'kind' => 'shelf',
                    'pk' => $pk,
                    'id' => $shelf->name
                ];
            }
            case 3 :
            {
                $block = app('db')->table('blocks')->where('pk', $pk)->where('is_active', True)->first();
                if (!$block) {
                    return $object;
                }
                return $object[] = [
                    'kind' => 'block',
                    'pk' => $pk,
                    'id' => $block->id
                ];
            }
            case 4 :
            {
                $api = $params->header('api_token');
                $user_pk = Crypt::decrypt($api)['pk'];
                $restoration = app('db')->table('restorations')->where('pk', $pk)->where('user_pk', $user_pk)->first();
                if (!$restoration) {
                    return $object;
                }
                return $object[] = [
                    'kind' => 'restoration',
                    'pk' => $pk,
                    'id' => $restoration->id
                ];
            }
            case 5 :
            {
                $issuing = app('db')->table('issuing_sessions')->where('pk', $pk)->first();
                if (!$issuing) {
                    return $object;
                }
                $api = $params->header('api_token');
                $user_pk = Crypt::decrypt($api)['pk'];
                $user = app('db')->table('users')->where('pk', $user_pk)->select('role', 'workplace_pk')->first();
                if ($user->role == 'mediator') {
                    $workplace_pk = app('db')->table('demands')->where('pk', $issuing->container_pk)->value('workplace_pk');
                    if ($user->workplace_pk != $workplace_pk) {
                        return $object;
                    }
                    return $object[] = [
                        'kind' => 'issuing',
                        'pk' => $pk,
                        'id' => $issuing->id
                    ];
                } elseif ($user->role == 'staff') {
                    return $object[] = [
                        'kind' => 'issuing',
                        'pk' => $pk,
                        'id' => $issuing->id
                    ];
                } else  return $object;
            }
            default :
                return $object;
        }
    }

    public function get_user()
    {
        $users = app('db')->table('users')->where('role', '!=', 'admin')->get();
        $object = array();
        foreach ($users as $user) {
            $wl = app('db')->table('workplaces')->where('pk', $user->workplace_pk)->value('name');
            $object[] = [
                'pk' => $user->pk,
                'name' => $user->name,
                'id' => $user->id,
                'role' => $user->role,
                'workplaceName' => $wl,
                'isActive' => $user->is_active ? 'active' : 'inactive',
                'createdDate' => $user->created_date,
            ];
        }
        return $this::sort_response($object, 'createdDate');
    }
}
