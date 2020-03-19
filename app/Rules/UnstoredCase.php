<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UnstoredCase implements Rule
{
    public function passes($attribute, $value)
    {
        $issued_groups = app('db')->table('issued_groups')->where('case_pk', $value)->exists();
        if ($issued_groups) return False;

        $entries = app('db')->table('entries')->where('case_pk', $value)->select('quantity', 'is_pending')->get();
        $inCased_Quantity = 0;
        foreach ($entries as $entry) {
            if ($entry->ispending) return False;
            $inCased_Quantity += $entry->quantity;
        }
        if (!$inCased_Quantity) return False;
        return True;
    }

    public function message()
    {
        return 'Chỉ có thể chọn :attribute ngoài kho';
    }
}
