<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('units')->insert([
            'name' => 'cái'
        ]);
        app('db')->table('units')->insert([
            'name' => 'cuộn'
        ]);
        app('db')->table('units')->insert([
            'name' => 'mét'
        ]);
        app('db')->table('units')->insert([
            'pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'name' => 'meter',
        ]);
    }
}
