<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MovingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c19a-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c5fa-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b286-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('moving_sessions')->insert([
            'pk' => '1bd2c92e-758b-11ea-bc55-0242ac130003',
            'start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
    }
}

