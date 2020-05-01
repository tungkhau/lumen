<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ConceptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('conceptions')->insert([
            'pk' => '05ed417c-7a6b-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '000012',
            'name' => 'cc 12 for test issue',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '05ed438e-7a6b-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '000023',
            'name' => 'cc 23 for test issue',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('conceptions')->insert([
            'pk' => '05ed4492-7a6b-11ea-bc55-0242ac130003',
            'is_active' => True,
            'year' => 2013,
            'id' => '000013',
            'name' => 'cc 13 for test issue',
            'customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
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

    }
}
