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
        Validator::extend('valid_quantity', function ($value) {
            if ((is_int($value)) && ($value > 0) && ($value < 2000000000)) {
                return true;
            } else return false;
        });

        Validator::extend('nullable_valid_quantity', function ($value) {
            if ((is_int($value)) && ($value >= 0) && ($value < 2000000000)) {
                return true;
            } else return false;
        });
    }
}
