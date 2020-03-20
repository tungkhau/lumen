<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
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
                'ordered_items.*.ordered_quantity' => 'required|integer|between:1,2000000000',
                'ordered_items.*.comment' => 'nullable|string|max:20',
                'user_pk' => 'required|uuid|exists:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }
        //Check preconditions, return conflict errors(409)
        $accessory_pks = array();
        foreach ($valid_request['ordered_items'] as $ordered_item) {
            array_push($accessory_pks, $ordered_item['accessory_pk']);
        }
        $suppliers_count = app('db')->table('accessories')->whereIn('pk', $accessory_pks)->distinct('supplier_pk')->count('suppler_pk');
        $failed = $suppliers_count == 1 ? False : True;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->create($valid_request);
        } catch (Exception $e) {
            return response()->json([$e->getMessage()], 500);
        }
        return response()->json(['success' => 'Tạo Đơn đặt thành công'], 201);
    }

    public function edit(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'order_pk' => 'required|uuid|exits:orders,pk',
                'ordered_item_pk' => 'required|uuid|exists:ordered_items,pk',
                'ordered_quantity' => 'required|integer|between:1,2000000000',
                'comment' => 'nullable|string|max:20',
                'user_pk' => 'required|uuid|exists:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $ordered_item_order_pk = app('db')->table('ordered_items')->where('pk', $valid_request['ordered_item_pk'])->value('order_pk');
        $unique = $ordered_item_order_pk == $valid_request['order_pk'] ? True : False;
        $imports = app('db')->table('imports')->where('order_pk', $valid_request['order_pk'])->exists();
        $owner = app('db')->table('orders')->where('pk', $valid_request['order_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;

        $failed = !$unique || $imports || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->edit($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa đơn đặt thành công'], 200);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'order_pk' => 'required|uuid|exits:orders,pk',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imports = app('db')->table('imports')->where('order_pk', $valid_request['order_pk'])->exists();
        $owner = app('db')->table('orders')->where('pk', $valid_request['order_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = $imports || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->delete($valid_request['order_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa đơn đặt thành công'], 200);
    }

    public function turn_off(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'required|uuid|exits:orders,pk,is_opened,' . True,
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imports = app('db')->table('imports')->where('order_pk', $valid_request['order_pk'])->exists();
        $owner = app('db')->table('orders')->where('pk', $valid_request['order_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = !$imports || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->turn_off($valid_request['order_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đóng đơn đặt thành công'], 200);
    }

    public function turn_on(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'required|uuid|exits:orders,pk,is_opened,' . False,
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $owner = app('db')->table('orders')->where('pk', $valid_request['order_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->order->turn_on($valid_request['order_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Mở đơn đặt thành công'], 200);
    }
}
