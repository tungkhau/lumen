<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class DemandValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'workplace_pk' => 'required|uuid|exists:workplaces,pk',
                'conception_pk' => 'required|uuid|exists:conceptions,pk,is_active,' . True,
                'product_quantity' => 'nullable|integer|between:1,32000',
                'demanded_items.*.accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
                'demanded_items.*.demanded_quantity' => 'required|integer|between:1,2000000000',
                'demanded_items.*.comment' => 'nullable|string|max:20',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function edit($params)
    {
        try {
            $this->validate($params, [
                'demand_pk' => 'required|uuid|exists:demands,pk',
                'demanded_item_pk' => 'required|uuid|exists:demanded_items,pk,demand_pk,' . $params['demand_pk'],
                'demanded_quantity' => 'required|integer|between:1,2000000000',
                'comment' => 'nullable|string|max:20',
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
                'demand_pk' => 'required|uuid|exists:demands,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function turn_off($params)
    {
        try {
            $this->validate($params, [
                'demand_pk' => 'required|uuid|exists:demands,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function turn_on($params)
    {
        try {
            $this->validate($params, [
                'demand_pk' => 'required|uuid|exists:demands,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
