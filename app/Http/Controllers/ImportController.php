<?php

namespace App\Http\Controllers;

use App\Interfaces\ImportInterface;
use App\Rules\UnstoredCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        foreach ($valid_request['ordered_items'] as $ordered_item) {
            $ordered_item_pks[] = $ordered_item['ordered_item_pk'];
        }
        $ordered_item_order_pks = app('db')->table('ordered_items')->whereIn('pk', $ordered_item_pks)->distinct('order_pk')->pluck('order_pk')->toArray();
        if (count($ordered_item_order_pks) == 1) if ($ordered_item_order_pks[0] == $valid_request['order_pk']) return $unique = True;

        $failed = !$unique;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['id'] = $this->id($valid_request['order_pk']);
        $valid_request['import_pk'] = (string)Str::uuid();
        foreach ($valid_request['imported_items'] as $key => $value) {
            $valid_request['imported_items'][$key]['import_pk'] = $valid_request['import_pk'];
        }
        try {
            $this->import->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo phiếu nhập thành công'], 201);
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
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'import_pk' => 'required|uuid|exits:imports,pk,is_opened,' . True,
                'case_pk' => ['required', 'uuid', 'exists:cases,pk', new UnstoredCase],
                'user_pk' => 'required|uuid|exits:users,pk',
                'imported_groups.*.imported_item_pk' => 'required|uuid|exists:imported_items,pk',
                'imported_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $unique = false;
        $imported_item_pks = array();
        foreach ($valid_request['imported_groups'] as $imported_group) {
            $imported_item_pks[] = $imported_group['imported_item_pk'];
        }
        $imported_item_import_pks = app('db')->table('imported_items')->whereIn('pk', $imported_item_pks)->distinct('import_pk')->pluck('import_pk')->toArray();
        if (count($imported_item_import_pks) == 1) if ($imported_item_import_pks[0] == $valid_request['import_pk']) return $unique = True;

        $failed = !$unique;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['receiving_session_pk'] = (string)Str::uuid();
        try {
            $this->import->receive($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Ghi nhận phiếu nhập thành công'], 200);
    }

    public function edit_receiving(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'importing_session_pk' => 'required|uuid|exists:receiving_sessions,pk,kind,' . 'importing',
                'user_pk' => 'required|uuid|exits:users,pk',
                'imported_groups.*.imported_group_pk' => 'required|uuid|exists:received_groups,pk,kind,' . 'imported',
                'imported_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        //If all imported groups belong to given importing session
        $unique = False;
        $imported_group_pks = array();
        foreach ($valid_request['imported_groups'] as $imported_group) {
            $imported_group_pks[] = $imported_group['imported_group_pk'];
        }
        $imported_group_importing_session_pks = app('db')->table('received_groups')->whereIn('pk', $imported_group_pks)->distinct('receiving_session_pk')->pluck('receiving_session_pk')->toArray();
        if (count($imported_group_importing_session_pks) == 1) if ($imported_group_importing_session_pks[0] == $valid_request['importing_session_pk']) return $unique = True;
        //If current user is its owner
        $owner = app('db')->table('receiving_sessions')->where('pk', $valid_request['importing_session_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;
        //If all imported groups belong to an opened import
        $opened = false;
        $imported_item_pks = app('db')->table('received_groups')->where('receiving_session_pk', $valid_request['importing_session_pk'])->distinct('received_item_pk')->pluck('received_item_pk')->toArray();
        $import_pks = app('db')->table('imported_items')->whereIn('pk', $imported_item_pks)->distinct('import_pk')->pluck('import_pk')->toArray(); //Expect only one import
        if (count($import_pks) == 1) $opened = app('db')->table('imports')->where('pk', $import_pks[0])->value('is_opened');

        $failed = !$owner || !$unique || !$opened;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->receive($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa ghi nhận phiếu nhập thành công'], 200);
    }

    public function delete_receiving(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'importing_session_pk' => 'required|uuid|exists:receiving_sessions,pk,kind,' . 'importing',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        //If all imported groups belong to an opened import
        $opened = false;
        $imported_item_pks = app('db')->table('received_groups')->where('receiving_session_pk', $valid_request['importing_session_pk'])->distinct('received_item_pk')->pluck('received_item_pk')->toArray();
        $import_pks = app('db')->table('imported_items')->whereIn('pk', $imported_item_pks)->distinct('import_pk')->pluck('import_pk')->toArray(); //Expect only one import
        if (count($import_pks) == 1) $opened = app('db')->table('imports')->where('pk', $import_pks[0])->value('is_opened');
        //If current user is its owner
        $owner = app('db')->table('receiving_sessions')->where('pk', $valid_request['importing_session_pk'])->value('user_pk') == $valid_request['user_pk'] ? True : False;

        $failed = !$opened || !$owner;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->delete_receiving($valid_request['importing_session_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa ghi nhận phiếu nhập thành công'], 200);
    }

    public function classify(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'imported_item_pk' => 'required|uuid|exits:imported_items,pk',
                'quality_state' => 'required|in:passed,pending,failed',
                'comment' => 'string|nullable|max:20',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
//        if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['classified_item_pk'] = (string)Str::uuid();
        try {
            $this->import->classify($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đánh giá phụ liệu nhập thành công'], 200);
    }

    public function reclassify(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'classified_item_pk' => 'required|uuid|exits:classified_items,pk',
                'quality_state' => 'required|in:passed,pending,failed',
                'comment' => 'string|nullable|max:20',
                'user_pk' => 'required|uuid|exits:users,pk'

            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
//        if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->reclassify($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa phiên đánh giá thành công'], 200);
    }

    public function delete_classification(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'classified_item_pk' => 'required|uuid|exits:classified_items,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
//        if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->import->delete_classification($valid_request['classified_item_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phiên đánh giá thành công'], 200);
    }

    public function sendback(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'classified_item_pk' => 'required|uuid|exits:classified_items,pk,quality_state,' . 'failed',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
//        if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $imported_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $valid_request['classified_item_pk'])->value('pk');
        $valid_request['received_group_pks'] = app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->pluck('pk')->toArray();
        $valid_request['sendbacking_session_pk'] = (string)Str::uuid();
        try {
            $this->import->sendback($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Trả phụ liệu không đạt thành công'], 200);
    }

    public static function checking_info($imported_item_pk)
    {
        $total_quantity = app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->sum('grouped_quantity');
        switch (True) {
            case ($total_quantity < 151):
            {
                $checking_info['sample'] = 20;
                $checking_info['acceptance'] = 0;
                return $checking_info;
            }
            case ($total_quantity >= 151 && $total_quantity <= 280):
            {
                $checking_info['sample'] = 32;
                $checking_info['acceptance'] = 0;
                return $checking_info;
            }
            case ($total_quantity >= 281 && $total_quantity <= 500):
            {
                $checking_info['sample'] = 50;
                $checking_info['acceptance'] = 1;
                return $checking_info;
            }
            case ($total_quantity >= 501 && $total_quantity <= 1200):
            {
                $checking_info['sample'] = 80;
                $checking_info['acceptance'] = 1;
                return $checking_info;
            }
            case ($total_quantity >= 1201 && $total_quantity <= 3200):
            {
                $checking_info['sample'] = 125;
                $checking_info['acceptance'] = 2;
                return $checking_info;
            }
            case ($total_quantity >= 3201 && $total_quantity <= 10000):
            {
                $checking_info['sample'] = 200;
                $checking_info['acceptance'] = 3;
                return $checking_info;
            }
            case ($total_quantity >= 10001 && $total_quantity <= 35000):
            {
                $checking_info['sample'] = 315;
                $checking_info['acceptance'] = 5;
                return $checking_info;
            }
            case ($total_quantity >= 35001 && $total_quantity <= 150000):
            {
                $checking_info['sample'] = 500;
                $checking_info['acceptance'] = 7;
                return $checking_info;
            }
            case ($total_quantity >= 150001 && $total_quantity <= 500000):
            {
                $checking_info['sample'] = 800;
                $checking_info['acceptance'] = 10;
                return $checking_info;
            }
            case ($total_quantity >= 500000):
            {
                $checking_info['sample'] = 1250;
                $checking_info['acceptance'] = 14;
                return $checking_info;
            }
        }
    }
}
