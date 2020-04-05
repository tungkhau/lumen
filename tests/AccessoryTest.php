<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AccessoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateNew()
    {
        $inputs = ['customer_pk' => '1b4a1594-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a17ec-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c620-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'M',
            'art' => 'M',
            'color' => 'M',
            'size' => 'M',
            'accessory_name' => 'M',
            'comment' => 'M' ,
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['id' => 'BB-BBB-00001-BBB',
            'customer_pk' => '1b4a1594-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a17ec-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c620-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'M',
            'art' => 'M',
            'color' => 'M',
            'size' => 'M',
            'name' => 'M',
            'comment' => 'M'];
        $activity_log = ['id' => 'BB-BBB-00001-BBB',
            'type' => 'create',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_accessory', $inputs);
        $this->seeJsonEquals(['success' => 'Tạo phụ liệu thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $activity_log);
    }

    public function testCreateIncreased()
    {
        $inputs = ['customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a1706-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'F',
            'art' => 'F',
            'color' => 'F',
            'size' => 'F',
            'accessory_name' => 'F',
        'comment' => 'F' ,
        'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['id' => 'AA-AAA-00002-AAA',
            'customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a1706-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'F',
            'art' => 'F',
            'color' => 'F',
            'size' => 'F',
            'name' => 'F',
            'comment' => 'F'];
        $activity_log = ['id' => 'AA-AAA-00002-AAA',
            'type' => 'create',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_accessory', $inputs);
        $this->seeJsonEquals(['success' => 'Tạo phụ liệu thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $activity_log);
    }

    public function testCreateDiffSup()
    {
        $inputs = ['customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a17ec-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'E',
            'art' => '',
            'color' => '',
            'size' => '',
            'accessory_name' => 'Phụ liệu E',
            'comment' => '',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['id' => 'AA-AAA-00001-BBB',
            'customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a17ec-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'E',
            'art' => '',
            'color' => '',
            'size' => '',
            'name' => 'Phụ liệu E',
            'comment' => ''];
        $activity_log = ['id' => 'AA-AAA-00001-BBB',
            'type' => 'create',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $activity_log);
    }

    public function testCreateFailedUniqueType()
    {
        $inputs = ['customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a1706-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c620-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'E',
            'art' => '',
            'color' => '',
            'size' => '',
            'accessory_name' => 'E',
            'comment' => '',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_accessory', $inputs);
        $this->seeJsonEquals(['invalid' => 'Mã item đã được tạo với loại phụ liệu khác']);
        $this->seeStatusCode(400);
    }

    public function testCreateFailedUniqueAccessory()
    {
        $inputs = ['customer_pk' => '1b49c742-771a-11ea-bc55-0242ac130003',
            'supplier_pk' => '1b4a1706-771a-11ea-bc55-0242ac130003',
            'type_pk' => '1b49c36e-771a-11ea-bc55-0242ac130003',
            'unit_pk' => '59a67ad0-6dd8-11ea-bc55-0242ac130003',
            'item' => 'E',
            'art' => '',
            'color' => '',
            'size' => '',
            'accessory_name' => 'E',
            'comment' => '' ,
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_accessory', $inputs);
        $this->seeJsonEquals(['invalid' => 'Phụ liệu đã tồn tại']);
        $this->seeStatusCode(400);
    }
    public function testDelete()
    {
        $inputs = ['accessory_pk' => '59a67c4c-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67c4c-6dd8-11ea-bc55-0242ac130003'];
        $id = app('db')->table('accessories')->where('pk', '59a67c4c-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'delete',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('DELETE', 'delete_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDeactivate()
    {
        $inputs = ['accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $id = app('db')->table('accessories')->where('pk', '59a67b8e-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'deactivate',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('PATCH', 'deactivate_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testReactivate()
    {
        $inputs = ['accessory_pk' => '59a67c4c-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67c4c-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $id = app('db')->table('accessories')->where('pk', '59a67c4c-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'reactivate',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('PATCH', 'reactivate_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testUploadPhoto()
    {
        $input = [
            'accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'image' => UploadedFile::fake()->image('image.jpg'),
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ];
        $this->call('POST', 'upload_accessory_photo', $input);
        $photo = app('db')->table('accessories')->where('pk', $input['accessory_pk'])->value('photo');
        $this->seeStatusCode(200);
        $this->assertTrue(Storage::exists($photo));
        Storage::delete($photo);
    }

    public function testDeletePhoto()
    {
        $input = [
            'accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'
        ];
        $data = [
            'pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'photo' => Null
        ];
        $photo = app('db')->table('accessories')->where('pk', $input['accessory_pk'])->value('photo');
        $this->call('POST', 'delete_accessory_photo', $input);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories', $data);
        $this->assertNotTrue(Storage::exists($photo));
    }


}
