<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RestoredItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('restored_items')->insert([
            'pk' => '0756cb02-71d6-11ea-bc55-0242ac130003',
            'restored_quantity' => '200',
            'restoration_pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '0756cc10-71d6-11ea-bc55-0242ac130003',
            'restored_quantity' => '500',
            'restoration_pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '0756ce4a-71d6-11ea-bc55-0242ac130003',
            'restored_quantity' => '400',
            'restoration_pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '0756cf1c-71d6-11ea-bc55-0242ac130003',
            'restored_quantity' => '500',
            'restoration_pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '1bd2aad4-758b-11ea-bc55-0242ac130003',
            'restored_quantity' => 1000,
            'restoration_pk' => '1bd2a9da-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '1bd2abb0-758b-11ea-bc55-0242ac130003',
            'restored_quantity' => 2000,
            'restoration_pk' => '1bd2a9da-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'restored_quantity' => 500,
            'restoration_pk' => '1bd2b4e8-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '1bd2b7f4-758b-11ea-bc55-0242ac130003',
            'restored_quantity' => 500,
            'restoration_pk' => '1bd2b4e8-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'restored_quantity' => 400,
            'restoration_pk' => '55296180-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'restored_quantity' => 800,
            'restoration_pk' => '55296180-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restored_items')->insert([
            'pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'restored_quantity' => 1200,
            'restoration_pk' => '55296180-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);

    }
}

