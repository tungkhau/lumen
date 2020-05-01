<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // storing_session_1
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
        app('db')->table('entries')->insert([
            'pk' => '1bd2c262-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'out',
            'quantity' => -200,
            'session_pk' => '1bd2c19a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '1bd2c33e-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'in',
            'quantity' => +200,
            'session_pk' => '1bd2c19a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        ////moving_session A -6 +5
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
        app('db')->table('entries')->insert([
            'pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'adjusting',
            'quantity' => Null,
            'session_pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        //// Discard session A 7 -100
        app('db')->table('entries')->insert([
            'pk' => '1bd2d7e8-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'discarding',
            'quantity' => Null,
            'session_pk' => '1bd2d720-758b-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'
        ]);
        // For test create accessory
        // for issuing test ... nightmare
        //// block
        //// shelf
        ////case
        //// accessories
        //// conceptions
        //// acc - cc
        //// demands
        ////restoration
        app('db')->table('entries')->insert([
            'pk' => '6c935c2c-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 100,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a32c-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c935d26-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 100,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a548-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c935e02-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 100,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a8c2-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c935f60-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 100,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5aa5c-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c9361d6-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 200,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a32c-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c9362b2-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 200,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a548-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c9363ac-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 200,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a7dc-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c93649c-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 200,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5ab38-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c936564-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 300,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a32c-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c93662c-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 300,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a7dc-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c9366f4-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 300,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5a8c2-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('entries')->insert([
            'pk' => '6c93699c-79b2-11ea-bc55-0242ac130003',
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'storing',
            'quantity' => 300,
            'session_pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5ac0a-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'
        ]);
        // for test confirm and return issue
        app('db')->table('entries')->insert([
            'pk' => 'a561ac70-8227-11ea-bc55-0242ac130003',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restored',
            'entry_kind' => 'issuing',
            'quantity' => -80,
            'session_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'case_pk' => '3ce5aa5c-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ]);
    }
}
