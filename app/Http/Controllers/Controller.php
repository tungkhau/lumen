<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function invalid_response($error_message)
    {
        return response()->json(['invalid' => $error_message], 400);
    }

    protected function conflict_response()
    {
        return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);
    }

    protected function unexpected_response()
    {
        return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
    }
}
