<?php

namespace App\Http\Controllers;

use App\Interfaces\AccessoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccessoryController extends Controller
{

    private $accessory;

    public function __construct(AccessoryInterface $acccessory)
    {
        $this->accessory = $acccessory;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'customer_pk' => 'required|uuid|exists:customers,pk',
                'supplier_pk' => 'required|uuid|exists:suppliers,pk',
                'type_pk' => 'required|uuid|exists:types,pk',
                'unit_pk' => 'required|uuid|exists:units,pk',
                'item' => 'required|string|max:20',
                'art' => 'string|nullable|max:20',
                'color' => 'string|nullable|max:20',
                'size' => 'string|nullable|max:10',
                'accessory_name' => 'required|string|max:50',
                'comment' => 'string|nullable|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
//        $is_active = app('db')->table('customers')->where('pk', $valid_request['customer_pk'])->value('is_active') &&
//            app('db')->table('suppliers')->where('pk', $valid_request['supplier_pk'])->value('is_active');
//        if (!$is_active) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);
//        $unique = app('db')->table('accessories')->where('customer_pk', $valid_request['customer_pk'])->where('supplier_pk',$valid_request['supplier_pk'])->where('item', $valid_request['item'])->doesntExists();
//        if (!$unique) return response()->json(['conflict' => 'Phụ liệu đã tồn tại'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['id'] = $this->id($valid_request['type_pk'], $valid_request['customer_pk'], $valid_request['item'], $valid_request['supplier_pk']);
        try {
            $this->accessory->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo phụ liệu thành công'], 201);
    }

    private function id($type_pk, $customer_pk, $item, $supplier_pk)
    {
        //TODO implement id generate
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_existed = app('db')->table('accessories_conceptions')->where('accessory_pk', $valid_request['accessory_pk'])->exists()
            || app('db')->table('ordered_items')->where('accessory_pk', $valid_request['accessory_pk'])->exists()
            || app('db')->table('in_distributed_items')->where('accessory_pk', $valid_request['accessory_pk'])->exists()
            || app('db')->table('demanded_items')->where('accessory_pk', $valid_request['accessory_pk'])->exists()
            || app('db')->table('restored_items')->where('accessory_pk', $valid_request['accessory_pk'])->exists()
            || app('db')->table('out_distributed_item')->where('accessory_pk', $valid_request['accessory_pk'])->exists();
        if ($is_existed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->accessory->delete($valid_request['accessory_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phụ liệu thành công'], 200);
    }

    public function deactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_active = app('db')->table('accessories')->where('pk', $valid_request['accessory_pk'])->value('is_active');
        if ($is_active == False) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->accessory->deactivate($valid_request['accessory_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Ẩn phụ liệu thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_active = app('db')->table('accessories')->where('pk', $valid_request['accessory_pk'])->value('is_active');
        if ($is_active == True) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->accessory->reactivate($valid_request['accessory_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Hiện phụ liệu thành công'], 200);
    }

    public function upload_photo(Request $request)
    {
        //TODO implement upload photo
    }

    public function delete_photo(Request $request)
    {
        //TODO implement delete photo
    }
}
