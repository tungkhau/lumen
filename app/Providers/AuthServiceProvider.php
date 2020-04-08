<?php

namespace App\Providers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('api_token')) {
                $user = app('db')->table('users')->where('api_token', $request->header('api_token'))->first();
                if ($user) {
                    $payload = Crypt::decrypt($user->api_token);
                    $equal = $payload['pk'] == $user->pk ? True : False;
                    $notExpired = time() < $payload['exp'] ? True : False;
                    if ($equal && $notExpired) return $user;
                }
            }
        });
    }
}
