<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class RestorationValidator
{
    use ProvidesConvenienceMethods;

    public function register($params)
    {
        try {
            $this->validate($params, [
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'comment' => 'nullable|string|max:20',
                'restored_items.*.accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
                'restored_items.*.restored_quantity' => 'required|integer|between:1,2000000000'
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
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed,' . False
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function confirm($params)
    {
        try {
            $this->validate($params, [
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . False
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function cancel($params)
    {
        try {
            $this->validate($params, [
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function receive($params)
    {
        try {
            $this->validate($params, [
                'restoration_pk' => 'required|uuid|exists:restorations,pk,is_confirmed' . True,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'restored_groups.*.restored_item_pk' => 'required|uuid|exists:restored_items,pk',
                'restored_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000',
                'restored_groups.*.case_pk' => 'required|uuid|exists:cases,pk|unstored_case'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }


}
