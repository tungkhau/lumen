<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class CustomerValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'customer_name' => 'required|string|max:35',
                'customer_id' => 'required|size:3| alpha|unique:customers, id',
                'address' => 'string|nullable|max:200',
                'phone' => 'string|nullable|max:20',
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
                'customer_pk' => 'required|uuid|exists:customers,pk',
                'address' => 'string|nullable|max:200',
                'phone' => 'string|nullable|max:20'
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
                'customer_pk' => 'required|uuid|exists:customers,pk'
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
                'customer_pk' => 'required|uuid|exists:customers,pk,is_active,' . True
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
                'customer_pk' => 'required|uuid|exists:customers,pk,is_active,' . False
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
