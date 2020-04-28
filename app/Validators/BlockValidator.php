<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class BlockValidator
{
    use ProvidesConvenienceMethods;

    public function open($params)
    {
        try {
            $this->validate($params, [
                'block_pk' => 'required|uuid|exists:blocks,pk,is_active,' . False,
                'row' => 'required|integer|max:10|gt:0',
                'col' => 'required|integer|max:15|gt:0'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function close($params)
    {
        try {
            $this->validate($params, [
                'block_pk' => 'required|uuid|exists:blocks,pk,is_active,' . True,
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
