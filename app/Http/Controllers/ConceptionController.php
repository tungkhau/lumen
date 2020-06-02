<?php

namespace App\Http\Controllers;

use App\Preconditions\ConceptionPrecondition;
use App\Repositories\ConceptionRepository;
use App\Validators\ConceptionValidator;
use Illuminate\Http\Request;

class ConceptionController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(ConceptionRepository $repository, ConceptionPrecondition $precondition, ConceptionValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
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
        return response()->json(['success' => 'Tạo mã hàng thành công'], 200);
    }

    public function delete(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['conception_id'] = app('db')->table('conceptions')->where('pk', $request['conception_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa mã hàng thành công'], 200);
    }

    public function deactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->deactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['conception_id'] = app('db')->table('conceptions')->where('pk', $request['conception_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->deactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ẩn mã hàng thành công'], 200);
    }

    public function reactivate(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->reactivate($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['conception_id'] = app('db')->table('conceptions')->where('pk', $request['conception_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->reactivate($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hiện mã hàng thành công'], 200);
    }

    public function link_accessory(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->link_accessory($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->link_accessory($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['conception_id'] = app('db')->table('conceptions')->where('pk', $request['conception_pk'])->value('id');
        $accessories = app('db')->table('accessories')->whereIn('pk', $request['accessory_pks'])->select('pk', 'id')->get();
        $temp = array();
        foreach ($accessories as $accessory) {
            $temp[] = [
                'pk' => $accessory->pk,
                'id' => $accessory->id,
            ];
        }
        $request['accessories'] = $temp;

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->link_accessory($request);
        if ($unexpected) return $this->unexpected_response();

        return response()->json(['success' => 'Kết nối mã hàng và phụ liệu thành công'], 200);
    }

    public function unlink_accessory(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->unlink_accessory($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->unlink_accessory($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['conception_id'] = app('db')->table('conceptions')->where('pk', $request['conception_pk'])->value('id');
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->unlink_accessory($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Hủy kết nối mã hàng và phụ liệu thành công'], 200);
    }
}
