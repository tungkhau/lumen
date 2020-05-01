<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DemandItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('demanded_items')->insert([
            'pk' => '5c0102dc-74b8-11ea-bc55-0242ac130003',
            'demanded_quantity' => 100,
            'comment' => 'demanded item A-1',
            'demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '5c01041c-74b8-11ea-bc55-0242ac130003',
            'demanded_quantity' => 200,
            'comment' => 'demanded item B-1',
            'demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '9612f308-74ff-11ea-bc55-0242ac130003',
            'demanded_quantity' => 200,
            'comment' => 'demanded item C-2',
            'demand_pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c01055c-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e1741a-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 20,
            'comment' => '',
            'demand_pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e177a8-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 20,
            'comment' => '',
            'demand_pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e178ac-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 40,
            'comment' => '',
            'demand_pk' => 'b7e6ceca-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e17988-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 40,
            'comment' => '',
            'demand_pk' => 'b7e6ceca-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e17a50-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 30,
            'comment' => '',
            'demand_pk' => 'b7e6cfec-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '85e17b18-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 30,
            'comment' => '',
            'demand_pk' => 'b7e6cfec-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '9ce0adb6-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 40,
            'comment' => '',
            'demand_pk' => 'b7e6d0be-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '9ce0afe6-7a76-11ea-bc55-0242ac130003',
            'demanded_quantity' => 40,
            'comment' => '',
            'demand_pk' => 'b7e6d0be-7a6b-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);

    }
}
