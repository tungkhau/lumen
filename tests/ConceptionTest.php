<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ConceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testLinkAccessory()
    {
        $inputs = ['accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $conception_id = app('db')->table('conceptions')->where('pk', '59a67d0a-6dd8-11ea-bc55-0242ac130003')->value('id');
        $accessory_id = app('db')->table('accessories')->where('pk', '2884ffbe-70a1-11ea-bc55-0242ac130003')->value('id');
        $accessory_history = ['id' => $accessory_id,
            'type' => 'link',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $conception_history = ['id' => $conception_id,
            'type' => 'link',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'link_conception_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories_conceptions', $data);
        $this->seeInDatabase('activity_logs', $conception_history);
        $this->seeInDatabase('activity_logs', $accessory_history);
    }

    public function testUnlinkAccessory()
    {
        $inputs = ['accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $conception_id = app('db')->table('conceptions')->where('pk', '59a67d0a-6dd8-11ea-bc55-0242ac130003')->value('id');
        $accessory_id = app('db')->table('accessories')->where('pk', '59a67b8e-6dd8-11ea-bc55-0242ac130003')->value('id');
        $conception_history = ['id' => $conception_id,
            'type' => 'unlink',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $accessory_history = ['id' => $accessory_id,
            'type' => 'unlink',
            'object' => 'accessory',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'unlink_conception_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('accessories_conceptions', $data);
        $this->seeInDatabase('activity_logs', $conception_history);
        $this->seeInDatabase('activity_logs', $accessory_history);
    }

    public function testCreate()
    {
        $inputs = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'conception_id' => '125759',
            'year' => 2019,
            'conception_name' => 'AUK123',
            'comment' => 'something',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'id' => '125759',
            'year' => 2019,
            'name' => 'AUK123',
            'comment' => 'something'];
        $history = ['id' => '125759',
            'type' => 'create',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_conception', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDelete()
    {
        $inputs = ['conception_pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003'];
        $id = app('db')->table('conceptions')->where('pk', '59a67f08-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'delete',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_conception', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('conceptions', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDeactivate()
    {
        $inputs = ['conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $id = app('db')->table('conceptions')->where('pk', '59a67d0a-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'deactivate',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'deactivate_conception', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testReactivate()
    {
        $inputs = ['conception_pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $id = app('db')->table('conceptions')->where('pk', '59a67f08-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'reactivate',
            'object' => 'conception',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'reactivate_conception', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions', $data);
        $this->seeInDatabase('activity_logs', $history);
    }
}
