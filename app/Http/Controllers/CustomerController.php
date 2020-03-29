<?php

namespace App\Http\Controllers;

use App\Preconditions\CustomerPrecondition;
use App\Repositories\CustomerRepository;
use App\Validators\CustomerValidator;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(CustomerRepository $repository, CustomerValidator $validator, CustomerPrecondition $precondition)
    {
        $this->validator = $validator;
        $this->precondition = $precondition;
        $this->repository = $repository;

    }

    public function create(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->create($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return response($unexpected->getMessage());
        return response()->json(['success' => 'Tạo khách hàng thành công'], 200);
    }


    public function edit(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $request['customer_id'] = app('db')->table('customers')->where('pk', $request['customer_pk'])->value('id');

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Sửa khách hàng thành công'], 200);
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
        $request['customer_id'] = app('db')->table('customers')->where('pk', $request['customer_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected )return $this->unexpected_response();
        return response()->json(['success' => 'Xóa khách hàng thành công'], 200);
    }


    public function deactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->deactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['customer_id'] = app('db')->table('customers')->where('pk', $request['customer_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->deactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ẩn khách hàng thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->reactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $request['customer_id'] = app('db')->table('customers')->where('pk', $request['customer_pk'])->value('id');

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->reactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hiện khách hàng thành công'], 200);
    }
}
