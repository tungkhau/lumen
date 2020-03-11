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
                'name' => 'Warehouse'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'Office'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'Factory 1'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'Factory 2'
        ]);
        DB::table('workplaces')->insert([
            'pk' => (string)Str::uuid(),
            'name' => 'Factory 3'
        ]);
    }
}
