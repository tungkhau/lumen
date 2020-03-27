<?php

namespace App\Http\Controllers;

use App\Preconditions\BlockPrecondition;
use App\Repositories\BlockRepository;
use App\Validators\BlockValidator;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    private $repository;
    private $validator;
    private $precondition;

    public function __construct(BlockRepository $repository, BlockPrecondition $precondition, BlockValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function open(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->open($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->open($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Mở dãy kho thành công'], 200);
    }

    public function close(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->close($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->close($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->close($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đóng dãy kho thành công'], 200);
    }
}