<?php

namespace App\Providers;

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
            $received_item_pk = $parameters[0];
            $case_pk = $parameters[1];
            $entries = app('db')->table('entries')->where([['received_item_pk', '=', $received_item_pk], ['case_pk', '=', $case_pk]])->get();
            $available_quantity = 0;
            if (count($entries)) {
                foreach ($entries as $entry) {
                    $available_quantity += $entry->result ? $entry->quantity : 0;
                    if ($entry->is_pending) {
                        return False;
                    }
                }
                return ($value > 0) && ($value <= $available_quantity ? True : False);
            }
            return False;
        });

        Validator::extend('adjusted_quantity', function ($attribute, $value, $parameters, $validator) {
            $received_item_pk = $parameters[0];
            $case_pk = $parameters[1];
            $entries = app('db')->table('entries')->where([['received_item_pk', '=', $received_item_pk], ['case_pk', '=', $case_pk]])->get();
            $available_quantity = 0;
            if (count($entries)) {
                foreach ($entries as $entry) {
                    $available_quantity += $entry->result ? $entry->quantity : 0;
                    if ($entry->is_pending) {
                        return False;
                    }
                }
                return ($value >= 0) && ($value == $available_quantity ? False : True) && ($available_quantity > 0) && ($value < 2000000000);
            }
            return False;
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
