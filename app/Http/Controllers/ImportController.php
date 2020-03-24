<?php

namespace App\Http\Controllers;

use App\Preconditions\ImportPrecondition;
use App\Repositories\ImportRepository;
use App\Validators\ImportValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(ImportRepository $repository, ImportPrecondition $precondition, ImportValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public static function checking_info($imported_item_pk)
    {
        $total_quantity = app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->sum('grouped_quantity');
        switch (True) {
            case ($total_quantity < 151):
            {
                $checking_info['sample'] = 20;
                $checking_info['acceptance'] = 0;
                return $checking_info;
            }
            case ($total_quantity >= 151 && $total_quantity <= 280):
            {
                $checking_info['sample'] = 32;
                $checking_info['acceptance'] = 0;
                return $checking_info;
            }
            case ($total_quantity >= 281 && $total_quantity <= 500):
            {
                $checking_info['sample'] = 50;
                $checking_info['acceptance'] = 1;
                return $checking_info;
            }
            case ($total_quantity >= 501 && $total_quantity <= 1200):
            {
                $checking_info['sample'] = 80;
                $checking_info['acceptance'] = 1;
                return $checking_info;
            }
            case ($total_quantity >= 1201 && $total_quantity <= 3200):
            {
                $checking_info['sample'] = 125;
                $checking_info['acceptance'] = 2;
                return $checking_info;
            }
            case ($total_quantity >= 3201 && $total_quantity <= 10000):
            {
                $checking_info['sample'] = 200;
                $checking_info['acceptance'] = 3;
                return $checking_info;
            }
            case ($total_quantity >= 10001 && $total_quantity <= 35000):
            {
                $checking_info['sample'] = 315;
                $checking_info['acceptance'] = 5;
                return $checking_info;
            }
            case ($total_quantity >= 35001 && $total_quantity <= 150000):
            {
                $checking_info['sample'] = 500;
                $checking_info['acceptance'] = 7;
                return $checking_info;
            }
            case ($total_quantity >= 150001 && $total_quantity <= 500000):
            {
                $checking_info['sample'] = 800;
                $checking_info['acceptance'] = 10;
                return $checking_info;
            }
            default:
            {
                $checking_info['sample'] = 1250;
                $checking_info['acceptance'] = 14;
                return $checking_info;
            }
        }
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
        $request['id'] = $this->id($request['order_pk']);
        $request['import_pk'] = (string)Str::uuid();
        foreach ($request['imported_items'] as $key => $value) {
            $request['imported_items'][$key]['import_pk'] = $request['import_pk'];
        }

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->create($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Tạo phiếu nhập thành công'], 200);
    }

    private function id($order_pk)
    {
        $order_id = app('db')->table('orders')->where('order_pk', $order_pk)->value('id');
        $latest_import = app('db')->table('imports')->where('order_pk', $order_pk)->orderBy('id', 'desc')->first();
        if ($latest_import) {
            $num = (int)substr($latest_import->id, -2, 2);
            $num++;
            $num = '0' . $num;
            return $order_id . '#' . substr($num, -2, 2);
        }
        return $order_id . '#01';
    }

    public function edit(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Sửa phiếu nhập thành công'], 200);
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
        return response()->json(['success' => 'Xóa phiếu nhập thành công'], 200);
    }

    public function turn_off(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_off($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_off($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_off($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đóng phiếu nhập thành công'], 200);
    }

    public function turn_on(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->turn_on($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->turn_on($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->turn_on($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Mở phiếu nhập thành công'], 200);
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
        $valid_request['receiving_session_pk'] = (string)Str::uuid();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->receive($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Ghi nhận phiếu nhập thành công'], 200);
    }

    public function edit_receiving(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->edit_receiving($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->edit_receiving($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->edit_receiving($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Sửa phiên ghi nhận thành công'], 200);
    }

    public function delete_receiving(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete_receiving($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete_receiving($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_receiving($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa phiên ghi nhận thành công'], 200);
    }

    public function classify(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->classify($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->classify($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $request['classified_item_pk'] = (string)Str::uuid();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->classify($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đánh giá phụ liệu nhập thành công'], 200);
    }

    public function reclassify(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->reclassify($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->reclassify($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->reclassify($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đánh giá lại phụ liệu nhập thành công'], 200);
    }

    public function delete_classification(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->delete_classification($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->delete_classification($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->delete_classification($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xóa kết quả đánh giá thành công'], 200);
    }

    public function sendback(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->sendback($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */
        $precondition = $this->precondition->sendback($request);
        if ($precondition) return $this->conflict_response();

        /* Map variables */
        $imported_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $valid_request['classified_item_pk'])->value('pk');
        $valid_request['received_group_pks'] = app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->pluck('pk')->toArray();
        $valid_request['sendbacking_session_pk'] = (string)Str::uuid();

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->sendback($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Gửi trả phụ liệu thành công'], 200);
    }
}
