<?php

namespace App\Http\Controllers;

use App\Preconditions\OrderPrecondition;
use App\Repositories\OrderRepository;
use App\Validators\OrderValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(OrderRepository $repository, OrderPrecondition $precondition, OrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public static function is_mutable($order_pk)
    {
        $imports = app('db')->table('imports')->where('order_pk', $order_pk)->exists();
        $owner = app('db')->table('orders')->where('pk', $order_pk)->value('user_pk') == $order_pk;
        return !$imports || $owner;
    }

    public static function is_switchable($order_pk)
    {
        $status = app('db')->table('orders')->where('pk', $order_pk)->value('is_opened');
        if ($status) return app('db')->table('imports')->where('order_pk', $order_pk)->exists();
        return True;
    }

    public static function sum_imported_quantity($ordered_item_pk)
    {
        return app('db')->table('imported_items')->where('ordered_item_pk', $ordered_item_pk)->sum('imported_quantity');
    }

    public static function sum_actual_quantity($order_item_pk)
    {
        $temp = app('db')->table('imported_items')->where('ordered_item_pk', $order_item_pk)->pluck('pk');
        $imported_item_pks = array();
        foreach ($temp as $item) {
            if (ImportController::quality_state($item) == 'failed') continue;
            array_push($imported_item_pks, $item);
        }
        $imported_group_pks = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->pluck('pk');
        $sum = 0;
        foreach ($imported_group_pks as $imported_group_pk) {
            $sum += ReceivedGroupController::actual_quantity($imported_group_pk);
        }
        return $sum;
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
        $request['order_pk'] = (string)Str::uuid();
        $temp = array();
        foreach ($request['ordered_items'] as $ordered_item) {
            $temp[] = [
                'accessory_pk' => $ordered_item['accessory_pk'],
                'comment' => $ordered_item['comment'],
                'ordered_quantity' => $ordered_item['ordered_quantity'],
                'order_pk' => $request['order_pk']
            ];
        }

        $request['ordered_items'] = $temp;

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo đơn đặt thành công'], 200);
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
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Sửa đơn đặt thành công'], 200);
    }

    //For Angular app only (doesn't check ownership)

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
        return response()->json(['success' => 'Xóa đơn đặt thành công'], 200);
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
        return response()->json(['success' => 'Đóng đơn đặt thành công'], 200);
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
        return response()->json(['success' => 'Mở đơn đặt thành công'], 200);
    }
}
