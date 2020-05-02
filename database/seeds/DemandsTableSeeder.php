<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DemandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('demands')->insert([
            'pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'id' => 'DEMAND 1',
            'product_quantity' => 10,
            'is_opened' => true,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demands')->insert([
            'pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'id' => 'DEMAND 2',
            'product_quantity' => 20,
            'is_opened' => false,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demands')->insert([
            'pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'id' => 'DN-000012-A',
            'product_quantity' => 10,
            'is_opened' => true,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed417c-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demands')->insert([
            'pk' => 'b7e6ceca-7a6b-11ea-bc55-0242ac130003',
            'id' => 'DN-000012-D',
            'product_quantity' => 20,
            'is_opened' => true,
            'workplace_pk' => '4aa80ee8-7a73-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed417c-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demands')->insert([
            'pk' => 'b7e6cfec-7a6b-11ea-bc55-0242ac130003',
            'id' => 'DN-000023-B',
            'product_quantity' => 10,
            'is_opened' => true,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed438e-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demands')->insert([
            'pk' => 'b7e6d0be-7a6b-11ea-bc55-0242ac130003',
            'id' => 'DN-000013-C',
            'product_quantity' => 10,
            'is_opened' => true,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '05ed4492-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ]);

    }
}
