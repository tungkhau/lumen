<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ReceivingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('receiving_sessions')->insert([
            'pk' => '9a6185b2-8159-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '727745c6-70df-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '72b7a616-7387-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '7310c7f8-815d-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => 'd05a2506-811e-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => 'fc45ccd2-8160-11ea-bc55-0242ac130003',
            'kind' => 'importing',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '1bd2ac82-758b-11ea-bc55-0242ac130003',
            'kind' => 'restoring',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
            'kind' => 'restoring',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('receiving_sessions')->insert([
            'pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003',
            'kind' => 'restoring',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);

    }
}
