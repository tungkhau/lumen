<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AccessoriesConceptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed417c-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed417c-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed438e-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed438e-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed4492-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed4492-7a6b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '5c01055c-74b8-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '5c01069c-74b8-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003'
        ]);

    }
}
