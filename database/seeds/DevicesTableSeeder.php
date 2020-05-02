<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('devices')->insert([
            'pk' => '59a67486-6dd8-11ea-bc55-0242ac130003',
            'name' => 'Phương Đông tablet',
            'id' => '123456'
        ]);
    }
}
