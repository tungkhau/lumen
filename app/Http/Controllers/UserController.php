<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use stdClass;

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

        //Check preconditions, return conflict errors(409)
        $workplace_name = app('db')->table('workplaces')->where('pk', $valid_request['workplace_pk'])->value('name');
        $role = $valid_request['role'];
        if ($role == 'mediator' && $workplace_name == 'office' || 'warehouse')
            return response()->json(['conflict' => 'Thao tác không được phép'], 409);

        //Execute method, return success message(200) or catch unexpected errors(500)
        try {
            $this->user->create($valid_request);
        } catch (Exception $e) {
            return response()->json(['unexpected' => 'Xảy ra lỗi bất ngờ, xin vui lòng thử lại'], 500);
        }
        return response()->json(['success' => 'Tạo Nhân viên thành công'], 201);
    }


    function reset_passord(Request $request)
    {
        //Catch errors from validations & preconditions
        try {
            $this->validate($request, [
                'user_pk' => 'required | uuid | exist: users, pk'
            ]);
        } catch (ValidationException $e) {
            //TODO catch error
        }
        $user_pk = $request->user_pk;
        //Catch errors from post-conditions
        try {
            $this->user->reset_password($user_pk);
        } catch (Exception $error) {
            //TODO catch error
        }

    }

    public
    function reactivate(Request $request)
    {
        //Catch errors from validations & preconditions
        try {
            $this->validate($request, [
                'user_pk' => 'required | uuid | exist: users, pk'
            ]);
        } catch (ValidationException $e) {
            //TODO catch error
        }

        $user_pk = $request->user_pk;
        //Catch errors from post-conditions
        try {
            $this->user->reactivate($user_pk);
        } catch (Exception $error) {
            //TODO catch error
        }
    }

    public
    function deactivate(Request $request)
    {
        //Catch errors from validations & preconditions
        try {
            $this->validate($request, [
                'user_pk' => 'required | uuid | exist: users, pk'
            ]);
        } catch (ValidationException $e) {
            //TODO catch error
        }
        $user_pk = $request->user_pk;
        //Catch errors from post-conditions
        try {
            $this->user->deactivate($user_pk);
        } catch (Exception $error) {
            //TODO catch error
        }
    }

    public
    function change_workplace(Request $request)
    {
        try {
            $this->validate($request, [
                'user_pk' => 'required | uuid | exist: users, pk',
                'workplace_pk' => 'required | uuid | exist: workplaces, pk'
            ]);
        } catch (ValidationException $e) {
            //TODO catch error
        }
        $params = new stdClass();
        $params->user_pk = $request->user_pk;
        $params->workplace_pk = $request->workplace_pk;
        //Catch errors from post-conditions
        try {
            $this->user->change_workplace($params);
        } catch (Exception $e) {
            $this->exceptions->report($e);
        }
    }


}
