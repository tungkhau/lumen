<?php

namespace App\Http\Controllers;

use App\Interfaces\ImportInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ImportController extends Controller
{

    private $import;

    public function __construct(ImportInterface $import)
    {
        $this->import = $import;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'order_pk' => 'required|uuid|exits:orders,pk,is_opened,' . True,
                'user_pk' => 'required|uuid|exits:users,pk',
                'imported_items.*.ordered_item_pk' => 'required|uuid|exists:ordered_items,pk',
                'imported_items.*.imported_quantity' => 'required|integer|between:1,2000000000',
                'imported_items.*.comment' => 'nullable|string|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $unique = false;
        $ordered_item_pks = array();
        foreach ($valid_request['ordered_item'] as $ordered_item) {
            $ordered_item_pks[] = $ordered_item['ordered_item_pk'];
        }
        $ordered_item_order_pks = app('db')->table('ordered_items')->whereIn('pk', $ordered_item_pks)->distinct('order_pk')->pluck('order_pk')->toArray();
        if (count($ordered_item_order_pks) == 1) if ($ordered_item_order_pks[0] == $valid_request['order_pk']) return $unique = True;

        $failed = !$unique;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['id'] = $this->id($valid_request['order_pk']);
        try {
            $this->import->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo phiếu nhập thành công'], 201);
    }

    public function edit(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'import_pk' => 'required|uuid|exits:imports,pk',
                'user_pk' => 'required|uuid|exits:users,pk',
                'imported_item_pk' => 'required|uuid|exists:imported_items,pk',
                'imported_quantity' => 'required|integer|between:1,2000000000',
                'comment' => 'nullable|string|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $valid_request['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $valid_request['import_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $unique = app('db')->table('imported_items')->where('pk', $valid_request['imported_item_pk'])->value('import_pk') == $valid_request['import_pk'] ? True : False;

        $failed = $received_groups || !$owner || !$unique;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->edit($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa phiếu nhập thành công'], 200);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'import_pk' => 'required|uuid|exits:imports,pk',
                'user_pk' => 'required|uuid|exits:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $valid_request['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $valid_request['import_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;

        $failed = $received_groups || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->delete($valid_request['import_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phiếu nhập thành công'], 200);
    }

    public function turn_off(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'import_pk' => 'required|uuid|exits:imports,pk,is_opened,' . True,
                'user_pk' => 'required|uuid|exits:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $valid_request['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $valid_request['import_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $failed = !$received_groups || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->turn_off($valid_request['import_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đóng phiếu nhập thành công'], 200);
    }

    public function turn_on(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'import_pk' => 'required|uuid|exits:imports,pk,is_opened,' . False,
                'user_pk' => 'required|uuid|exits:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $valid_request['import_pk'])->pluck('pk')->toArray();
        $checked_or_counted = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->where([['counting_session_pk', '!=', Null], ['checking_session_pk', '!=', Null]])->exists();
        $owner = app('db')->table('imports')->where('pk', $valid_request['import_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        $classified = app('db')->table('imported_items')->where([['import_pk', $valid_request['import_pk']], ['classified_item_pk', '!=', Null]])->exists();

        $failed = !$owner || $classified || $checked_or_counted;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->turn_on($valid_request['import_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Mở phiếu nhập thành công'], 200);
    }

    public function receive(Request $request)
    {

    }

    public function edit_receiving(Request $request)
    {

    }

    public function delete_receiving(Request $request)
    {

    }

    private function id($order_pk)
    {
        $order_id = app('db')->table('orders')->where('order_pk', $order_pk)->value('id');
        $latest_import = app('db')->table('imports')->where('order_pk', $order_pk)->orderBy('id', 'desc')->first();
        if ($latest_import) {
            $num = (int)substr($latest_import->id, -2, 2);
            $num++;
            $num = '0' . $num;
            return $order_id . '#' . substr($num, -2, 2);
        }
        return $order_id . '#01';
    }
}
