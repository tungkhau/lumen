<?php

namespace App\Http\Controllers;

use App\Preconditions\EntryPrecondition;
use App\Repositories\EntryRepository;
use App\Validators\EntryValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntryController extends Controller
{

    private $repository;
    private $validator;
    private $precondition;

    public function __construct(EntryRepository $repository, EntryValidator $validator, EntryPrecondition $precondition)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public static function inCased_quantity($received_item_pk, $case_pk)
    {
        $entries = app('db')->table('entries')->where([['received_item_pk', '=', $received_item_pk], ['case_pk', '=', $case_pk]])->pluck('quantity');
        $inCased_quantity = 0;
        if (count($entries)) {
            foreach ($entries as $entry) {
                if ($entry->quantity == Null) return False;
                $inCased_quantity += $entry->quantity;
            }
            return $inCased_quantity;
        }
        return 0;
    }

    private static function inCased_item($received_item_pk, $case_pk)
    {
        $inCased_item = app('db')->table('entries')->where([['received_item_pk', '=', $received_item_pk], ['case_pk', '=', $case_pk]])->distinct('received_item_pk')->first()->toArray();
        if ($inCased_item) return $inCased_item;
        return False;
    }


    public function ajdust(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->adjust($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['adjusting_session_pk'] = (string)Str::uuid();
        $request['quantity'] = $request['adjusted_quantity'] - $this->inCased_quantity($request['received_item_pk'], $request['case_pk']);
        $inCased_item = $this::inCased_item($request['received_item_pk'], $request['case_pk']);

        $request['entry']['kind'] = $inCased_item['kind'];
        $request['entry']['received_item_pk'] = $request['received_item_pk'];
        $request['entry']['entry_kind'] = 'adjusting';
        $request['entry']['quantity'] = Null;
        $request['entry']['session_pk'] = $request['adjusting_session_pk'];
        $request['entry']['case_pk'] = $request['case_pk'];
        $request['entry']['accessory_pk'] = $inCased_item['accessory_pk'];

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->adjust($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đăng kí hiệu chỉnh thành công'], 200);
    }

    public function discard(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->discard($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['discarding_session_pk'] = (string)Str::uuid();
        $request['quantity'] = -$request['discarded_quantity'];

        $inCased_item = $this::inCased_item($request['received_item_pk'], $request['case_pk']);

        $request['entry']['kind'] = $inCased_item['kind'];
        $request['entry']['received_item_pk'] = $request['received_item_pk'];
        $request['entry']['entry_kind'] = 'discarding';
        $request['entry']['quantity'] = Null;
        $request['entry']['session_pk'] = $request['discarding_session_pk'];
        $request['entry']['case_pk'] = $request['case_pk'];
        $request['entry']['accessory_pk'] = $inCased_item['accessory_pk'];

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->discard($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Đăng kí loại bỏ thành công'], 200);
    }

    public function verify_adjusting(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->verify_adjusting($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['verifying_session_pk'] = (string)Str::uuid();
        $request['quantity'] = app('db')->table('adjusting_sessions')->where('pk', $request['adjusting_session_pk'])->value('quantity');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->verify_adjusting($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xác thực hiệu chỉnh thành công'], 200);
    }

    public function verify_discarding(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->verify_discarding($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['verifying_session_pk'] = (string)Str::uuid();
        $request['quantity'] = app('db')->table('discarding_sessions')->where('pk', $request['discarding_session_pk'])->value('quantity');

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->verify_discarding($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Xác thực loại bỏ thành công'], 200);
    }

    public function move(Request $request)
    {
        /* Validate request, catch invalid errors(400) */
        $validation = $this->validator->move($request);
        if ($validation) return $this->invalid_response($validation);

        /* Check preconditions, return conflict errors(409) */

        /* Map variables */
        $request['moving_session_pk'] = (string)Str::uuid();
        $request['outEntries'] = array();
        $request['inEntries'] = array();
        foreach ($request['inCased_items'] as $inCased_item) {
            $temp = $this::inCased_item($inCased_item['received_item_pk'], $request['start_case_pk']);

            $request['outEntries'][]['kind'] = $temp['kind'];
            $request['outEntries'][]['received_item_pk'] = $inCased_item['received_item_pk'];
            $request['outEntries'][]['entry_kind'] = 'out';
            $request['outEntries'][]['quantity'] = -$inCased_item['quantity'];
            $request['outEntries'][]['session_pk'] = $request['moving_session_pk'];
            $request['outEntries'][]['case_pk'] = $request['start_case_pk'];
            $request['outEntries'][]['accessory_pk'] = $temp['accessory_pk'];

            $request['inEntries'][]['kind'] = $temp['kind'];
            $request['inEntries'][]['received_item_pk'] = $inCased_item['received_item_pk'];
            $request['inEntries'][]['entry_kind'] = 'in';
            $request['inEntries'][]['quantity'] = $inCased_item['quantity'];
            $request['inEntries'][]['session_pk'] = $request['moving_session_pk'];
            $request['inEntries'][]['case_pk'] = $request['end_case_pk'];
            $request['inEntries'][]['accessory_pk'] = $temp['accessory_pk'];
        }

        /* Execute method, return success message(200) or catch unexpected errors(500) */
        $unexpected = $this->repository->move($request);
        if ($unexpected) return $this->unexpected_response();
        return response()->json(['success' => 'Chuyển phụ liệu tồn thành công'], 200);
    }
}
