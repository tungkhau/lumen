<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AccessoryTest extends TestCase
{
    use DatabaseTransactions;

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
