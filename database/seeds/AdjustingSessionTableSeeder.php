<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AdjustingSessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('adjusting_sessions')->insert([
            'pk' => '1bd2cdfc-758b-11ea-bc55-0242ac130003',
            'quantity' => +200,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'verifying_session_pk' => '157753ee-8baf-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('adjusting_sessions')->insert([
            'pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'quantity' => +100,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
        ]);

    }
}
