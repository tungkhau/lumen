<?php

use Illuminate\Database\Seeder;


class IssuedItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('issued_items')->insert([
            'pk' => 'a561ab8a-8227-11ea-bc55-0242ac130003',
            'kind' => 'consumed',
            'issued_quantity' => 20,
            'end_item_pk' => '85e1741a-7a76-11ea-bc55-0242ac130003',
            'issuing_session_pk' => 'a561aa90-8227-11ea-bc55-0242ac130003'
        ]);
    }
}
