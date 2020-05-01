<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ClassifiedItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('classified_items')->insert([
            'pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003',
            'quality_state' => 'passed'
        ]);
        app('db')->table('classified_items')->insert([
            'pk' => '1cfd5cec-72a2-11ea-bc55-0242ac130003',
            'quality_state' => 'failed'
        ]);

    }
}
