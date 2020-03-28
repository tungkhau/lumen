<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['user_id' => '545454',
            'user_name' => 'AN',
            'role' => 'merchandiser',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'];
        $data = ['id' => '545454',
            'name' => 'AN',
            'role' => 'merchandiser',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'create_user', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testDeactivate()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $this->call('PATCH', 'deactivate_user', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testReactivate()
    {
        $inputs = ['user_pk' => '59a67242-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67242-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $this->call('PATCH', 'reactivate_user', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testResetPassword()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',];
        $this->call('PATCH', 'reset_user_password', $inputs);
        $this->seeStatusCode(200);
    }

    public function testChangeWorkPlace()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'];
        $data = ['pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'];
        $this->call('PATCH', 'change_user_workplace', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }
}
