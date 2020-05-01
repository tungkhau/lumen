<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CheckingSessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('checking_sessions')->insert([
            'pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 500,
            'unqualified_quantity' => 10,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ]);

    }
}
