<?php

/** @var Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(User::class, function (Faker $faker) {
    $workplace_pks = app('db')->table('workplaces')->pluck('pk')->toArray();
    return [
        'id' => $faker->numberBetween(10000, 999999),
        'name' => $faker->name,
        'password' => app('hash')->make('1'),
        'is_active' => true,
        'role' => $faker->randomElement(array('admin', 'merchandiser', 'manager', 'staff', 'inspector', 'mediator')),
        'workplace_pk' => $faker->randomElement($workplace_pks)
    ];
});
