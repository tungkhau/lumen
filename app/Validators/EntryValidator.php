<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class EntryValidator
{
    use ProvidesConvenienceMethods;

    public function adjust($params)
    {
        try {
            $this->validate($params, [
                'case_pk' => 'required|uuid|exists:entries, case_pk|stored_case',
                'received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'adjusted_quantity' => 'adjusted_quantity:{$request["received_item_pk"]},{$request["cases_pk"]}',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function verify_adjusting($params)
    {
        try {
            $this->validate($params, [
                'adjusting_session_pk' => 'required|uuid|exits:adjusting_sessions,pk,verifying_session_pk,' . Null,
                'result' => 'required|boolean',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function discard($params)
    {
        try {
            $this->validate($params, [
                'case_pk' => 'required|uuid|exists:entries, case_pk|stored_case',
                'received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'discarded_quantity' => 'available_quantity:{$request["received_item_pk"]},{$request["cases_pk"]}',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function verify_discarding($params)
    {
        try {
            $this->validate($params, [
                'discarding_session_pk' => 'required|uuid|exits:discarding_sessions,pk,verifying_session_pk,' . Null,
                'result' => 'required|boolean',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function move($params)
    {
        try {
            $this->validate($params, [
                'start_case_pk' => 'required|uuid|exists:entries,case_pk|stored_case',
                'inCased_items.*.received_item_pk' => 'required|uuid|exits:entries,received_item_pk',
                'inCased_items.*.quantity' => 'available_quantity:{$request["inCased_items.*.received_item_pk"]},{$request["start_cases_pk"]}',
                'end_case_pk' => 'required|uuid|exists:entries, case_pk|stored_case|different:' . $params['start_case_pk'],
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
