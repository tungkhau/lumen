<?php

namespace App\Http\Controllers;

use App\Repositories\EntryRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EntryController extends Controller
{

    private $entry;

    public function __construct(EntryRepository $entry)
    {
        $this->entry = $entry;
    }

    public function ajdust(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'case_pk' => 'required|uuid|exists:entries, case_pk|stored_case',
                'received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'adjusted_quantity' => 'adjusted_quantity:{$request["received_item_pk"]},{$request["cases_pk"]}',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['adjusting_session_pk'] = (string)Str::uuid();
        $kind = app('db')->table('entries')->where('received_item_pk', $valid_request['received_item_pk'])->distinct('kind')->value('kind');
        $valid_request['entry']['kind'] = $kind;
        $valid_request['entry']['received_item_pk'] = $valid_request['received_item_pk'];
        $valid_request['entry']['entry_kind'] = 'adjusting';
        $valid_request['entry']['quantity'] = $valid_request['adjusted_quantity'];
        $valid_request['entry']['session_pk'] = $valid_request['adjusting_session_pk'];
        $valid_request['entry']['case_pk'] = $valid_request['case_pk'];
        $valid_request['entry']['is_pending'] = True;
        $valid_request['entry']['result'] = False;
        try {
            $this->entry->adjust($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đăng kí hiệu chỉnh thành công'], 200);
    }

    public function discard(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'case_pk' => 'required|uuid|exists:entries, case_pk|stored_case',
                'received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'discarded_quantity' => 'available_quantity:{$request["received_item_pk"]},{$request["cases_pk"]}',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['discarding_session_pk'] = (string)Str::uuid();
        $kind = app('db')->table('entries')->where('received_item_pk', $valid_request['received_item_pk'])->distinct('kind')->value('kind');
        $valid_request['entry']['kind'] = $kind;
        $valid_request['entry']['received_item_pk'] = $valid_request['received_item_pk'];
        $valid_request['entry']['entry_kind'] = 'discarding';
        $valid_request['entry']['quantity'] = $valid_request['discarded_quantity'];
        $valid_request['entry']['session_pk'] = $valid_request['discarding_session_pk'];
        $valid_request['entry']['case_pk'] = $valid_request['case_pk'];
        $valid_request['entry']['is_pending'] = True;
        $valid_request['entry']['result'] = False;
        try {
            $this->entry->discard($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đăng kí loại bỏ thành công'], 200);
    }

    public function verify_adjusting(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'adjusting_session_pk' => 'required|uuid|exits:adjusting_sessions,pk,verifying_session_pk,' . Null,
                'result' => 'required|boolean',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['verifying_session_pk'] = (string)Str::uuid();
        try {
            $this->entry->verify_adjusting($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xác thực phiên hiệu chỉnh thành công'], 200);
    }

    public function verify_discarding(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'discarding_session_pk' => 'required|uuid|exits:discarding_sessions,pk,verifying_session_pk,' . Null,
                'result' => 'required|boolean',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['verifying_session_pk'] = (string)Str::uuid();
        try {
            $this->entry->verify_discarding($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xác thực phiên loại bỏ thành công'], 200);
    }

    public function move(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'start_case_pk' => 'required|uuid|exists:entries, case_pk|stored_case',
                'inCased_items.*.received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'inCased_items.*.quantity' => 'available_quantity:{$request["inCased_items.*.received_item_pk"]},{$request["start_cases_pk"]}',
                'end_case_pk' => 'required|uuid|exists:entries, case_pk|stored_case|different:' . $request['start_case_pk'],
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        $valid_request['moving_session_pk'] = (string)Str::uuid();
        try {
            $this->entry->move($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Chuyển phụ liệu tồn thành công'], 200);
    }

}
