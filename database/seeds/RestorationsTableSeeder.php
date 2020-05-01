<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RestorationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('restorations')->insert([
            'pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'id' => '111111',
            'is_confirmed' => True,
            'comment' => 'bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'id' => '22222',
            'is_confirmed' => false,
            'comment' => 'bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '1bd2a9da-758b-11ea-bc55-0242ac130003',
            'id' => 'RN-040420-A',
            'is_confirmed' => True,
            'comment' => 'restoration 3',
            'receiving_session_pk' => '1bd2ac82-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '1bd2b4e8-758b-11ea-bc55-0242ac130003',
            'id' => 'RN-040420-B',
            'is_confirmed' => True,
            'comment' => 'restoration 4',
            'receiving_session_pk' => '1bd2b8c6-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('restorations')->insert([
            'pk' => '55296180-79b2-11ea-bc55-0242ac130003',
            'id' => 'RN-090420-A',
            'is_confirmed' => True,
            'comment' => '',
            'user_pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
            'receiving_session_pk' => '5bd3c494-79b2-11ea-bc55-0242ac130003'
        ]);

    }
}
