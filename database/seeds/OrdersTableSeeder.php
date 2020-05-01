<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('orders')->insert([
            'pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'id' => 'ORDER 1 open',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'ce09946d-87d9-11ea-a4ee-305a3a8512c6'
        ]);
        app('db')->table('orders')->insert([
            'pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003',
            'id' => '6666 66',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'ce09946d-87d9-11ea-a4ee-305a3a8512c6'
        ]);
        app('db')->table('orders')->insert([
            'pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'id' => 'ORDER 2 close',
            'is_opened' => false,
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'ce09946d-87d9-11ea-a4ee-305a3a8512c6'
        ]);
        app('db')->table('orders')->insert([
            'pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'id' => 'ORDER 3 open',
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'ce09946d-87d9-11ea-a4ee-305a3a8512c6'
        ]);
    }
}
