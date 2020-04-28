<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['customer_name' => 'Jason',
            'customer_id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['name' => 'Jason',
            'id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $history = ['id' => 'JAS',
            'type' => 'create',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_customer', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('customers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testEdit()
    {
        $inputs = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0369764668',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0369764668'];
        $this->call('POST', 'edit_customer', $inputs);
        $id = app('db')->table('customers')->where('pk', '59a6758a-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'update',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->seeStatusCode(200);
        $this->seeInDatabase('customers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDelete()
    {
        $inputs = ['customer_pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003'];
        $id = app('db')->table('customers')->where('pk', '59a6765c-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'delete',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_customer', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('customers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDeactivate()
    {
        $inputs = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $id = app('db')->table('customers')->where('pk', '59a6758a-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'deactivate',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'deactivate_customer', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('customers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testReactivate()
    {
        $inputs = ['customer_pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a6765c-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $id = app('db')->table('customers')->where('pk', '59a6765c-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'reactivate',
            'object' => 'customer',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'reactivate_customer', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('customers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }
}

