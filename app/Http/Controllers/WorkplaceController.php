<?php

namespace App\Http\Controllers;

use App\Preconditions\WorkplacePrecondition;
use App\Repositories\WorkplaceRepository;
use App\Validators\WorkplaceValidator;
use Illuminate\Http\Request;

class WorkplaceController extends Controller
{


    private $validator;
    private $precondition;
    private $repository;

    public function __construct(WorkplaceValidator $validator, WorkplacePrecondition $precondition, WorkplaceRepository $repository)
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
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo bộ phận thành công'], 200);
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
        return response()->json(['success' => 'Xóa bộ phận thành công'], 200);
    }
}
