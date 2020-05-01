<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ClassifyingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('classifying_sessions')->insert([
            'pk' => 'd682abc4-80d8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3adbe-7194-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5cec-72a2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('classifying_sessions')->insert([
            'pk' => '1cfd5dbe-72a2-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3adbe-7194-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003'
        ]);

    }
}
