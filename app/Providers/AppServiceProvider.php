<?php

namespace App\Providers;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('available_quantity', function ($attribute, $value, $parameters, $validator) {
            $inCased_quantity = EntryController::inCased_quantity($parameters[0], $parameters[1]);
            if ($inCased_quantity) {
                if ($value <= $inCased_quantity) return True;
                else return False;
            } else return False;
        });

        Validator::extend('adjusted_quantity', function ($attribute, $value, $parameters, $validator) {
            $inCased_quantity = EntryController::inCased_quantity($parameters[0], $parameters[1]);
            if ($inCased_quantity || $value < 0) {
                if ($value != $inCased_quantity) return True;
                else return False;
            } else return False;
        });

        Validator::extend('unstored_case', function ($attribute, $value, $parameters, $validator) {
            $issued_groups = app('db')->table('issued_groups')->where('case_pk', $value)->exists();
            if ($issued_groups) return False;

            $entries = app('db')->table('entries')->where('case_pk', $value)->pluck('quantity');
            $inCased_quantity = 0;
            if (count($entries) == 0) return True;
            foreach ($entries as $entry) {
                if ($entry->quantity == Null) return False;
                $inCased_quantity += $entry->quantity;
            }
            if ($inCased_quantity != 0) return False;
            return True;
        });

        Validator::extend('stored_case', function ($attribute, $value, $parameters, $validator) {
            $shelf_pk = app('db')->table('cases')->where('pk', $value)->value('shelf_pk');
            if (!$shelf_pk) return False;

            $issued_groups = app('db')->table('issued_groups')->where('case_pk', $value)->exists();
            if ($issued_groups) return False;

            $received_groups = app('db')->table('received_groups')->where('case_pk', $value)->exists();
            if ($received_groups) return False;

            return True;
        });

        Validator::extend('checked_quantity', function ($attribute, $value, $parameters, $validator) {
            $imported_group = app('db')->table('received_groups')->where('pk', $parameters[0])->select('received_item_pk', 'grouped_quantity')->first();
            $sample = ImportController::checking_info($imported_group->received_item_pk)['sample'];
            $grouped_quantity = $imported_group->grouped_quantity;
            $celling = $sample <= $grouped_quantity ? $sample : $grouped_quantity;
            if ($value <= $celling) return True;
            return False;
        });
    }
}
