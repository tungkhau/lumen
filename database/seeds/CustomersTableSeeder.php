<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'id' => 'DCL',
            'name' => 'CTY TNHH Decathlon VN',
            'address' => '26 Ung Văn Khiêm, Phường 25, Bình Thạnh, Hồ Chí Minh',
            'phone' => '028 3840 5336',
        ]);
        DB::table('customers')->insert([
            'id' => 'AST',
            'name' => 'CTY TNHH Assistant',
            'address' => '23/3/1 Ấp Mới 1, Tân Xuân, Hóc Môn, Hồ Chí Minh',
            'phone' => '094 600 8197',
        ]);
        app('db')->table('customers')->insert([
            'pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'AAA',
            'name' => 'CUSTOMER A',
        ]);
        app('db')->table('customers')->insert([
            'pk' => '1b4a1594-771a-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'BBB',
            'name' => 'CUSTOMER B',
        ]);
        app('db')->table('customers')->insert([
            'pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => 'ABC',
            'name' => 'ABC',
        ]);
        app('db')->table('customers')->insert([
            'pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => 'XYZ',
            'name' => 'XYZ',
        ]);
    }
}
