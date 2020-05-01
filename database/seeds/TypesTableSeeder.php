<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{

    public function run()
    {
        app('db')->table('types')->insert([
            'id' => 'DK',
            'name' => 'Dây kéo'
        ]);
        app('db')->table('types')->insert([
            'id' => 'CT',
            'name' => 'Bộ chống trộm'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NK',
            'name' => 'Nút đóng kim loại'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NN',
            'name' => 'Nút nhựa'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NC',
            'name' => 'Nhãn chính'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NP',
            'name' => 'Nhãn phụ'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NZ',
            'name' => 'Nhãn size'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NB',
            'name' => 'Nhãn barcode'
        ]);
        app('db')->table('types')->insert([
            'id' => 'KC',
            'name' => 'Khóa chặn'
        ]);
        app('db')->table('types')->insert([
            'id' => 'CM',
            'name' => 'Chỉ may'
        ]);
        app('db')->table('types')->insert([
            'id' => 'DL',
            'name' => 'Dây luồng'
        ]);
        app('db')->table('types')->insert([
            'id' => 'TH',
            'name' => 'Dây thun'
        ]);
        app('db')->table('types')->insert([
            'id' => 'BC',
            'name' => 'Bo cổ'
        ]);
        app('db')->table('types')->insert([
            'id' => 'MC',
            'name' => 'Mắt cáo'
        ]);
        app('db')->table('types')->insert([
            'id' => 'DA',
            'name' => 'Đinh tán'
        ]);
        app('db')->table('types')->insert([
            'id' => 'NG',
            'name' => 'Nút gài'
        ]);
        app('db')->table('types')->insert([
            'id' => 'KD',
            'name' => 'Keo dựng'
        ]);
        app('db')->table('types')->insert([
            'id' => 'GC',
            'name' => 'Gói chống ẩm'
        ]);
        app('db')->table('types')->insert([
            'id' => 'TB',
            'name' => 'Thẻ bài'
        ]);
        app('db')->table('types')->insert([
            'id' => 'TD',
            'name' => 'Tem dán'
        ]);
        app('db')->table('types')->insert([
            'id' => 'BL',
            'name' => 'Băng lông'
        ]);
        app('db')->table('types')->insert([
            'id' => 'BG',
            'name' => 'Băng gai'
        ]);
        app('db')->table('types')->insert([
            'id' => 'DN',
            'name' => 'Đạn nhựa'
        ]);
        app('db')->table('types')->insert([
            'id' => 'TG',
            'name' => 'Thùng giấy'
        ]);
        app('db')->table('types')->insert([
            'id' => 'BB',
            'name' => 'Bao bì'
        ]);
        app('db')->table('types')->insert([
            'id' => 'MT',
            'name' => 'Móc treo'
        ]);
        app('db')->table('types')->insert([
            'id' => 'BK',
            'name' => 'Băng keo'
        ]);
        app('db')->table('types')->insert([
            'id' => 'DT',
            'name' => 'Dây treo'
        ]);
        app('db')->table('types')->insert([
            'id' => 'DD',
            'name' => 'Dây đai'
        ]);
        app('db')->table('types')->insert([
            'pk' => '59a679fe-6dd8-11ea-bc55-0242ac130003',
            'id' => 'AB',
            'name' => 'ABC',
        ]);
        app('db')->table('types')->insert([
            'pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'id' => 'AA',
            'name' => 'A TYPE',
        ]);
        app('db')->table('types')->insert([
            'pk' => '1b49c620-771a-11ea-bc55-0242ac130003',
            'id' => 'BB',
            'name' => 'B TYPE',
        ]);
    }
}
