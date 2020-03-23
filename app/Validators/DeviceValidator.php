<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class DeviceValidator
{
    use ProvidesConvenienceMethods;

    public function register($params)
    {
        try {
            $this->validate($params, [
                'device_id' => 'required|max:32|unique:devices,id|string',
                'device_name' => 'required|string|max:20'
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
                'device_pk' => 'required|uuid|exists:devices,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
