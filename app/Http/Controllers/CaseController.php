<?php

namespace App\Http\Controllers;

use App\Interfaces\CaseInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CaseController extends Controller
{

    private $case;

    public function __construct(CaseInterface $case)
    {
        $this->case = $case;
    }

    public function create()
    {
        try {
            $id = $this->id();
            $this->case->create($id);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo đơn vị chứa thành công'], 201);
    }

    public function disable(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'case_pk' => 'required|uuid|exists:cases,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        //TODO check performance
        $received_groups = app('db')->table('received_groups')->where('case_pk', $valid_request['case_pk'])->exists();
        $issued_groups = app('db')->table('issued_groups')->where('case_pk', $valid_request['case_pk'])->exists();
        $entries = app('db')->table('entries')->where('case_pk', $valid_request['case_pk'])->select('quantity', 'is_pending');
        $stored = 0;
        foreach ($entries as $entry) {
            $stored += $entry->quantity;
            if ($entry->is_pending) {
                $stored = true;
                break;
            }
        }
        $failed = $received_groups || $issued_groups || $stored;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->disable($valid_request['case_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa đơn vị chứa thành công'], 200);
    }

    public function store(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'case_pk' => 'required|uuid|exists:cases,pk|unstored_case',
                'shelf_pk' => 'required|uuid|exits:shelves,pk',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $received_groups = app('db')->table('received_groups')->where('case_pk', $valid_request['case_pk'])->get();
        $valid_request['count'] = count($received_groups);
        $passed = True;
        if ($valid_request['count']) {
            foreach ($received_groups as $received_group) {
                if ($received_group->kind == 'imported') {
                    if (app('db')->table('imported_items')->join('classified_items', 'imported_items.classified_item_pk', '=', 'classified_items.pk')->where('imported_items.pk', $received_group->pk)->value('classified_items.quality_state') == 'passed' ? True : False) {
                        $passed = False;
                        break;
                    }
                }
            }
        }
        $failed = !$passed;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        if ($valid_request['count']) {
            $valid_request['storing_session_pk'] = (string)Str::uuid();
            $valid_request['received_group_pks'] = array();
            foreach ($received_groups as $received_group) {
                $valid_request['entries']['kind'] = $received_group->kind;
                $valid_request['entries']['received_item_pk'] = $received_group->received_item_pk;
                $valid_request['entries']['entry_kind'] = 'storing';
                $valid_request['entries']['quantity'] = $received_group->grouped_quantity;
                $valid_request['entries']['session_pk'] = $valid_request['storing_session_pk'];
                $valid_request['entries']['case_pk'] = $valid_request['case_pk'];

                array_push($valid_request['received_group_pks'], $received_group->pk);
            }
        }
        try {
            $this->case->store($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Lưu kho đơn vị chứa thành công'], 200);
    }

    private function id()
    {
        $date = (string)date('dmy');
        $date_string = "%" . $date . "%";
        $latest_case = app('db')->table('cases')->where('id', 'like', $date_string)->latest()->first();
        if ($latest_case) {
            $key = substr($latest_case->id, -2, 2);
            $key++;
        } else $key = "AA";
        return (string)env('DEFAULT_SITE') . "-" . $date . "-" . $key;
    }
}
