<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class IssuedGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('issued_groups')->insert([
            'pk' => 'a561af72-8227-11ea-bc55-0242ac130003',
            'kind' => 'consumed',
            'grouped_quantity' => 40,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'issued_item_pk' => 'a561ab8a-8227-11ea-bc55-0242ac130003',
            'case_pk' => 'a561a838-8227-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('issued_groups')->insert([
            'pk' => '82770cf0-8254-11ea-bc55-0242ac130003',
            'kind' => 'consumed',
            'grouped_quantity' => 40,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'issued_item_pk' => 'a561ab8a-8227-11ea-bc55-0242ac130003',
            'case_pk' => '82770a98-8254-11ea-bc55-0242ac130003'
        ]);

    }
}
