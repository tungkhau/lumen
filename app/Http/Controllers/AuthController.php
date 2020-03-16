<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }
        $credentials = array();
        $credentials['id'] = $request->user_id;
        $credentials['password'] = $request->password;
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['unauthorized' => 'Mã nhân viên hoặc Mật khẩu không đúng'], 401);
        }
        return $this->respondWithToken($token);
//        return response()->json(['success' => 'Đăng nhập thành công', 'token' => $token , 'expires_in' => Auth::factory()->getTTL() * 60 ], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['success' => 'Đăng xuất thành công'], 200);
    }

}
