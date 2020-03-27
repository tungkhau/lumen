<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class ReceivedGroupValidator
{
    use ProvidesConvenienceMethods;

    public function store($params)
    {
        try {
            $this->validate($params, [
                'received_groups.*.pk' => 'required|uuid|exits:received_groups,pk',
                'case_pk' => 'required|uuid|exists:cases,pk|stored_case',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
