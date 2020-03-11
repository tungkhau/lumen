<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'pk' => $faker->uuid,
        'id' => $faker->numberBetween(10000,999999),
        'name' => $faker->name,
        'password' => $faker->password,
        'is_active' => true,
        'role' => $faker->randomElement(array('admin','merchandiser','manager','staff','inspector','mediator')),
        'workplace_pk' =>$faker->uuid
    ];
});
