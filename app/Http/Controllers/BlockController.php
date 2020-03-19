<?php

namespace App\Http\Controllers;

use App\Interfaces\BlockInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BlockController extends Controller
{

    private $block;

    public function __construct(BlockInterface $block)
    {
        $this->block = $block;
    }

    public function open(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'block_pk' => 'required|uuid|exists:blocks,pk,is_active,' . False,
                'row' => 'required|integer|max:15|gt:0',
                'col' => 'required|integer|max:50|gt:0'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $block = app('db')->table('blocks')->where('pk', $valid_request['block_pk'])->first();
        $row = $block->row;
        $col = $block->col;

        $failed = $row || $col;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->block->open($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Mở dãy kho thành công'], 200);
    }

    public function close(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'block_pk' => 'required|uuid|exists:blocks,pk,is_active,' . True,
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $block = app('db')->table('blocks')->where('pk', $valid_request['block_pk'])->first();
        $row = $block->row ? False : True;
        $col = $block->col ? False : True;
        $shelf_pks = app('db')->table('shelves')->where('block_pk', $valid_request['block_pk'])->pluck('pk')->toArray();
        $cases = app('db')->table('cases')->whereIn('shelf_pk', $shelf_pks)->exists();

        $failed = $row || $col || $cases;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->block->close($valid_request['block_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đóng dãy kho thành công'], 200);
    }
}
