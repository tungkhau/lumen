<?php

use Illuminate\Database\Seeder;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('workplaces')->insert([
            'pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003',
            'name' => 'Office',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003',
            'name' => 'Warehouse',
        ]);
        app('db')->table('users')->insert([
            'pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '123456',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'TEST',
            'role' => 'Mediator',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => '59a67242-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => '234567',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'TEST',
            'role' => 'Mediator',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('devices')->insert([
            'pk' => '59a67486-6dd8-11ea-bc55-0242ac130003',
            'name' => 'TEST',
            'id' => '123456'
        ]);
        app('db')->table('customers')->insert([
            'pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABC',
            'name' => 'ABC',
        ]);
        app('db')->table('customers')->insert([
            'pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'XYZ',
            'name' => 'XYZ',
        ]);
        app('db')->table('suppliers')->insert([
            'pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABC',
            'name' => 'ABC',
        ]);
        app('db')->table('suppliers')->insert([
            'pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'XYZ',
            'name' => 'XYZ',
        ]);
        app('db')->table('types')->insert([
            'pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'id' => 'AB',
            'name' => 'ABC',
        ]);
        app('db')->table('units')->insert([
            'pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'name' => 'meter',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABCACVASDEQDFQED',
            'item' => '1234',
            'name' => 'TEST',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '59a67c4c-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'ABCACVASDEQDFQEF',
            'item' => '4321',
            'name' => 'TEST',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '125754',
            'name' => 'Bla bla',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => '125754',
            'year' => 2014,
            'name' => 'Bla bla',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories_conceptions')->insert([
            'accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('cases')->insert([
            'id' => '123456789012',
            'pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);

        app('db')->table('blocks')->insert([
            'pk' => '59a68750-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'A',
            'row' => 1,
            'col' => 1
        ]);
        app('db')->table('blocks')->insert([
            'pk' => '59a682e6-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'B',
            'row' => Null,
            'col' => Null
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'name' => 'test',
            'block_pk' => '59a68750-6dd8-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('accessories')->insert([
            'pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABCACVASDEQDFQEJ',
            'item' => '12345',
            'name' => 'TESTA',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);

        //make for G4

        app('db')->table('accessories')->insert([
            'pk' => '72773130-70df-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABCACVASDEQDFQER',
            'item' => '1235',
            'name' => 'ACC 1',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '72773234-70df-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABCACVASDEQDFQE1',
            'item' => '1236',
            'name' => 'ACC 2',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '727733e2-70df-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABCACVASDEQDFQE3',
            'item' => '1237',
            'name' => 'ACC 3',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app( 'db')->table('orders')->insert([
            'pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'id' => 'ORDER 1 open',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => '72773612-70df-11ea-bc55-0242ac130003',
            'ordered_quantity' => 1111,
            'comment' => 'od item 1-1',
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
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

        app( 'db')->table('orders')->insert([
            'pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'id' => 'ORDER 2 close',
            'is_opened' => false,
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
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
    }
}
