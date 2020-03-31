<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ReceivedGroupTest extends TestCase
{
    use DatabaseTransactions;

    public function testCount()
    {
        $inputs = ['received_group_pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',  //receive_group_1 -> imported_item_1 -> imported_1 -> is_opened = true ???
            'counted_quantity' => 99,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'count', $inputs);
        $counting_session_pk = app('db')->table('counting_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $counting_session = ['pk' => $counting_session_pk,
            'counted_quantity' => 99,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',
            'counting_session_pk' => $counting_session_pk];
        $this->seeJsonEquals(['success' => 'Kiểm số lượng thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('counting_sessions', $counting_session);
        $this->seeInDatabase('received_groups', $received_group);
    }

    public function testEditCounting()
    {
        $inputs = ['counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' => 499,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' => 499,];
        $this->call('PATCH', 'edit_counting', $inputs);
        $this->seeJsonEquals(['success' => 'Sửa phiên kiểm số lượng thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('counting_sessions', $data);
    }

    public function testDeleteCounting()
    {
        $inputs = ['counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '727747ce-70df-11ea-bc55-0242ac130003',
            'counting_session_pk' => null];
        $this->call('DELETE', 'delete_counting', $inputs);
        $this->seeJsonEquals(['success' => 'Xóa phiên kiểm số lượng thành công']);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('counting_sessions', $data);
        $this->seeInDatabase('received_groups', $received_group);
    }

    public function testCheck()
    {
        $inputs = ['imported_group_pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',  //receive_group_1 -> imported_item_1 -> imported_1 -> is_opened = true ???
            'checked_quantity' => 10,
            'unqualified_quantity' => 1,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'check', $inputs);
        $pk = app('db')->table('checking_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $data = ['pk' => $pk,
            'checked_quantity' => 10,
            'unqualified_quantity' => 1,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '7bd0f1bc-7387-11ea-bc55-0242ac130003',
            'checking_session_pk' => $pk];
        $this->seeJsonEquals(['success' => 'Kiểm chất lượng thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('checking_sessions', $data);
        $this->seeInDatabase('received_groups', $received_group);
    }

    public function testEditChecking()
    {
        $inputs = ['checking_session_pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 1,
            'unqualified_quantity' => 0,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 1,
            'unqualified_quantity' => 0];
        $this->call('PATCH', 'edit_checking', $inputs);
        $this->seeJsonEquals(['success' => 'Sửa phiên kiểm chất lượng thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('checking_sessions', $data);
    }

    public function testDeleteChecking()
    {
        $inputs = ['checking_session_pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '727747ce-70df-11ea-bc55-0242ac130003',
            'checking_session_pk' => null];
        $this->call('DELETE', 'delete_checking', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('checking_sessions', $data);
        $this->seeInDatabase('received_groups', $received_group);
    }
}
