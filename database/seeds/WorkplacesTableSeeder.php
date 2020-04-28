<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkplacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'warehouse'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'office'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'factory 1'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'factory 2'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'factory 3'
        ]);
    }
}
