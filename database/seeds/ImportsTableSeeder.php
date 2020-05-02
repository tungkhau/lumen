<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ImportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('imports')->insert([
            'pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'id' => 'import_1',
            'is_opened' => true,
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'id' => 'import_2',
            'is_opened' => true,
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => '178ef7ba-7389-11ea-bc55-0242ac130003',
            'id' => 'import_3',
            'is_opened' => False,
            'order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => 'a4ce8364-8155-11ea-bc55-0242ac130003',
            'id' => 'import_5',
            'is_opened' => false,
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => '7310c4ba-815d-11ea-bc55-0242ac130003',
            'id' => 'import_6',
            'is_opened' => false,
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imports')->insert([
            'pk' => 'd05a2178-811e-11ea-bc55-0242ac130003',
            'id' => 'import_4',
            'is_opened' => False,
            'order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);

    }
}
