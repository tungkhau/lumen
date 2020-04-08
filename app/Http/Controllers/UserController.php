<?php

namespace App\Http\Controllers;

use App\Preconditions\UserPrecondition;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(UserRepository $repository, UserValidator $validator, UserPrecondition $precondition)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public static function info($user_pk)
    {
        $user = app('db')->table('users')->where('pk', $user_pk)->first();
        return ['name' => $user->name, 'id' => $user->id];
    }

    public function create(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->create($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->create($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo nhân viên thành công'], 200);
    }

    public function reset_password(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->reset_password($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->reset_password($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Reset mật khẩu nhân viên thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->reactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->reactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hiện nhân viên thành công'], 200);
    }

    public function deactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->deactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->deactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ẩn nhân viên thành công'], 200);
    }

    public function change_workplace(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->change_workplace($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->change_workplace($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->change_workplace($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đổi bộ phận thành công'], 200);
    }

    public function change_password(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->change_password($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->change_password($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->change_password($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đổi mật khẩu thành công'], 200);
    }


}
