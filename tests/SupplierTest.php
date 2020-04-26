<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class SupplierTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['supplier_name' => 'Jason',
            'supplier_id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['name' => 'Jason',
            'id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $history = ['id' => 'JAS',
            'type' => 'create',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testEdit()
    {
        $inputs = ['supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0946008197',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $id = app('db')->table('suppliers')->where('pk', '59a67724-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'update',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'edit_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDelete()
    {
        $inputs = ['supplier_pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003'];
        $id = app('db')->table('suppliers')->where('pk', '59a677ec-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'delete',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('suppliers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testDeactivate()
    {
        $inputs = ['supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $id = app('db')->table('suppliers')->where('pk', '59a67724-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'deactivate',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'deactivate_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }

    public function testReactivate()
    {
        $inputs = ['supplier_pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $id = app('db')->table('suppliers')->where('pk', '59a677ec-6dd8-11ea-bc55-0242ac130003')->value('id');
        $history = ['id' => $id,
            'type' => 'reactivate',
            'object' => 'supplier',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'reactivate_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
        $this->seeInDatabase('activity_logs', $history);
    }
}
