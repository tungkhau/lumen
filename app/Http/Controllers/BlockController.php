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
        $block_id = app('db')->table('blocks')->where('pk', $request['block_pk'])->value('id');
        $temp = array();
        for ($col = 1; $col < $request['col']; $col++) {
            for ($row = 1; $row < $request['row']; $row++) {
                $row = substr("00{$row}", -2);
                $col = substr("00{$col}", -2);
                $temp[] = [
                    'name' => $block_id . "-" . $row . "-" . $col,
                    'block_pk' => $request['block_pk']
                ];
            }
        }
        $request['shelves'] = $temp;
        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->open($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Mở dãy kho thành công'], 200);
    }

    public
    function close(Request $request)
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
