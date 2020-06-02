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

        /*Check limit */
        $request['id'] = $this->demand_id($request['conception_pk']);
        if (!$request['id']) return $this->limited_response();

        /* Map variables */
        $request['demand_pk'] = (string)Str::uuid();

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

    private function demand_id($conception_pk)
    {
        $conception_id = app('db')->table('conceptions')->where('pk', $conception_pk)->value('id');
        $tmp = '%' . $conception_id . '%';
        $latest_demand = app('db')->table('demands')->where('id', 'like', $tmp)->orderBy('created_date', 'desc')->first();
        if ($latest_demand) {
            $key = (int)substr($latest_demand->id, -2, 2);
            if ($key == 99) return False;
            $key++;
            $key = substr("00{$key}", -2);
        } else $key = "01";
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
        if ($unexpected) return $this->unexpected_response();
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

    public function issue(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->issue($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->issue($request);
        if ($precondition) return $this->conflict_response();


        /*Check limit */
        $request['issuing_session_id'] = $this->issuing_id($request['demand_pk']);
        if (!$request['issuing_session_id']) return $this->limited_response();

        /* Map variables */
        $request['issuing_session_pk'] = (string)Str::uuid();
        $issued_groups = $request['issued_groups'];
        $cases = array();
        $tmp1 = array();
        foreach ($issued_groups as $issued_group) {
            $tmp1[] = [
                'accessory_pk' => ReceivedGroupController::accessory_pk($issued_group['received_item_pk']),
                'grouped_quantity' => $issued_group['grouped_quantity']
            ];
            array_push($cases, $issued_group['case_pk']);
        }
        $cases = array_unique($cases);
        $tmp2 = collect($tmp1)->mapToGroups(function ($item, $key) {
            return [$item['accessory_pk'] => $item['grouped_quantity']];
        });
        $tmp3 = array();
        foreach ($tmp2 as $accessory_pk => $grouped_quantities) {
            $tmp3[$accessory_pk] = $grouped_quantities->sum();
        }

        $demanded_items = app('db')->table('demanded_items')->where('demand_pk', $request['demand_pk'])->select('accessory_pk', 'pk')->get();
        $tmp4 = array();
        foreach ($demanded_items as $demanded_item) {
            $tmp4[$demanded_item->accessory_pk] = $demanded_item->pk;
        }
        $tmp5 = array();
        foreach ($issued_groups as $issued_group) {
            $tmp5[] = [
                'received_item_pk' => $issued_group['received_item_pk'],
                'accessory_pk' => ReceivedGroupController::accessory_pk($issued_group['received_item_pk']),
                'case_pk' => $issued_group['case_pk'],
                'grouped_quantity' => $issued_group['grouped_quantity'],
            ];
        }
        //tmp 3 [acc_pk => issued_quantity]
        //tmp4 [acc_pk => demanded_item_pk]
        $issued_items = array();
        $issued_groups = array();
        foreach ($tmp4 as $a => $c) {
            foreach ($tmp3 as $b => $d) {
                if ($a == $b) {
                    $issued_item_pk = (string)Str::uuid();
                    $issued_items[] = [
                        'pk' => $issued_item_pk,
                        'issued_quantity' => $d,
                        'kind' => 'consumed',
                        'end_item_pk' => $c,
                        'issuing_session_pk' => $request['issuing_session_pk'],
                    ];
                    foreach ($tmp5 as $item) {
                        if ($item['accessory_pk'] == $b) {
                            $issued_groups[] = [
                                'received_item_pk' => $item['received_item_pk'],
                                'case_pk' => $item['case_pk'],
                                'grouped_quantity' => $item['grouped_quantity'],
                                'kind' => 'consumed',
                                'issuing_session_pk' => $request['issuing_session_pk'],
                                'issued_item_pk' => $issued_item_pk,
                            ];
                        }
                    }
                }
            }
        }

        $inCase_items = $request['inCased_items'];
        $entries = array();
        foreach ($inCase_items as $item) {
            $inCase_item = EntryController::inCased_item($item['received_item_pk'], $item['case_pk']);
            $entries[] = [
                'received_item_pk' => $item['received_item_pk'],
                'kind' => $inCase_item->kind,
                'entry_kind' => 'issuing',
                'quantity' => -$item['issued_quantity'],
                'session_pk' => $request['issuing_session_pk'],
                'case_pk' => $item['case_pk'],
                'accessory_pk' => $inCase_item->accessory_pk,
            ];
        }
        $request['entries'] = $entries;
        $request['issued_groups'] = $issued_groups;
        $request['issued_items'] = $issued_items;
        $request['cases'] = $cases;



        $unexpected = $this->repository->issue($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xuất phụ liệu thành công'], 200);
    }

    private function issuing_id($demand_pk)
    {
        $latest_issuing = app('db')->table('issuing_sessions')->where('container_pk', $demand_pk)->orderBy('executed_date', 'desc')->first();
        $demand_id = (string)app('db')->table('demands')->where('pk', $demand_pk)->value('id');
        if ($latest_issuing) {
            $num = (int)substr($latest_issuing->id, -2, 2);
            if ($num == 99) return False;
            $num++;
            $num = substr("00{$num}", -2);
        } else $num = "01";
        return (string)$demand_id . "#" . $num;
    }

    public function confirm(Request $request)
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
            $kind = app('db')->table('received_groups')->where('received_item_pk', $issued_group->received_item_pk)->first()->kind;
            $entries[] = ['kind' => $kind,
                'received_item_pk' => $issued_group->received_item_pk,
                'entry_kind' => 'returning',
                'quantity' => $issued_group->grouped_quantity,
                'session_pk' => $request['returning_session_pk'],
                'case_pk' => $issued_group->case_pk,
                'accessory_pk' => ReceivedGroupController::accessory_pk($issued_group->received_item_pk)
            ];
        }
        $request['entries'] = $entries;

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->return_issuing($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hủy xuất phụ liệu thành công'], 200);
    }
}
