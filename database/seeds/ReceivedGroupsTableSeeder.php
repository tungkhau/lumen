<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ReceivedGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('received_groups')->insert([
            'pk' => '7310c8d4-815d-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 600,
            'received_item_pk' => '7310c708-815d-11ea-bc55-0242ac130003',
            'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '7310c7f8-815d-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '9a61881e-8159-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 500,
            'received_item_pk' => 'a4ce862a-8155-11ea-bc55-0242ac130003',
            'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '9a6185b2-8159-11ea-bc55-0242ac130003',
            'counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'checking_session_pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003'
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
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 100,
            'received_item_pk' => '24f53a5e-7389-11ea-bc55-0242ac130003',
            'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '72b7a616-7387-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => 'd05a25f6-811e-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 20,
            'received_item_pk' => 'd05a23e4-811e-11ea-bc55-0242ac130003',
            'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'receiving_session_pk' => 'd05a2506-811e-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => 'fc45d13c-8160-11ea-bc55-0242ac130003',
            'kind' => 'imported',
            'grouped_quantity' => 20,
            'received_item_pk' => 'd05a23e4-811e-11ea-bc55-0242ac130003',
            'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'receiving_session_pk' => 'fc45ccd2-8160-11ea-bc55-0242ac130003',
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
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2b98e-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 500,
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
            'storing_session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '1bd2ba56-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 500,
            'received_item_pk' => '1bd2b7f4-758b-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
            'storing_session_pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3c6b0-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 100,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3c7b4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 100,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3c886-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 100,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3c94e-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 100,
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3ca16-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 200,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3cad4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 200,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3cd0e-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 200,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3cdd6-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 200,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3ce94-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 300,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3cf5c-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 300,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3d01a-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 300,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('received_groups')->insert([
            'pk' => '5bd3d0ce-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'grouped_quantity' => 300,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'case_pk' => null,
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'storing_session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003'
        ]);

    }
}
