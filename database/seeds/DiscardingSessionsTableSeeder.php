<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DiscardingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('discarding_sessions')->insert([
            'pk' => '1bd2d108-758b-11ea-bc55-0242ac130003',
            'quantity' => -100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'verifying_session_pk' => '15775678-8baf-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('discarding_sessions')->insert([
            'pk' => '1bd2d720-758b-11ea-bc55-0242ac130003',
            'quantity' => -100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
        ]);

    }
}
