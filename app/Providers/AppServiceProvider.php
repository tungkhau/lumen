<?php

namespace App\Providers;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\OrderController;
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
            if($inCased_quantity) {
                if($value <= $inCased_quantity) return True;
                else return False;
            } else return False;
        });

        Validator::extend('adjusted_quantity', function ($attribute, $value, $parameters, $validator) {
            $inCased_quantity = EntryController::inCased_quantity($parameters[0], $parameters[1]);
            if($inCased_quantity || $value < 0) {
                if($value != $inCased_quantity) return True;
                else return False;
            } else return False;
        });

        Validator::extend('unstored_case', function ($attribute, $value, $parameters, $validator) {
            $issued_groups = app('db')->table('issued_groups')->where('case_pk', $value)->exists();
            if ($issued_groups) return False;

            $entries = app('db')->table('entries')->where('case_pk', $value)->select('quantity', 'is_pending')->get();
            $available_quantity = 0;
            foreach ($entries as $entry) {
                if ($entry->is_pending) return False;
                $available_quantity += $entry->result ? $entry->quantity : 0;
            }
            if (!$available_quantity) return False;
            return True;
        });
    }
}
