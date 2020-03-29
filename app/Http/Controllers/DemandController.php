<?php

namespace App\Http\Controllers;

use App\Preconditions\DemandPrecondition;
use App\Repositories\DemandRepository;
use App\Validators\DemandValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DemandController extends Controller
{
    private $repository;
    private $validator;
    private $precondition;


    public function __construct(DemandValidator $validator, DemandRepository $repository, DemandPrecondition $precondition)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function create(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->create($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->create($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['demand_pk'] = (string)Str::uuid();
        $temp = array();
        foreach ($request['demanded_items'] as $demanded_item) {
            $temp[] = [
                'accessory_pk' => $demanded_item['accessory_pk'],
                'demanded_quantity' => $demanded_item['demanded_quantity'],
                'comment' => $demanded_item['comment'],
                'demand_pk' => $request['demand_pk']
            ];
        }
        $request['demanded_items'] = $temp;

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo đơn cấp phát thành công'], 200);
    }

    public function edit(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Sửa đơn cấp phát thành công'], 200);
    }

    public function delete(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa đơn cấp phát thành công'], 200);
    }

    public function turn_off(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_off($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_off($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_off($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đóng đơn cấp phát thành công'], 200);
    }

    public function turn_on(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_on($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_on($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_on($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Mở đơn cấp phát thành công'], 200);
    }
}
