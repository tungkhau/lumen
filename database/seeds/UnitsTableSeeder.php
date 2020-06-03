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
    }
}
