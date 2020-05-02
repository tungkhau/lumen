<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ActivityLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('activity_logs')->insert([
            'id' => 'ABC',
            'type' => 'create',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('activity_logs')->insert([
            'id' => 'XYZ',
            'type' => 'create',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('activity_logs')->insert([
            'id' => 'ABC',
            'type' => 'create',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003',
        ]);
    }
}
