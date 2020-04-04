<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class EntryTest extends TestCase
{
    use DatabaseTransactions;

    public function testAdjust()
    {
        $inputs = ['case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'adjusted_quantity' => 350,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','adjust',$inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('adjusting_sessions')->where('quantity',-50)->value('pk');
        $adjusting_session = ['user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'quantity' => -50,
            'pk' => $pk];
        $this->seeInDatabase('adjusting_sessions',$adjusting_session);
        $entry = ['kind' => 'restored',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'adjusting',
            'quantity' => null,
            'session_pk' => $pk,
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $this->seeInDatabase('entries',$entry);
    }
    public function testVerifyAdjusting()
    {
        $inputs = ['adjusting_session_pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'result' => true,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','verify_adjusting',$inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('verifying_sessions')->where('user_pk','cec3ac24-7194-11ea-bc55-0242ac130003')->value('pk');
        $verifying_session = ['pk' => $pk,
            'kind' => 'adjusting',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'result' => true];
        $this->seeInDatabase('verifying_sessions',$verifying_session);
        $adjust_session = ['pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'verify_session_pk' => $pk];
        $this->seeInDatabase('adjusting_sessions',$adjust_session);
        $entry = ['pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'quantity' => 100];
        $this->seeInDatabase('entries',$entry);
    }
}
