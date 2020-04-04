<?php

namespace App\Http\Controllers;

use App\Preconditions\CasePrecondition;
use App\Repositories\CaseRepository;
use App\Validators\CaseValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(CaseRepository $repository, CasePrecondition $precondition, CaseValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function create()
    {
        /* Validate request, catch invalid errors(400) */

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['case_id'] = $this->id();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo đơn vị chứa thành công'], 200);
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

    public function disable(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->disable($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->disable($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->disable($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa đơn vị chứa thành công'], 200);
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
        $received_groups = app('db')->table('received_groups')->where('case_pk', $request['case_pk'])->get();
        $request['count'] = count($received_groups);
        if ($request['count']) {
            $request['storing_session_pk'] = (string)Str::uuid();
            $received_group_pks = array();
            $entries = array();
            foreach ($received_groups as $received_group) {
                $temp[] = ['kind' => $received_group->kind,
                    'received_item_pk' => $received_group->received_item_pk,
                    'entry_kind' => 'storing',
                    'quantity' => $received_group->grouped_quantity,
                    'session_pk' => $request['storing_session_pk'],
                    'case_pk' => $request['case_pk'],
                    'accessory_pk' => ReceivedGroupController::accessory_pk($received_group->pk)
                ];
                array_push($received_group_pks, $received_group->pk);
            }
            $request['entries'] = $entries;
            $request['received_group_pks'] = $received_group_pks;
        }

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->store($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Lưu kho đơn vị chứa thành công'], 200);
    }

    public function replace(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->replace($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->replace($request);
        if ($precondition) return $this->conflict_response();


        /* Map variables */
        $request['start_shelf_pk'] = app('db')->table('cases')->where('pk', $request['case_pk'])->value('shelf_pk');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->replace($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Di chuyển đơn vị chứa thành công'], 200);
    }
}
