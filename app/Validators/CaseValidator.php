<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class CaseValidator
{
    use ProvidesConvenienceMethods;

    public function disable($params)
    {
        try {
            $this->validate($params, [
                'required|uuid|exists:cases,pk,is_active,'.True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
