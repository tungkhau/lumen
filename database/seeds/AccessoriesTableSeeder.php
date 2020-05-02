<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class AccessoriesTableSeeder extends Seeder
{

    public function run()
    {
        app('db')->table('accessories')->insert([
            'pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-00001-YKK',
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
            'id' => 'DK-DCL-00002-YKK',
            'item' => '4321',
            'name' => 'TEST',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-00003-YKK',
            'item' => '12345',
            'name' => 'TESTA',
            'photo' => 'e2cdd742-1dcb-4508-9c24-5159f15d8d0d.PNG',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '72773130-70df-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-00004-YKK',
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
            'id' => 'DK-DCL-00005-YKK',
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
            'id' => 'DK-DCL-00006-YKK',
            'item' => '1237',
            'name' => 'ACC 3',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '5c00f918-74b8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-00001-AAA',
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
            'id' => 'DK-DCL-00001-BBB',
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
            'id' => 'DK-DCL-00001-CCC',
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
            'id' => 'DK-DCL-00001-DDD',
            'item' => '020420D',
            'name' => 'D FOR TEST DEMAND',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '1b4a19a4-771a-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'AA-AAA-00001-AAA',
            'item' => 'E',
            'name' => 'Phụ liệu E',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a1706-771a-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-000001-AST',
            'item' => 'DK1',
            'name' => 'ACC 1 for test issue',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-000002-AST',
            'item' => 'DK2',
            'name' => 'ACC 2 for test issue',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('accessories')->insert([
            'pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'DK-DCL-000003-AST',
            'item' => 'Dk3',
            'name' => 'ACC 3 for test issue',
            'type_pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
        ]);

    }
}
