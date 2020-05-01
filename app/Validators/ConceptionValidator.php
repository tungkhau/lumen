<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class ConceptionValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'customer_pk' => 'required|uuid|exists:customers,pk,is_active,' . True,
                'conception_id' => 'required|string|regex:/^[0-9]+$/|max:12',
                'year' => 'required|digits:4|integer|between:2015,' . (date('Y') + 1),
                'conception_name' => 'required|string|max:20',
                'comment' => 'string|nullable|max:20',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete($params)
    {
        try {
            $this->validate($params, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function deactivate($params)
    {
        try {
            $this->validate($params, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk,is_active,' . True,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function reactivate($params)
    {
        try {
            $this->validate($params, [
                'conception_pk' => 'required|uuid|exists:conceptions,pk,is_active,' . False,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function link_accessory($params)
    {
        try {
            $this->validate($params, [
                'accessory_pks.*.accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
                'conception_pk' => 'required|uuid|exists:conceptions,pk,is_active,' . True,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function unlink_accessory($params)
    {
        try {
            $this->validate($params, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
                'conception_pk' => 'required|uuid|exists:conceptions,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
