<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class AccessoryValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'customer_pk' => 'required|uuid|exists:customers,pk,is_active,' . True,
                'supplier_pk' => 'required|uuid|exists:suppliers,pk,is_active,' . True,
                'type_pk' => 'required|uuid|exists:types,pk',
                'unit_pk' => 'required|uuid|exists:units,pk',
                'item' => 'required|string|max:20|unique_type:{$params["customer_pk"]},{$params["type_pk"]}|unique_accessory:{$params["customer_pk"]},{$params["supplier_pk"]}',
                'art' => 'string|nullable|max:20',
                'color' => 'string|nullable|max:20',
                'size' => 'string|nullable|max:10',
                'accessory_name' => 'required|string|max:50',
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
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
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
                'accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . True,
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
                'accessory_pk' => 'required|uuid|exists:accessories,pk,is_active,' . False,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function upload_photo($params)
    {
        try {
            $this->validate($params, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
                'image' => 'required|image|max:4000',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete_photo($params)
    {
        try {
            $this->validate($params, [
                'accessory_pk' => 'required|uuid|exists:accessories,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
