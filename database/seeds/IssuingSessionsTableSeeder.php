<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class IssuingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('issuing_sessions')->insert([
            'pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'kind' => 'consuming',
            'id' => 'DN-000012-A#01',
            'container_pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ]);
    }
}
