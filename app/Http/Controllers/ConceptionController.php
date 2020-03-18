<?php

namespace App\Http\Controllers;

use App\Interfaces\ConceptionInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConceptionController extends Controller
{

    private $conception;

    public function __construct(ConceptionInterface $conception)
    {
        $this->conception = $conception;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'customer_pk' => 'required|uuid|exists:customers,pk',
                'id' => 'required|string|regex:/^[0-9]+$/|max:12',
                'year' => 'required|digits:4|integer|between:2015,' . (date('Y') + 1),
                'conception_name' => 'required|string|max:20',
                'comment' => 'string|nullable|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_active = app('db')->table('customers')->where('pk', $valid_request['customer_pk'])->value('is_active');
        $is_existed = app('db')->table('conceptions')
            ->where('customer_pk', $valid_request['customer_pk'])
            ->where('id', $valid_request['id'])
            ->where('year', $valid_request['year'])->exists();

        if (!$is_active || $is_existed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo mã hàng thành công'], 201);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_existed = app('db')->table('demands')->where('conception_pk', $valid_request['conception_pk'])->exists()
            || app('db')->table('accessories_conceptions')->where('conception_pk', $valid_request['conception_pk'])->exists();
        if ($is_existed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->delete($valid_request['conception_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa mã hàng thành công'], 200);
    }

    public function deactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_active = app('db')->table('conceptions')->where('pk', $valid_request['conception_pk'])->value('is_active');
        if ($is_active == False) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->deactivate($valid_request['conception_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Ẩn mã hàng thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $is_active = app('db')->table('conceptions')->where('pk', $valid_request['conception_pk'])->value('is_active');
        if ($is_active == True) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->reactivate($valid_request['conception_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Hiện mã hàng thành công'], 200);
    }

    public function link_accessory(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
                'conception_pk' => 'required|uuid|exists:conceptions,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $accessory = app('db')->table('accessories')->where('pk', $valid_request['accessory_pk'])->select('customer_pk', 'is_active')->get();
        $conception = app('db')->table('conceptions')->where('pk', $valid_request['conception_pk'])->select('customer_pk', 'is_active')->get();
        $equal = ($accessory->customer_pk == $conception->customer_pk) ? True : False;
        $is_active = $accessory->is_active && $conception->is_active;
        if (!($equal && $is_active)) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->link_accessory($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Liên kết phụ liệu và mã hàng thành công'], 200);
    }

    public function unlink_accessory(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
                'conception_pk' => 'required|uuid|exists:conceptions,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $demand_pks = app('db')->table('demands')->where('conception_pk', $valid_request['conception_pk'])->pluck('pk')->toArray();
        $failed = False;
        foreach ($demand_pks as $demand_pk) {
            if (app('db')->table('demanded_items')->where('demand_pk', $demand_pk)->where('accessory_pk', $valid_request['accessory_pk'])->exists()) {
                $failed = True;
                break;
            }
        }
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->conception->unlink_accessory($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Hủy liên kết phụ liệu và mã hàng thành công'], 200);
    }

}
