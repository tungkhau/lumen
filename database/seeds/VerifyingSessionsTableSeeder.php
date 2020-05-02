<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class VerifyingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('verifying_sessions')->insert([
            'pk' => '157753ee-8baf-11ea-bc55-0242ac130003',
            'kind'=> 'adjusting',
            'result' => '1',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('verifying_sessions')->insert([
            'pk' => '15775678-8baf-11ea-bc55-0242ac130003',
            'kind'=> 'discarding',
            'result' => '1',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003'
        ]);
    }
}
