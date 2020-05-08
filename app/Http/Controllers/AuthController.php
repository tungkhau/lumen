<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login_desktop(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_id' => 'required|exists:users,id,is_active,' . True,
                'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        $user = app('db')->table('users')->where('id', $request['user_id'])->whereIn('role', ['merchandiser', 'admin', 'manager'])->first();
        if (!$user) return response()->json(['invalid' => 'Tài khoản không có quyền truy cập'], 400);
        if (!app('hash')->check($valid_request['password'], $user->password)) return response()->json(['invalid' => 'Mật khẩu không đúng'], 400);
        $api_token = $this->api_token($user->pk);

        try {
            app('db')->table('users')->where('pk', $user->pk)->update(['api_token' => $api_token]);
        } catch (Exception $e) {
            return response()->json(['unexpected' => $e->getMessage()], 500);
        }
        $temp = app('db')->table('users')->where('api_token', $api_token)->first();
        $response = [
            'pk' => $temp->pk,
            'name' => $temp->name,
            'id' => $temp->id,
            'role' => $temp->role,
            'apiToken' => $temp->api_token
        ];

        return response()->json(['success' => 'Đăng nhập thành công', 'user' => $response], 200);
    }

    private function api_token($user_pk)
    {
        $payload = [
            'pk' => $user_pk,
            'exp' => time() + env('EXPIRATION')
        ];
        return Crypt::encrypt($payload);
    }

    public function login_mobile(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_id' => 'required|exists:users,id,is_active,' . True,
                'password' => 'required'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        $user = app('db')->table('users')->where('id', $request['user_id'])->whereIn('role', ['staff', 'inspector', 'mediator'])->first();
        if (!$user) return response()->json(['invalid' => 'Tài khoản không có quyền truy cập'], 400);
        if (!app('hash')->check($valid_request['password'], $user->password)) return response()->json(['invalid' => 'Mật khẩu không đúng'], 400);
        $api_token = $this->api_token($user->pk);

        try {
            app('db')->table('users')->where('pk', $user->pk)->update(['api_token' => $api_token]);
        } catch (Exception $e) {
            return response()->json(['unexpected' => $e->getMessage()], 500);
        }
        $temp = app('db')->table('users')->where('api_token', $api_token)->first();
        $response = [
            'pk' => $temp->pk,
            'name' => $temp->name,
            'id' => $temp->id,
            'role' => $temp->role,
            'apiToken' => $temp->api_token
        ];

        return response()->json(['success' => 'Đăng nhập thành công', 'user' => $response], 200);
    }

    public function logout(Request $request)
    {
        try {
            app('db')->table('users')->where('api_token', $request->header('api_token'))->update(['api_token' => Null]);
        } catch (Exception $e) {
            return response()->json(['unexpected' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Đăng xuất thành công'], 200);
    }
}
