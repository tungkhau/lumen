<?php

namespace App\Http\Controllers;

use App\Interfaces\DeviceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    private $device;

    public function __construct(DeviceInterface $device)
    {
        $this->device = $device;
    }

    public function register(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'device_id' => 'required|max:32|unique:devices,id|string',
                'device_name' => 'required|string|max:20'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->device->register($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Đăng kí thiết bị thành công'], 201);
    }

    public function delete(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'device_pk' => 'required|uuid|exists:devices,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }
        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->device->delete($valid_request['device_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Xóa thiết bị thành công'], 200);
    }
}
