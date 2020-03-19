<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class OrderController extends BaseController
{

    private $order;

    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'supplier_pk' => 'required|uuid|exists:suppliers,pk,is_active,' . True,
                'order_id' => 'required|string|max:25|unique:orders,id',
                'ordered_items.*.accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
                'ordered_items.*.ordered_quantity' => 'valid_quantity',
                'ordered_items.*.comment' => 'nullable|string|max:20',
                'user_pk' => 'required|uuid|exits:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $accessory_pks = $valid_request['ordered_item']['accessory_pk'];
        $suppliers_count = app('db')->table('accessories')->whereIn('accessory_pk', $accessory_pks)->distinct('supplier_pk')->count('suppler_pk');
        $failed = $suppliers_count == 1 ? False : True;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo Đơn đặt thành công'], 201);
    }

    public function edit(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function turn_off(Request $request)
    {

    }

    public function turn_on(Request $request)
    {

    }
}
