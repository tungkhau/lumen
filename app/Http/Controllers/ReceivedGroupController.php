<?php

namespace App\Http\Controllers;

use App\Preconditions\ReceivedGroupPrecondition;
use App\Repositories\ReceivedGroupRepository;
use App\Validators\ReceivedGroupValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public static function actual_quantity($received_group_pk)
    {
        $received_group = app('db')->table('received_groups')->where('pk', $received_group_pk)->first();
        if ($received_group->counting_session_pk == Null) return $received_group->grouped_quantity;
        return app('db')->table('counting_sessions')->where('pk', $received_group->counting_session_pk)->value('counted_quantity');
    }

    public function count(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->count($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->count($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['counting_session_pk'] = (string)Str::uuid();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->count($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Kiểm số lượng thành công'], 200);
    }

    public function edit_counting(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit_counting($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit_counting($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit_counting($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Sửa phiên kiểm số lượng thành công'], 200);
    }

    public function delete_counting(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete_counting($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete_counting($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_counting($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa phiên kiểm số lượng thành công'], 200);
    }

    public function check(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->check($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->check($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['checking_session_pk'] = (string)Str::uuid();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->check($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Kiểm chất lượng thành công'], 200);
    }

    public function edit_checking(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $request['imported_group_pk'] = app('db')->table('received_groups')->where('checking_session_pk', $request['checking_session_pk'])->value('pk');
        $validation = $this->validator->edit_checking($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit_checking($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit_checking($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Sửa phiên kiểm chất lượng thành công'], 200);
    }

    public function delete_checking(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete_checking($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete_checking($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_checking($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa phiên kiểm chất lượng thành công'], 200);
    }

    public function arrange(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->arrange($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->arrange($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['arranging_session_pk'] = (string)Str::uuid();
        $received_group_pks = array();
        $temp = array();
        foreach ($request['received_groups'] as $received_group) {
            $temp[] = [
                'received_group_pk' => $received_group['received_group_pk'],
                'arranging_session_pk' => $request['arranging_session_pk']
            ];
            array_push($received_group_pks, $received_group['received_group_pk']);
        }
        $request['received_groups_arranging_sessions'] = $temp;
        $request['received_group_pks'] = $received_group_pks;


        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->arrange($request);
        if ($unexpected) return $unexpected->getMessage();
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
        $received_groups = app('db')->table('received_groups')->whereIn('pk', array_values($request['received_groups']))->get();
        $temp = array();
        foreach ($received_groups as $received_group) {
            $temp[] = [
                'received_item_pk' => $received_group->received_item_pk,
                'kind' => $received_group->kind,
                'quantity' => $received_group->grouped_quantity,
                'entry_kind' => 'storing',
                'session_pk' => $request['storing_session_pk'],
                'case_pk' => $request['case_pk'],
                'accessory_pk' => $this::accessory_pk($received_group->received_item_pk)
            ];
        }
        $request['entries'] = $temp;
        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->store($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Lưu kho cụm phụ liệu nhập thành công'], 200);
    }

    public static function accessory_pk($received_item_pk)
    {
        $kind = app('db')->table('received_groups')->where('received_item_pk', $received_item_pk)->distinct('kind')->value('kind');
        switch ($kind) {
            case 'restored':
            {
                return app('db')->table('restored_items')->where('pk', $received_item_pk)->value('accessory_pk');
            }
            case 'in_transferred':
            {
                $in_distributed_item_pk = app('db')->table('in_transferred_items')->where('pk', $received_item_pk)->value('in_distributed_item_pk');
                return app('db')->table('in_distributed_items')->where('pk', $in_distributed_item_pk)->value('accessory_pk');
            }
            default:
            {
                $ordered_item_pk = app('db')->table('imported_items')->where('pk', $received_item_pk)->value('ordered_item_pk');
                return app('db')->table('ordered_items')->where('pk', $ordered_item_pk)->value('accessory_pk');
            }
        }
    }
}
