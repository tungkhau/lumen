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
    }
}
