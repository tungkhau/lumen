<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ReceivedGroupTest extends TestCase
{
    use DatabaseTransactions;

    public function testCount()
    {
        $inputs = ['received_group_pk' => '727746b6-70df-11ea-bc55-0242ac130003',  //receive_group_1 -> imported_item_1 -> imported_1 -> is_opened = true ???
            'count_quantity' => 99,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $this->call('POST','count',$inputs);
        $pk = app('db')->table('counting_sessions')->where('counted_quantity',99)->value('pk');
        $counting_session = ['pk' => $pk,
            'counted_quantity' => 99,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '727746b6-70df-11ea-bc55-0242ac130003',
            'counting_session_pk' => $pk];
        $this->seeStatusCode(200);
        $this->seeInDatabase('counting_sessions',$counting_session);
        $this->seeInDatabase('received_groups',$received_group);
    }

    public function testEditCounting ()
    {
        $inputs = ['counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' =>  499,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'counted_quantity' => 499,];
        $this->call('PATCH','edit_counting',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('counting_sessions',$data);
    }
    public function testDeleteCounting()
    {
        $inputs = ['counting_session_pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd56fc-72a2-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '727747ce-70df-11ea-bc55-0242ac130003',
            'counting_session_pk' => null];
        $this->call('DELETE','delete_counting',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('counting_sessions',$data);
        $this->seeInDatabase('received_groups', $received_group);
    }
    public function testCheck()
    {
        $inputs = ['imported_group_pk' => '727746b6-70df-11ea-bc55-0242ac130003',  //receive_group_1 -> imported_item_1 -> imported_1 -> is_opened = true ???
            'checked_quantity' => 100,
            'unqualified_quantity' => 1,
            'user_pk' => '511f4482-6dd8-11ea-bc55-024
            2ac130003'];
        $this->call('POST','check',$inputs);
        $pk = app('db')->table('checking_sessions')->where('checked_quantity',100)->value('pk');
        $data = ['pk' => $pk,
            'checked_quantity' => 100,
            'unqualified_quantity'  => 1,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $received_group = ['pk' => '727746b6-70df-11ea-bc55-0242ac130003',
            'checking_session_pk' => $pk];
        $this->seeStatusCode(200);
        $this->seeInDatabase('checking_sessions',$data);
        $this->seeInDatabase('received_groups',$received_group);
    }
    public function testEditChecking()
    {
        $inputs = ['checking_session_pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 500,
            'unqualified_quantity' => 9,
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '1cfd592c-72a2-11ea-bc55-0242ac130003',
            'checked_quantity' => 500,
            'unqualified_quantity' => 9];
        $this->call('PATCH','edit_checking',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('checking_sessions',$data);
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
