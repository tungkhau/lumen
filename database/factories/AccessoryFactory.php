<?php

/** @var Factory $factory */

use App\Models\Accessory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Accessory::class, function (Faker $faker) {
    $type_pks = app('db')->table('types')->pluck('pk')->toArray();
    $unit_pks = app('db')->table('units')->pluck('pk')->toArray();
    $customer_pks = app('db')->table('customers')->pluck('pk')->toArray();
    $supplier_pks = app('db')->table('suppliers')->pluck('pk')->toArray();
    return [
        'id' => $faker->bothify('??-???-####-???'),
        'item' => $faker->bothify('??##??##??##??##??##'),
        'art' => $faker->bothify('??##??##??##??##??##'),
        'color' => $faker->bothify('??##??##??##??##??##'),
        'size' => $faker->bothify('??##??##?#'),
        'name' => $faker->name,
        'comment' => $faker->realText(20),
        'type_pk' => $faker->randomElement($type_pks),
        'unit_pk' => $faker->randomElement($unit_pks),
        'customer_pk' => $faker->randomElement($customer_pks),
        'supplier_pk' => $faker->randomElement($supplier_pks)
    ];
});
