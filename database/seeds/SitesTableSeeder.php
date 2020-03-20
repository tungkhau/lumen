<?php

use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('sites')->insert([
            'id' => 'CL',
            'name' => 'XN Cai Lậy'
        ]);

        app('db')->table('sites')->insert([
            'id' => 'CG',
            'name' => 'XN Chợ Gạo'
        ]);
    }
}
