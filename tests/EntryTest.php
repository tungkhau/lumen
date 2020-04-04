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
        $this->call('POST', 'adjust', $inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('adjusting_sessions')->where('quantity', -50)->value('pk');
        $adjusting_session = ['user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'quantity' => -50,
            'pk' => $pk];
        $this->seeInDatabase('adjusting_sessions', $adjusting_session);
        $entry = ['kind' => 'restored',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'adjusting',
            'quantity' => null,
            'session_pk' => $pk,
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $this->seeInDatabase('entries', $entry);
    }

    public function testVerifyAdjusting()
    {
        $inputs = ['adjusting_session_pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'result' => true,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'verify_adjusting', $inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('verifying_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $verifying_session = ['pk' => $pk,
            'kind' => 'adjusting',
            'user_pk' => $inputs['user_pk'],
            'result' => True];
        $this->seeInDatabase('verifying_sessions', $verifying_session);
        $adjust_session = ['pk' => $inputs['adjusting_session_pk'],
            'verifying_session_pk' => $pk];
        $this->seeInDatabase('adjusting_sessions', $adjust_session);
        $entry = ['pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'quantity' => 100];
        $this->seeInDatabase('entries', $entry);
    }
    public function testDiscard()
    {
        $inputs = ['case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'discarded_quantity' => -50,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','discard',$inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('discarding_sessions')->where('quantity',-50)->value('pk');
        $discarding_session = ['user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'quantity' => -50,
            'pk' => $pk];
        $this->seeInDatabase('discarding_sessions',$discarding_session);
        $entry = ['kind' => 'restored',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'discarding',
            'quantity' => null,
            'session_pk' => $pk,
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $this->seeInDatabase('entries',$entry);
    }
    public function testVerifyDiscarding()
    {
        $inputs = ['discarding_session_pk' => '1bd2d720-758b-11ea-bc55-0242ac130003',
            'result' => true,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','verify_discarding',$inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('verifying_sessions')->where('user_pk','cec3ac24-7194-11ea-bc55-0242ac130003')->value('pk');
        $verifying_session = ['pk' => $pk,
            'kind' => 'discarding',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'result' => true];
        $this->seeInDatabase('verifying_sessions',$verifying_session);
        $discarding_session = ['pk' => '1bd2d36a-758b-11ea-bc55-0242ac130003',
            'verify_session_pk' => $pk];
        $this->seeInDatabase('discarding_session',$discarding_session);
        $entry = ['pk' => '1bd2d432-758b-11ea-bc55-0242ac130003',
            'quantity' => -100];
        $this->seeInDatabase('entries',$entry);
    }
    public function testAdjustFail () // adjust when have a discarding session not confirm
    {
        $inputs = ['case_pk' => '1bd2b34e-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'adjusted_quantity' => 600,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','adjust',$inputs);
        $this->seeStatusCode(409);
    }
    public function testDiscardFail ()  // discard when have a adjust session not confirm
    {
        $inputs = ['case_pk' => '1bd2d720-758b-11ea-bc55-0242ac130003',
            'received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
            'discarded_quantity' => -20,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','discard',$inputs);
        $this->seeStatusCode(409);
    }
    public function testMoveItems ()
    {
        $inputs = ['start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'in_case_items' => [
                ['received_item_pk' => '1bd2b5a6-758b-11ea-bc55-0242ac130003',
                    'quantity' => 200],
                ['received_item_pk' => '1bd2b7f4-758b-11ea-bc55-0242ac130003',
                    'quantity' => 200]
            ],
            'end_case_id' => '1bd2b420-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST','move',$inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('moving_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $moving_session = ['start_case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_case_pk' => '1bd2b420-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'pk' => $pk];
        $this->seeInDatabase('moving_sessions',$moving_session);
        $entry_A_out = ['kind' => 'restored',
            'received_item_pk' => '1bd2aad4-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'moving',
            'quantity' => -200,
            'session_pk' => $pk,
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $entry_A_in = ['kind' => 'restored',
            'received_item_pk' => '1bd2aad4-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'moving',
            'quantity' => 200,
            'session_pk' => $pk,
            'case_pk' => '1bd2b420-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $entry_B_out = ['kind' => 'restored',
            'received_item_pk' => '1bd2abb0-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'moving',
            'quantity' => -200,
            'session_pk' => $pk,
            'case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ];
        $entry_B_in = ['kind' => 'restored',
            'received_item_pk' => '1bd2abb0-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'moving',
            'quantity' => 200,
            'session_pk' => $pk,
            'case_pk' => '1bd2b420-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ];
        $this->seeInDatabase('entries',$entry_A_in);
        $this->seeInDatabase('entries',$entry_A_out);
        $this->seeInDatabase('entries',$entry_B_in);
        $this->seeInDatabase('entries',$entry_B_out);
    }
}
