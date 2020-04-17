<?php

use App\ViewModels\User;

return [

    "defaults" => [
        "guard" => 'api',
        "passwords" => "users",
    ],

    "guards" => [
        "api" => [
            "driver" => "api",
            "provider" => "users"
        ],
    ],

    "providers" => [
        "users" => [
            "driver" => "eloquent",
            "model" => User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
