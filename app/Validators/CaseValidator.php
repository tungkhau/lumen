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
                'case_pk' => 'required|uuid|exists:cases,pk,is_active,' . True
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
                'case_pk' => 'required|uuid|exists:cases,pk|unstored_case',
                'shelf_pk' => 'required|uuid|exits:shelves,pk',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function replace($params)
    {
        try {
            $this->validate($params, [
                'case_pk' => 'required|uuid|exists:cases,pk|stored_case',
                'end_shelf_pk' => 'required|uuid|exits:shelves,pk',
                'user_pk' => 'required|uuid|exits:users,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }


}
