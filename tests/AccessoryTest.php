<?php

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

}
