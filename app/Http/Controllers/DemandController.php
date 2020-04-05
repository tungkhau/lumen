<?php

namespace App\Http\Controllers;

use App\Preconditions\DemandPrecondition;
use App\Repositories\DemandRepository;
use App\Validators\DemandValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DemandController extends Controller
{
    private $repository;
    private $validator;
    private $precondition;


    public function __construct(DemandValidator $validator, DemandRepository $repository, DemandPrecondition $precondition)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function create(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->create($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->create($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['demand_pk'] = (string)Str::uuid();
        $request['id'] = $this->id($request['conception_pk']);
        $temp = array();
        foreach ($request['demanded_items'] as $demanded_item) {
            $temp[] = [
                'accessory_pk' => $demanded_item['accessory_pk'],
                'demanded_quantity' => $demanded_item['demanded_quantity'],
                'comment' => $demanded_item['comment'],
                'demand_pk' => $request['demand_pk']
            ];
        }
        $request['demanded_items'] = $temp;

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Tạo đơn cấp phát thành công'], 200);
    }

    private function id($conception_pk)
    {
        $conception_id = app('db')->table('conceptions')->where('pk', $conception_pk)->value('id');
        $latest_demand = app('db')->table('demands')->where('id', 'like', $conception_id)->orderBy('created_date', 'desc')->first();
        if ($latest_demand) {
            $key = substr($latest_demand->id, -1, 1);
            $key++;
        } else $key = "A";
        return (string)"DN" . "-" . $conception_id . "-" . $key;
    }

    public function edit(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Sửa đơn cấp phát thành công'], 200);
    }

    public function delete(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Xóa đơn cấp phát thành công'], 200);
    }

    public function turn_off(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_off($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_off($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_off($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đóng đơn cấp phát thành công'], 200);
    }

    public function turn_on(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_on($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_on($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_on($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Mở đơn cấp phát thành công'], 200);
    }

//    public function issue(Request $request)
//    {
//        /* Validate request, catch invalid errors(400) */
//        $validation = $this->validator->issue($request);
//        if ($validation) return $this->invalid_response($validation);
//
//        /* Check preconditions, return conflict errors(409) */
//
//        //Transfer to collections
//        $issued_groups = collect($request['issued_groups']);
//        $inCased_items = collect($request['inCased_items']);
//        //Mapping
//        $issued_groups = $issued_groups->mapToGroups(function ($item, $key) {
//            return [$item['received_item_pk'] => $item['grouped_quantity']];
//        });
//        $inCased_items =  $inCased_items->mapToGroups(function ($item, $key) {
//            return [$item['received_item_pk'] => $item['issued_quantity']];
//        });
//    }

    public function confirm_issuing(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->confirm_issuing($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->confirm_issuing($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['progressing_session_pk'] = (string)Str::uuid();
        $request['issued_group_pks'] = app('db')->table('issued_groups')->where('issuing_session_pk', $request['consuming_session_pk'])->pluck('pk');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->confirm_issuing($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Nhận phụ liệu thành công'], 200);
    }

    public function return_issuing(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->return_issuing($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->return_issuing($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['returning_session_pk'] = (string)Str::uuid();
        $request['issued_group_pks'] = app('db')->table('issued_groups')->where('issuing_session_pk', $request['consuming_session_pk'])->pluck('pk');
        $request['issued_item_pks'] = app('db')->table('issued_items')->where('issuing_session_pk', $request['consuming_session_pk'])->pluck('pk');
        $issued_groups = app('db')->table('issued_groups')->where('issuing_session_pk', $request['consuming_session_pk'])->get();
        $entries = array();

        foreach ($issued_groups as $issued_group) {
            $entries[] = ['kind' => $issued_group->kind,
                'received_item_pk' => $issued_group->received_item_pk,
                'entry_kind' => 'returning',
                'quantity' => $issued_group->grouped_quantity,
                'session_pk' => $request['returning_session_pk'],
                'case_pk' => $issued_group->case_pk,
                'accessory_pk' => ReceivedGroupController::accessory_pk($issued_group->received_item_pk)
            ];
        }
        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->return_issuing($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hủy xuất phụ liệu thành công'], 200);
    }


    private function accessory_pk($received_item_pk)
    {
        $kind = app('received_groups')->where('received_item_pk', $received_item_pk)->distinct('kind')->select('kind')->first()->kind;
        switch ($kind) {
            case 'imported' :
            {
                $accessory_pk = app('db')->table('imported_items')->where('pk', $received_item_pk)
                    ->join('ordered_items', 'imported_items.ordered_item_pk', '=', 'ordered_items.pk')->value('accessory_pk');
                break;
            }
            case 'restored' :
            {
                $accessory_pk = app('db')->table('restored_items')->where('pk', $received_item_pk)->value('accessory_pk');
                break;
            }
            default :
            {
                $accessory_pk = app('db')->table('collected_items')->where('pk', $received_item_pk)
                    ->join('in_distributed_items', 'collected_items.in_distributed_item_pk', '=', 'in_distributed_items.pk')->value('accessory_pk');
                break;
            }
        }
        return $accessory_pk;
    }
}
