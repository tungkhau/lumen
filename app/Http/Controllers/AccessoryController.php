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

    public static function info($accessory_pk)
    {
        $accessory = app('db')->table('accessories')->where('pk', $accessory_pk)->first();
        $customer_name = app('db')->table('customers')->where('pk', $accessory->customer_pk)->value('name');
        $supplier_name = app('db')->table('suppliers')->where('pk', $accessory->supplier_pk)->value('name');
        $unit = app('db')->table('units')->where('pk', $accessory->unit_pk)->value('name');
        return [
            'id' => $accessory->id,
            'name' => $accessory->name,
            'item' => $accessory->item,
            'art' => $accessory->art,
            'color' => $accessory->color,
            'size' => $accessory->size,
            'unit' => $unit,
            'customer' => $customer_name,
            'supplier' => $supplier_name,
        ];
    }

    public function create(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->create($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['accessory_id'] = $this->id($request['type_pk'], $request['customer_pk'], $request['item'], $request['supplier_pk']);

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $unexpected->getMessage();
        return response()->json(['success' => 'Tạo phụ liệu thành công'], 200);
    }

    private function id($type_pk, $customer_pk, $item, $supplier_pk)
    {
        $customer_id = app('db')->table('customers')->where('pk', $customer_pk)->value('id');
        $supplier_id = app('db')->table('suppliers')->where('pk', $supplier_pk)->value('id');
        $type_id = app('db')->table('types')->where('pk', $type_pk)->value('id');
        $latest_accessory = app('db')->table('accessories')->where([['customer_pk', $customer_pk], ['type_pk', $type_pk]])->orderBy('created_date', 'desc')->first();
        if ($latest_accessory != Null) {
            if ($latest_accessory->item == $item) {
                $temp = (string)substr($latest_accessory->id, 0, 13);
                return $temp . $supplier_id;
            }
            $num = (int)substr($latest_accessory->id, 7, 5);
            $num++;
            $num = substr("00000{$num}", -5);
            return $type_id . '-' . $customer_id . '-' . $num . '-' . $supplier_id;
        }
        return $type_id . '-' . $customer_id . '-00001-' . $supplier_id;
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
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

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
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

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
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

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
        $request['photo'] = (string)Str::uuid() . '.' . $request['image']->getClientOriginalExtension();
        $request['old_photo'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('photo');
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->upload_photo($request);
        if ($unexpected) return $this->unexpected_response();
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
        $request['accessory_id'] = app('db')->table('accessories')->where('pk', $request['accessory_pk'])->value('id');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_photo($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa hình ảnh phụ liệu thành công'], 200);
    }
}

