<?php

namespace App\Http\Controllers;

use App\Preconditions\DevicePrecondition;
use App\Repositories\DeviceRepository;
use App\Validators\DeviceValidator;
use Illuminate\Http\Request;

class DeviceController extends Controller
{


    private $validator;
    private $precondition;
    private $repository;

    public function __construct(DeviceValidator $validator, DevicePrecondition $precondition, DeviceRepository $repository)
    {
        $this->validator = $validator;
        $this->precondition = $precondition;
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->register($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->register($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đăng kí thiết bị thành công'], 200);
    }

    public function delete(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xoá thiết bị thành công'], 200);
    }
}
