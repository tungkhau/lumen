<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;

class AuthTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;


    public function testLoginDesktop()
    {
        $inputs = ['user_id' => '1',
            'password' => '1',];
        $this->call('POST', 'login_desktop', $inputs);
        $user = app('db')->table('users')->where('id', $inputs['user_id'])->first();
        $data = [
            'id' => '1',
            'api_token' => $user->api_token
        ];
        $response = [
            'pk' => $user->pk,
            'name' => $user->name,
            'id' => $user->id,
            'role' => $user->role,
            'apiToken' => $user->api_token
        ];
        $this->seeStatusCode(200);
        $this->seeJsonEquals(['success' => 'Đăng nhập thành công', 'user' => $response]);
        $this->SeeInDatabase('users', $data);
    }
}
