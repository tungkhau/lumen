<?php

namespace App\Http\Controllers;

use App\Preconditions\AccessoryPrecondition;
use App\Repositories\AccessoryRepository;
use App\Validators\AccessoryValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessoryController extends Controller
{
    private $repository;
    private $precondition;
    private $validator;

    public function __construct(AccessoryRepository $repository, AccessoryPrecondition $precondition, AccessoryValidator $validator)
    {
        $this->repository = $repository;
        $this->precondition = $precondition;
        $this->validator = $validator;
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
        return response()->json(['success' => 'Tạo phụ liệu thành công'], 200);
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

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa phụ liệu thành công'], 200);
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
        return response()->json(['success' => 'Ẩn phụ liệu thành công'], 200);
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
        return response()->json(['success' => 'Hiện phụ liệu thành công'], 200);
    }

    public function upload_photo(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->upload_photo($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['photo'] = (string)Str::uuid().'.'.$request['image']->getClientOriginalExtension();
        $request['old_photo'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('photo');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->upload_photo($request);
        if($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Cập nhật hình ảnh phụ liệu thành công'], 200);
    }

    public function delete_photo(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete_photo($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete_photo($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['old_photo'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('photo');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_photo($request);
        if($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa hình ảnh phụ liệu thành công'], 200);    }

    private function id($type_pk, $customer_pk, $item, $supplier_pk)
    {
        //TODO implement id generate
    }
}

