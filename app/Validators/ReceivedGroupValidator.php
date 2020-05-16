<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class ReceivedGroupValidator
{
    use ProvidesConvenienceMethods;

    public function count($params)
    {
        try {
            $this->validate($params, [
                'received_group_pk' => 'required|uuid|exists:received_groups,pk',
                'counted_quantity' => 'required|integer|between:1,99999999',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function edit_counting($params)
    {
        try {
            $this->validate($params, [
                'counting_session_pk' => 'required|uuid|exists:counting_sessions,pk',
                'counted_quantity' => 'required|integer|between:1,99999999',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete_counting($params)
    {
        try {
            $this->validate($params, [
                'counting_session_pk' => 'required|uuid|exists:counting_sessions,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function check($params)
    {
        try {
            $this->validate($params, [
                'imported_group_pk' => 'required|uuid|exists:received_groups,pk,kind,' . 'imported',
                'checked_quantity' => 'integer|checked_quantity:' . $params['imported_group_pk'],
                'unqualified_quantity' => 'required|integer|gte:0|lte:' . $params['checked_quantity'],
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ], [
                'checked_quantity.checked_quantity' => 'Số lượng kiểm mỗi cụm phụ liệu không thể vượt quá cỡ mẫu',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function edit_checking($params)
    {
        try {
            $this->validate($params, [
                'checking_session_pk' => 'required|uuid|exists:checking_sessions,pk',
                'checked_quantity' => 'integer|checked_quantity:' . $params['imported_group_pk'],
                'unqualified_quantity' => 'required|integer|gte:0|lte:' . $params['checked_quantity'],
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete_checking($params)
    {
        try {
            $this->validate($params, [
                'checking_session_pk' => 'required|uuid|exists:checking_sessions,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function arrange($params)
    {
        try {
            $this->validate($params, [
                'start_case_pk' => 'required|uuid|exists:received_groups,case_pk|unstored_case',
                'end_case_pk' => 'required|uuid|exists:cases,pk|unstored_case|different:start_case_pk',
                'received_groups.*.received_group_pk' => 'required|uuid|exists:received_groups,pk,case_pk,' . $params['start_case_pk'],
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function store($params)
    {
        try {
            $this->validate($params, [
                'received_groups.*.received_group_pk' => 'required|uuid|exists:received_groups,pk',
                'case_pk' => 'required|uuid|exists:cases,pk|stored_case',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
