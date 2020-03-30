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
        $request['id'] = $this->id();
        $request['restoration_pk'] = (string)Str::uuid();
        $temp = array();
        foreach ($request['restored_items'] as $restored_item) {
            $temp[] = [
                'restored_quantity' => $restored_item['restored_quantity'],
                'accessory_pk' => $restored_item['accessory_pk'],
                'restoration_pk' => $request['restoration_pk']
            ];
        }
        $request['restored_items'] = $temp;

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
        if ($unexpected) return response($unexpected->getMessage());
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
        $request['receiving_session_pk'] = (string)Str::uuid();
        $request['received_groups'] = array();
        $request['case_pks'] = array();
        foreach ($request['restored_groups'] as $restored_group) {
            $request['received_groups'][]['received_item_pk'] = $restored_group['restored_item_pk'];
            $request['received_groups'][]['grouped_quantity'] = $restored_group['grouped_quantity'];
            $request['received_groups'][]['kind'] = 'restored';
            $request['received_groups'][]['receiving_session_pk'] = $request['receiving_session_pk'];
            $request['received_groups'][]['case_pk'] = $restored_group['case_pk'];
            $request['case_pks'] = array_push($restored_group['case_pk']);
        }
        $request['case_pks'] = array_unique($request['case_pks']);

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->receive($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ghi nhận phiếu trả thành công'], 200);
    }
}
