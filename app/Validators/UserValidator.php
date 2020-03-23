<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class UserValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'user_id' => 'required|string|unique:users,id|regex:/^[0-9]+$/|max:6',
                'user_name' => 'required|max:30|string',
                'role' => 'required|in:merchandiser,manager,staff,inspector,mediator',
                'workplace_pk' => 'required|uuid|exists:workplaces,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function reset_password($params)
    {
        try {
            $this->validate($params, [
                'user_pk' => 'required|uuid|exists:users,pk'
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
                'user_pk' => 'required|uuid|exists:users,pk,is_active' . False
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
                'user_pk' => 'required|uuid|exists:users,pk,is_active' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function change_workplace($params)
    {
        try {
            $this->validate($params, [
                'user_pk' => 'required|uuid|exists:users,pk',
                'workplace_pk' => 'required|uuid|exists:workplaces,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
