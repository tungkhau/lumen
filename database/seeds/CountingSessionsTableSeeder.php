<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CountingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('counting_sessions')->insert([
            'pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' => 500,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);
    }
}
