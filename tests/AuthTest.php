<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testLoginDesktop()
    {
        $inputs = ['user_id' => '123456',
            'password' => 'AST@PDG',];
        $this->call('POST', 'login_desktop', $inputs);
        $api_token = app('db')->table('users')->where('id', '123456')->value('api_token');
        $data = [
            'id' => '123456',
            'api_token' => $api_token
        ];
        $this->seeStatusCode(200);
        $this->seeJsonEquals([['success' => 'Đăng nhập thành công'], ['api_token' => $api_token]]);
        $this->SeeInDatabase('users', $data);
    }
}
