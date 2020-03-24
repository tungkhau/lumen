<?php

namespace App\Http\Controllers;

use App\Preconditions\RestorationPrecondition;
use App\Repositories\RestorationRepository;
use App\Validators\RestorationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestorationController extends Controller
{
    private $repository;
    private $validator;
    private $precondition;

    public function __construct(RestorationRepository $repository, RestorationPrecondition $precondition, RestorationValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function register(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->register($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->register($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $valid_request['id'] = $this->id();
        $valid_request['restoration_pk'] = (string)Str::uuid();
        foreach ($valid_request['restored_items'] as $key => $value) {
            $valid_request['restored_items'][$key]['restoration_pk'] = $valid_request['restoration_pk'];
        }

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->register($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đăng kí phiếu trả thành công'], 200);
    }

    private function id()
    {
        $date = (string)date('dmy');
        $date_string = "%" . $date . "%";
        $latest_restoration = app('db')->table('restorations')->where('id', 'like', $date_string)->orderBy('id', 'desc')->first();
        if ($latest_restoration) {
            $key = substr($latest_restoration->id, -1, 1);
            $key++;
        } else $key = "A";
        return (string)'RN' . '-' . $date . '-' . $key;
    }

    public function delete(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hủy phiếu trả thành công'], 200);
    }

    public function confirm(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->confirm($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->confirm($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->confirm($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xác nhận phiếu trả thành công'], 200);
    }

    public function cancel(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->cancel($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->cancel($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->cancel($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hủy phiếu trả thành công'], 200);
    }

    public function receive(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->receive($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->receive($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        //TODO Map variables

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->receive($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ghi nhận phiếu trả thành công'], 200);
    }
}
