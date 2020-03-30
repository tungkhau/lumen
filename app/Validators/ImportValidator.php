<?php

namespace App\Validators;

use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class ImportValidator
{
    use ProvidesConvenienceMethods;

    public function create($params)
    {
        try {
            $this->validate($params, [
                'order_pk' => 'required|uuid|exists:orders,pk,is_opened,' . True,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'imported_items.*.ordered_item_pk' => 'required|uuid|exists:ordered_items,pk,order_pk,' . $params['order_pk'],
                'imported_items.*.imported_quantity' => 'required|integer|between:1,2000000000',
                'imported_items.*.comment' => 'nullable|string|max:20'
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
                'import_pk' => 'required|uuid|exists:imports,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'imported_item_pk' => 'required|uuid|exists:imported_items,pk',
                'imported_quantity' => 'required|integer|between:1,2000000000',
                'comment' => 'nullable|string|max:20'
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
                'import_pk' => 'required|uuid|exists:imports,pk',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
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
                'import_pk' => 'required|uuid|exists:imports,pk,is_opened,' . True,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
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
                'import_pk' => 'required|uuid|exists:imports,pk,is_opened,' . False,
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function receive($params)
    {
        try {
            $this->validate($params, [
                'import_pk' => 'required|uuid|exists:imports,pk,is_opened,' . True,
                'case_pk' => 'required|uuid|exists:cases,pk|unstored_case',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'imported_groups.*.imported_item_pk' => 'required|uuid|exists:imported_items,pk',
                'imported_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function edit_receiving($params)
    {
        try {
            $this->validate($params, [
                'importing_session_pk' => 'required|uuid|exists:receiving_sessions,pk,kind,' . 'importing',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True,
                'imported_groups.*.imported_group_pk' => 'required|uuid|exists:received_groups,pk,kind,' . 'imported',
                'imported_groups.*.grouped_quantity' => 'required|integer|between:1,2000000000'
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete_receiving($params)
    {
        try {
            $this->validate($params, [
                'importing_session_pk' => 'required|uuid|exists:receiving_sessions,pk,kind,' . 'importing',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function classify($params)
    {
        try {
            $this->validate($params, [
                'imported_item_pk' => 'required|uuid|exists:imported_items,pk',
                'quality_state' => 'required|in:passed,pending,failed',
                'comment' => 'string|nullable|max:20',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function reclassify($params)
    {
        try {
            $this->validate($params, [
                'classified_item_pk' => 'required|uuid|exists:classified_items,pk',
                'quality_state' => 'required|in:passed,pending,failed',
                'comment' => 'string|nullable|max:20',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function delete_classification($params)
    {
        try {
            $this->validate($params, [
                'classified_item_pk' => 'required|uuid|exists:classified_items,pk',
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }

    public function sendback($params)
    {
        try {
            $this->validate($params, [
                'failed_item_pk' => 'required|uuid|exists:classified_items,pk,quality_state,' . 'failed',
                'user_pk' => 'required|uuid|exists:users,pk,is_active,' . True
            ]);
        } catch (ValidationException $e) {
            $error_messages = $e->errors();
            return (string)array_shift($error_messages)[0];
        }
        return False;
    }
}
