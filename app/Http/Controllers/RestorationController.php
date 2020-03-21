<?php

namespace App\Http\Controllers;

use App\Interfaces\RestorationInterface;
use App\Rules\UnstoredCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RestorationController extends Controller
{

    private $restoration;

    public function __construct(RestorationInterface $restoration)
    {
        $this->restoration = $restoration;
    }

    public function register(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exits:users,pk',
                'comment' => 'nullable|string|max:20',
                'restored_items.*.accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
                'restored_items.*.restored_quantity' => 'required|integer|between:1,2000000000'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $mediator = app('db')->table('users')->where('pk', $valid_request['user_pk'])->value('role') == 'mediator' ? True : False;
        $failed = !$mediator;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['id'] = $this->id();
        $valid_request['restoration_pk'] = (string)Str::uuid();
        foreach ($valid_request['restored_items'] as $key => $value) {
            $valid_request['restored_items'][$key]['restoration_pk'] = $valid_request['restoration_pk'];
        }
        try {
            $this->restoration->register($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đăng kí phiếu trả thành công'], 200);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . False
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->restoration->delete($valid_request['restoration_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phiếu trả thành công'], 200);
    }

    public function confirm(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exits:users,pk',
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . False
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $owner = app('db')->table('restorations')->where('pk', $valid_request['restoration_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->restoration->confirm($valid_request['restoration_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xác nhận phiêu trả thành công'], 200);
    }

    public function cancel(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exits:users,pk',
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $owner = app('db')->table('restorations')->where('pk', $valid_request['restoration_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->restoration->cancel($valid_request['restoration_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Hủy phiêu trả thành công'], 200);
    }

    public function receive(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . True,
                'user_pk' => 'required|uuid|exits:users,pk',
                'restored_groups.*.restored_item_pk' => 'required|uuid|exists:restored_items,pk',
                'restored_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000',
                'restored_groups.*.case_pk' => ['required', 'uuid', 'exists:cases,pk', new UnstoredCase]
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        //TODO implement precondition + prepare used_cases_pks
//        $equal = True;
//        $sum = array_reduce($valid_request['restored_groups'], function ($a, $b) {
//            isset($a[$b['restored_item_pk']]) ? $a[$b['restored_item_pk']]['grouped_quantity'] += $b['grouped_quantity'] : $a[$b['restored_item_pk']] = $b;
//            return $a;
//        });
//        $temp = app('db')->table('restored_item')->where('restoration_pk', $valid_request['restoration_pk'])->select('restored_quantity', 'accessory_pk')->get();

//        if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['restoring_session_pk'] = (string)Str::uuid();
        try {
            $this->restoration->receive($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Ghi nhận phiếu trả thành công'], 200);
    }

    private function id()
    {
        $date = (string)date('dmy');
        $date_string = "%" . $date . "%";
        $latest_restoration = app('db')->table('restorations')->where('id', 'like', $date_string)->orderBy('id', 'desc')->first();
        if ($latest_restoration) {
            $key = substr($latest_restoration->id, -1, 1);
            $key++;
        } else $key = "A";
        return (string)'RN' . '-' . $date . '-' . $key;
    }
}
