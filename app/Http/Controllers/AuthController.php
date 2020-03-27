<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_id' => 'required',
                'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        $user = app('db')->table('users')->where('id', $request['user_id'])->first();
        if (!$user) return response()->json(['invalid' => 'Mã nhân viên hoặc mật khẩu không đúng'], 400);
        if (!app('hash')->check($valid_request['password'], $user->password)) return response()->json(['invalid' => 'Mã nhân viên hoặc mật khẩu không đúng'], 400);
        $payload = [
            'pk' => $user->pk,
            'exp' => time() + env('EXPIRATION')
        ];
        $api_token = Crypt::encrypt($payload);
        try {
            app('db')->table('users')->where('pk', $user->pk)->update(['api_token' => $api_token]);
        } catch (Exception $e) {
            return response()->json(['unexpected' => $e->getMessage()], 500);
        }
        return response()->json([['success' => 'Đăng nhập thành công'], ['api_token' => $api_token]], 200);
    }

    public function login_mobile(Request $request)
    {

    }

    public function logout(Request $request)
    {
        try {
            app('db')->table('users')->where('api_token', $request['api_token'])->update(['api_token' => Null]);
        } catch (Exception $e) {
            return response()->json(['unexpected' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Đăng xuất thành công'], 200);
    }
}
