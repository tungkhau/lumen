<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShelvesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('shelves')->insert([
            'pk' => 'f9a3e51e-86d2-11ea-bc55-0242ac130003',
            'name' => 'J-01-01',
            'block_pk' => 'f9a3e2bc-86d2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => 'f9a3e834-86d2-11ea-bc55-0242ac130003',
            'name' => 'J-01-02',
            'block_pk' => 'f9a3e2bc-86d2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => 'f9a3e942-86d2-11ea-bc55-0242ac130003',
            'name' => 'J-02-01',
            'block_pk' => 'f9a3e2bc-86d2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => 'f9a3eb4a-86d2-11ea-bc55-0242ac130003',
            'name' => 'J-02-02',
            'block_pk' => 'f9a3e2bc-86d2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'name' => 'A-01-01',
            'block_pk' => '59a68750-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003',
            'name' => 'C-01-01',
            'block_pk' => '3ad6f2f2-7688-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '311edb4e-79b2-11ea-bc55-0242ac130003',
            'name' => 'G-01-01',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '311edd56-79b2-11ea-bc55-0242ac130003',
            'name' => 'G-01-02',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'G-02-01',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'G-02-02',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'G-03-01',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'G-03-02',
            'block_pk' => '24c6e79c-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '311ede50-79b2-11ea-bc55-0242ac130003',
            'name' => 'H-01-01',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '311ee04e-79b2-11ea-bc55-0242ac130003',
            'name' => 'H-01-02',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'H-02-01',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'H-02-02',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'H-03-01',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'name' => 'H-03-02',
            'block_pk' => '24c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('shelves')->insert([
            'pk' => '25c6ea26-79b2-11ea-bc55-0242ac130004',
            'name' => 'I-01-01',
            'block_pk' => '25c6ea26-79b2-11ea-bc55-0242ac130003'
        ]);
    }
}
