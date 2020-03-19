<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function create(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_id' => 'required|string|unique:users,id|regex:/^[0-9]+$/|max:6',
                'user_name' => 'required|max:30|string',
                'role' => 'required|in:merchandiser,manager,staff,inspector,mediator',
                'workplace_pk' => 'required|uuid|exists:workplaces,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }
        //TODO add precondition
        //Check preconditions, return conflict errors(409)
//        $workplace_name = app('db')->table('workplaces')->where('pk', $valid_request['workplace_pk'])->value('name');
//        $role = $valid_request['role'];

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo Nhân viên thành công'], 201);
    }

    public function reset_password(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exists:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->reset_password($valid_request['user_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Reset Mật khẩu nhân viên thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exists:users,pk,is_active' . False

            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->reactivate($valid_request['user_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Thao tác thành công'], 200);
    }

    public function deactivate(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exists:users,pk,is_active' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->deactivate($valid_request['user_pk']);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Thao tác thành công'], 200);
    }

    public function change_workplace(Request $request)
    {
        //Validate request, catch invalid errors(400)
        try {
            $valid_request = $this->validate($request, [
                'user_pk' => 'required|uuid|exists:users,pk',
                'workplace_pk' => 'required|uuid|exists:workplaces,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            $error_message = (string)array_shift($error_messages)[0];
            return response()->json(['invalid' => $error_message], 400);
        }

        //TODO Check preconditions, return conflict errors(409)
        $workplace_name = app('db')->table('workplaces')->where('pk', $valid_request['workplace_pk'])->value('name');
        $current_workplace_name = app('db')->table('workplaces')->join('users', 'users.workplace_pk', '=', 'workplaces.pk')->value('workplaces.name');
        $role = app('db')->table('users')->where('pk', $valid_request['user_pk'])->value('role');

//        if (($role != 'mediator') && ($workplace_name == 'office' || 'warehouse' || $current_workplace_name))
//            return response()->json(['conflict' => 'Không thể thực hiện thao tác này'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->change_workplace($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Thao tác thành công'], 200);
    }


}
