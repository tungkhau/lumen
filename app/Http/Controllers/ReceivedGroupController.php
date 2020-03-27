<?php

namespace App\Http\Controllers;

use App\Preconditions\ReceivedGroupPrecondition;
use App\Repositories\ReceivedGroupRepository;
use App\Validators\ReceivedGroupValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReceivedGroupController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(ReceivedGroupPrecondition $precondition, ReceivedGroupRepository $repository, ReceivedGroupValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function count(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'received_group_pk' => 'required|uuid|exits:received_groups,pk',
                'counted_quantity' => 'required|integer|between:1,2000000000',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $received_group = app('db')->table('received_groups')->where('pk', $valid_request['received_group_pk'])->first();
        $opened = False;
        $classified = False;
        $imported = $received_group->kind == 'imported' ? True : False;
        if ($imported) {
            $imported_item_pk = $received_group->received_item_pk;
            $imported_item = app('db')->table('imported_items')->where('pk', $imported_item_pk)->select('import_pk', 'classified_item_pk')->first();
            $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');

            $classified = $imported_item->classified_item_pk ? True : False;
        }
        $counted = $received_group->couting_session_pk ? True : False;
        $stored = $received_group->storing_session_pk ? True : False;

        $failed = $opened || $classified || $counted || $stored;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['counting_session_pk'] = (string)Str::uuid();
        try {
            $this->received_group->count($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Kiểm số lượng thành công'], 200);
    }

    public function edit_counting(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'counting_session_pk' => 'required|uuid|exits:counting_sessions,pk',
                'counted_quantity' => 'required|integer|between:1,2000000000',
                'user_pk' => 'required|uuid|exits:users,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
        // if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->received_group->edit_counting($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa phiên kiểm số lượng thành công'], 200);
    }

    public function delete_counting(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'counting_session_pk' => 'required|uuid|exits:counting_sessions,pk',
                'user_pk' => 'required|uuid|exits:users,pk',
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
            $this->received_group->delete_counting($valid_request['counting_session_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phiên kiểm số lượng thành công'], 200);
    }

    public function check(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'imported_group_pk' => 'required|uuid|exits:received_groups,pk,kind,' . 'imported',
                'checked_quantity' => 'required|integer|gt:0',
                'unqualified_quantity' => 'required|integer|gte:0|lte:' . $request['checked_quantity'],
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
        $valid_request['checking_session_pk'] = (string)Str::uuid();
        try {
            $this->received_group->check($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Kiểm chất lượng thành công'], 200);
    }

    public function edit_checking(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'checking_session_pk' => 'required|uuid|exits:checking_sessions,pk',
                'checked_quantity' => 'required|integer|gt:0',
                'unqualified_quantity' => 'required|integer|gte:0|lte:' . $request['checked_quantity'],
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
            $this->received_group->edit_checking($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sửa phiên kiểm chất lượng thành công'], 200);
    }

    public function delete_checking(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'checking_session_pk' => 'required|uuid|exits:checking_sessions,pk',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
        //if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->received_group->delete_counting($valid_request['checking_session_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa phiên kiểm chất lượng thành công'], 200);
    }

    public function arrange(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'start_case_pk' => 'required|uuid|exists:received_groups,case_pk|unstored_case',
                'end_case_pk' => 'required|uuid|exists:cases,pk|unstored_case|different:' . $request['start_case_pk'],
                'received_groups.*.received_group_pk' => 'required|uuid|exits:received_groups,pk,case_pk,' . $request['start_case_pk'],
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409) TODO implement preconditions
        //if () return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['arranging_session_pk'] = (string)Str::uuid();
        try {
            $this->received_group->arrange($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Sắp xếp cụm phụ liệu thành công'], 200);
    }

    public function store(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->store($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->store($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['storing_session_pk'] = (string)Str::uuid();
        $received_groups = app('db')->table('received_groups')->whereIn('pk', array_values($request['received_groups']))->get()->toArray();
        foreach ($received_groups as $received_group) {
            $request['entries']['received_item_pk'] = $received_group['received_item_pk'];
            $request['entries']['kind'] = $received_group['kind'];
            $request['entries']['quantity'] = $received_group['grouped_quantity'];
            $request['entries']['entry_kind'] = 'storing';
            $request['entries']['session_pk'] = $request['storing_session_pk'];
            $request['entries']['case_pk'] = $request['case_pk'];
            $request['entries']['accessory_pk'] = $this::accessory_pk($received_group['pk']);
        }

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->store($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Lưu kho cụm phụ liệu nhập thành công'], 200);
    }

    public static function accessory_pk($received_group_pk)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->select('received_item_pk', 'kind')->first();
        switch ($received_group->kind) {
            case 'restored':
            {
                return app('db')->table('restored_items')->where('pk', $received_group->received_item_pk)->value('accessory_pk');
            }
            case 'collected':
            {
                $in_distributed_item_pk = app('db')->table('collected_items')->where('pk', $received_group->received_item_pk)->value('in_distributed_item_pk');
                return app('db')->table('in_distributed_items')->where('pk', $in_distributed_item_pk)->value('accessory_pk');
            }
            default:
            {
                $ordered_item_pk = app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('ordered_item_pk');
                return app('db')->table('ordered_items')->where('pk', $ordered_item_pk)->value('accessory_pk');
            }
        }
    }
}
