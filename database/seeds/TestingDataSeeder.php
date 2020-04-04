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
        $tables = app('db')->select('SHOW TABLES');
        app('db')->statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            app('db')->table($table->Tables_in_main)->truncate();
        }
        app('db')->statement('SET FOREIGN_KEY_CHECKS=1;');


        app('db')->table('workplaces')->insert([
            'pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003',
            'name' => 'Văn phòng',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003',
            'name' => 'Kho phụ liệu',
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
        app('db')->table('cases')->insert([
            'id' => '123456789033',
            'pk' => '5b4ca804-7388-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);

        app('db')->table('cases')->insert([
            'id' => '123456789013',
            'pk' => 'd993f450-7190-11ea-bc55-0242ac130003',
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
            'photo' => 'e2cdd742-1dcb-4508-9c24-5159f15d8d0d.PNG',
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
        app('db')->table('orders')->insert([
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

        app('db')->table('orders')->insert([
            'pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003',
            'id' => '6666 66',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
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

        app('db')->table('orders')->insert([
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

        app('db')->table('orders')->insert([
            'pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'id' => 'ORDER 3 open',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('ordered_items')->insert([
            'pk' => 'f521c1d6-70f8-11ea-bc55-0242ac130003',
            'ordered_quantity' => 3333,
            'comment' => 'od item 1-3',
            'order_pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'id' => 'import_1',
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('classified_items')->insert([
            'pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003',
            'quality_state' => 'passed'
        ]);

        app('db')->table('imported_items')->insert([
            'pk' => '72773d4c-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '11',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773612-70df-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72773ed2-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '21',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727736da-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72773f9a-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '31',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773842-70df-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('imports')->insert([
            'pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'id' => 'import_2',
            'is_opened' => False,
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '727741ca-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '12',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727739be-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '727742d8-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '22',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773b12-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72774396-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '32',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773bda-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => '178ef7ba-7389-11ea-bc55-0242ac130003',
            'id' => 'import_3',
            'is_opened' => False,
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '24f53a5e-7389-11ea-bc55-0242ac130003',
            'imported_quantity' => '13',
            'import_pk' => '178ef7ba-7389-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727739be-70df-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('receiving_sessions')->insert([
            'pk' => '727745c6-70df-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('receiving_sessions')->insert([
            'pk' => '72b7a616-7387-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);


        app('db')->table('counting_sessions')->insert([
            'pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' => 500,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('checking_sessions')->insert([
            'pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 500,
            'unqualified_quantity' => 10,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('received_groups')->insert([
            'pk' => '727746b6-70df-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 100,
            'received_item_pk' => '72773d4c-70df-11ea-bc55-0242ac130003',
            'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '727745c6-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '727747ce-70df-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 200,
            'received_item_pk' => '72773ed2-70df-11ea-bc55-0242ac130003',
            'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '727745c6-70df-11ea-bc55-0242ac130003',
            'counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'checking_session_pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003'
        ]);

        app('db')->table('received_groups')->insert([
            'pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 100,
            'received_item_pk' => '24f53a5e-7389-11ea-bc55-0242ac130003',
            'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '72b7a616-7387-11ea-bc55-0242ac130003'
        ]);


        app('db')->table('workplaces')->insert([
            'pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'name' => 'Factory 1',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => '07fc0a0c-719c-11ea-bc55-0242ac130003',
            'name' => 'Factory 2',
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3a882-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100001',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'User merchandiser',
            'role' => 'Merchandiser',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100002',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'user manager',
            'role' => 'Manager',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100003',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'user mediator',
            'role' => 'Mediator',
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100004',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'user staff',
            'role' => 'Staff',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3adbe-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100005',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'user inspector',
            'role' => 'Inspector',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3afc6-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '100006',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'user admin',
            'role' => 'Admin',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'id' => '111111',
            'is_confirmed' => True,
            'comment' => 'bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
        ]);
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
        app('db')->table('restorations')->insert([
            'pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'id' => '22222',
            'is_confirmed' => false,
            'comment' => 'bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
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

        // make for G6

        app('db')->table('classified_items')->insert([
            'pk' => '1cfd5cec-72a2-11ea-bc55-0242ac130003',
            'quality_state' => 'failed'
        ]);
        app('db')->table('classifying_sessions')->insert([
            'pk' => '1cfd5dbe-72a2-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('sendbacking_sessions')->insert([
            'pk' => '1cfd5e90-72a2-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
        //for demand test
        app('db')->table('accessories')->insert([
            'pk' => '5c00f918-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'acc A',
            'item' => '020420A',
            'name' => 'A FOR TEST DEMAND',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'acc B',
            'item' => '020420B',
            'name' => 'B FOR TEST DEMAND',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '5c01055c-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'acc C',
            'item' => '020420C',
            'name' => 'C FOR TEST DEMAND',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '5c01069c-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'acc D',
            'item' => '020420D',
            'name' => 'D FOR TEST DEMAND',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '020420',
            'name' => 'CC 1',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '020421',
            'name' => 'CC 2',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
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
        app('db')->table('demands')->insert([
            'pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'id' => 'DEMAND 1',
            'product_quantity' => 10,
            'is_opened' => true,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ]);
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
        app('db')->table('demands')->insert([
            'pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'id' => 'DEMAND 2',
            'product_quantity' => 20,
            'is_opened' => false,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c00fd64-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('demanded_items')->insert([
            'pk' => '9612f308-74ff-11ea-bc55-0242ac130003',
            'demanded_quantity' => 200,
            'comment' => 'demanded item C-2',
            'demand_pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c01055c-74b8-11ea-bc55-0242ac130003'
        ]);

        // for g6 testing
        app('db')->table('cases')->insert([
            'id' => '123456789-04',
            'pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('cases')->insert([
            'id' => '123456789-05',
            'pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('cases')->insert([
            'id' => '123456789-06',
            'pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('cases')->insert([
            'id' => '123456789-07',
            'pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('cases')->insert([
            'id' => '123456789-08',
            'pk' => '1bd2b420-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '1bd2a9da-758b-11ea-bc55-0242ac130003',
            'id' => 'RN-040420-A',
            'is_confirmed' => True,
            'comment' => 'restoration 3',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
            // receiving_session 3        1bd2ac82-758b-11ea-bc55-0242ac130003 null because foreign key law
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
        app('db')->table('receiving_sessions')->insert([
            'pk' => '1bd2ac82-758b-11ea-bc55-0242ac130003',
            'kind' => 'restoring',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2ad54-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 1000,
            'received_item_pk' => '1bd2aad4-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '1bd2ac82-758b-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2ae1c-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 2000,
            'received_item_pk' => '1bd2abb0-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '1bd2ac82-758b-11ea-bc55-0242ac130003',
        ]);
        //// Restoration 4
        app('db')->table('cases')->insert([
            'id' => '123456789-14',
            'pk' => '30ddcb36-7629-11ea-bc55-0242ac130003',
            'is_active' => True
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '1bd2b4e8-758b-11ea-bc55-0242ac130003',
            'id' => 'RN-040420-B',
            'is_confirmed' => True,
            'comment' => 'restoration 4',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
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
        app('db')->table('receiving_sessions')->insert([
            'pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restoring',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2b98e-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 500,
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'case_pk' => '30ddcb36-7629-11ea-bc55-0242ac130003', // case 4x
            'receiving_session_pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
        ]);
        //TODO received_group -> case_pk => nullable?
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2ba56-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 500,
            'received_item_pk' => '1bd2b7f4-758b-11ea-bc55-0242ac130003',
            'case_pk' => '30ddcb36-7629-11ea-bc55-0242ac130003', // case 4x
            'receiving_session_pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
        ]);

//        app('db')->table('entries')->insert([
//            'pk' => '',
//            'received_item_pk' => '',
//            'kind' => '',
//            'entry_kind' => '',
//            'quantity' => ,
//            'session_pk' => '',
//            'case_pk' => '',
//            'accessory_pk' => ''
//        ]);
        // storing_session_1
        app('db')->table('storing_sessions')->insert([
            'pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2be02-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 500,
            'session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c000-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b7f4-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 500,
            'session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ]);
        ////moving session A -5 +6
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c19a-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c262-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'out',
            'quantity' => -200,
            'session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c33e-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'in',
            'quantity' => +200,
            'session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        ////moving_session A -6 +5
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c5fa-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c6d6-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'out',
            'quantity' => -100,
            'session_pk' => '1bd2c5fa-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c79e-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'in',
            'quantity' => +100,
            'session_pk' => '1bd2c5fa-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// moving session A -5 +7
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c92e-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c9f6-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'out',
            'quantity' => -100,
            'session_pk' => '1bd2c92e-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2cc62-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'in',
            'quantity' => +100,
            'session_pk' => '1bd2c92e-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// adjusting session A 5 +200
        app('db')->table('adjusting_sessions')->insert([
            'pk' => '1bd2cdfc-758b-11ea-bc55-0242ac130003',
            'quantity' => +200,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2cec4-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'adjusting',
            'quantity' => +200,
            'session_pk' => '1bd2cdfc-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// Discard session A 5 -100
        app('db')->table('discarding_sessions')->insert([
            'pk'=> '1bd2d108-758b-11ea-bc55-0242ac130003',
            'quantity' => -100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2d1da-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'discarding',
            'quantity' => -100,
            'session_pk' => '1bd2d108-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// Adjust session A 6 +100
        app('db')->table('adjusting_sessions')->insert([
            'pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'quantity' => +100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'adjusting',
            'quantity' => +100,
            'session_pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// Discard session A 7 -100
        app('db')->table('discarding_sessions')->insert([
            'pk'=> '1bd2d720-758b-11ea-bc55-0242ac130003',
            'quantity' => -100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2d7e8-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2d7e8-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'discarding',
            'quantity' => -100,
            'session_pk' => '1bd2d720-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
    }
}
