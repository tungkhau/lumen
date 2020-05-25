<?php

use Illuminate\Database\Seeder;

class WorkplacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('workplaces')->insert([
            'pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003',
            'name' => 'Văn phòng',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003',
            'name' => 'Kho phụ liệu',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'name' => 'Factory 1',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => '07fc0a0c-719c-11ea-bc55-0242ac130003',
            'name' => 'Factory 2',
        ]);
        app('db')->table('workplaces')->insert([
            'pk' => '4aa80ee8-7a73-11ea-bc55-0242ac130003',
            'name' => 'Factory 3',
        ]);
    }
}
