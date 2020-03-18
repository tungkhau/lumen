<?php

namespace App\Http\Controllers;

use App\Interfaces\ShelfInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShelfController extends Controller
{
    private $shelf;

    public function __construct(ShelfInterface $shelf)
    {
        $this->shelf = $shelf;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'shelf_name' => 'required|string|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->shelf->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo ô kệ tạm thời thành công'], 201);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'shelf_pk' => 'required|uuid|exists:shelves,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Check preconditions, return conflict errors(409)
        $block = app('db')->table('shelves')->where('pk', $valid_request['shelf_pk'])->value('block_pk') ? True : False;
        $cases = app('db')->table('cases')->where('shelf_pk', $valid_request['shelf_pk'])->exist();
        $failed = $block || $cases;
        if ($failed) return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->shelf->delete($valid_request['shelf_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa ô kệ tạm thời thành công'], 200);
    }

}
