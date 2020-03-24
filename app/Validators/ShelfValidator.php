<?php
namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class ShelfValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'shelf_name' => 'required|string|max:20'
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
                'shelf_pk' => 'required|uuid|exists:shelves,pk'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

}
