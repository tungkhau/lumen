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
        $password = app('db')->table('users')->where('id', '545454')->value('password');
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
        $this->assertTrue(app('hash')->check(env('DEFAULT_PASSWORD'), $password));
    }

    public function testDeactivate()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $this->call('POST', 'deactivate_user', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testReactivate()
    {
        $inputs = ['user_pk' => '59a67242-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67242-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True];
        $this->call('POST', 'reactivate_user', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testResetPassword()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',];
        $this->call('POST', 'reset_user_password', $inputs);
        $password = app('db')->table('users')->where('pk', '511f4482-6dd8-11ea-bc55-0242ac130003')->value('password');
        $this->seeStatusCode(200);
        $this->assertTrue(app('hash')->check(env('DEFAULT_PASSWORD'), $password));
    }

    public function testChangeWorkPlace()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'workplace_pk' => '07fc0a0c-719c-11ea-bc55-0242ac130003'];
        $data = ['pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'workplace_pk' => '07fc0a0c-719c-11ea-bc55-0242ac130003'];
        $this->call('POST', 'change_user_workplace', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('users', $data);
    }

    public function testChangePassword()
    {
        $inputs = ['user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'current_password' => 'AST@PDG',
            'new_password' => 'jason',
            'new_password_confirmation' => 'jason'];
        $this->call('POST', 'change_password', $inputs);
        $password = app('db')->table('users')->where('pk', '511f4482-6dd8-11ea-bc55-0242ac130003')->value('password');
        $this->seeStatusCode(200);
        $this->assertTrue(app('hash')->check('jason', $password));
    }
}
