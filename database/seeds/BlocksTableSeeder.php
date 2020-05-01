<?php

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range('D', 'F') as $id)
            app('db')->table('blocks')->insert([
                'id' => $id
            ]);
        foreach (range('K', 'Z') as $id)
            app('db')->table('blocks')->insert([
                'id' => $id
            ]);

        app('db')->table('blocks')->insert([
            'pk' => '3ad6f2f2-7688-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'C',
            'row' => 1,
            'col' => 1
        ]);
        app('db')->table('blocks')->insert([
            'pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'G',
            'row' => 3,
            'col' => 2
        ]);
        app('db')->table('blocks')->insert([
            'pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'H',
            'row' => 3,
            'col' => 2
        ]);
        app('db')->table('blocks')->insert([        //Add chơi cho đủ từ G - J -)
            'pk' => '25c6ea26-79b2-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'I',
            'row' => 2,
            'col' => 1
        ]);
        app('db')->table('blocks')->insert([
            'pk' => 'f9a3e2bc-86d2-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'J',
            'row' => 2,
            'col' => 2,
        ]);
        app('db')->table('blocks')->insert([
            'pk' => '59a68750-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'A',
            'row' => 1,
            'col' => 1
        ]);
        app('db')->table('blocks')->insert([
            'pk' => '59a682e6-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'B',
            'row' => Null,
            'col' => Null
        ]);



    }
}
