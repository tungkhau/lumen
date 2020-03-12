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
            'name' => 'Công ty TNHH Decathlon Việt Nam',
            'address' => '26 Ung Văn Khiêm, Phường 25, Bình Thạnh, Hồ Chí Minh',
            'phone' => '028 3840 5336',

        ]);
    }
}
