<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StoringSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('storing_sessions')->insert([
            'pk' => '1bd2bd3a-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('storing_sessions')->insert([
            'pk' => '6c9359de-79b2-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);

    }
}
