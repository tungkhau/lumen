<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrderedItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('ordered_items')->insert([
            'pk' => '72773612-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 3114123,
            'comment' => 'od item 1-1',
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '7043b34c-71a8-11ea-bc55-0242ac130003',
            'ordered_quantity' => 4444,
            'comment' => 'od item 1 - 4',
            'order_pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '24b29244-71a9-11ea-bc55-0242ac130003',
            'ordered_quantity' => 2444,
            'comment' => 'od item 2 - 4',
            'order_pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '727736da-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 2221,
            'comment' => 'od item 2-1',
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '72773842-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 3331,
            'comment' => 'od item 3-1',
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '727733e2-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '727739be-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 1112,
            'comment' => 'od item 1-2',
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '72773b12-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 2222,
            'comment' => 'od item 2-2',
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '72773bda-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 3332,
            'comment' => 'od item 3-2',
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '727733e2-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => 'f521c1d6-70f8-11ea-bc55-0242ac130003',
            'ordered_quantity' => 3333,
            'comment' => 'od item 1-3',
            'order_pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
    }
}
