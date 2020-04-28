<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CaseTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $id = 'QT-' . (string)date('dmy') . '-AA';
        $data = ['id' => $id];
        $this->call('POST', 'create_case');
        $this->seeStatusCode(200);
        $this->SeeInDatabase('cases', $data);
    }

    public function testDisable()
    {
        $inputs = ['case_pk' => 'd993f450-7190-11ea-bc55-0242ac130003'];
        $data = ['pk' => 'd993f450-7190-11ea-bc55-0242ac130003',
            'is_active' => False];
        $this->call('POST', 'disable_case', $inputs);
//        $this->seeStatusCode(200);
        $this->seeJson(['success' => 'Xóa đơn vị chứa thành công']);
        $this->seeInDatabase('cases', $data);
    }

    public function testStore()
    {
        $inputs = ['shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'case_pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'store_case', $inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('storing_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $storing_session = ['user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'pk' => $pk];
        $this->seeInDatabase('storing_sessions', $storing_session);
        $entry_A = ['kind' => 'restored',
            'received_item_pk' => '1bd2aad4-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'storing',
            'quantity' => 1000,
            'session_pk' => $pk,
            'case_pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00f918-74b8-11ea-bc55-0242ac130003'];
        $entry_B = ['kind' => 'restored',
            'received_item_pk' => '1bd2abb0-758b-11ea-bc55-0242ac130003',
            'entry_kind' => 'storing',
            'quantity' => 2000,
            'session_pk' => $pk,
            'case_pk' => '1bd2a750-758b-11ea-bc55-0242ac130003',
            'accessory_pk' => '5c00fbde-74b8-11ea-bc55-0242ac130003'
        ];
        $this->seeInDatabase('entries', $entry_A);
        $this->seeInDatabase('entries', $entry_B);
        $receive_group_A = ['pk' => '1bd2ad54-758b-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $receive_group_B = ['pk' => '1bd2ae1c-758b-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $case = ['pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003'];
        $this->seeInDatabase('received_groups', $receive_group_A);
        $this->seeInDatabase('received_groups', $receive_group_B);
        $this->seeInDatabase('cases', $case);
    }

    public function testReplace()
    {
        $inputs = ['case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'end_shelf_pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $data = ['case_pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'start_shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003',
            'end_shelf_pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $case = ['pk' => '1bd2b1be-758b-11ea-bc55-0242ac130003',
            'shelf_pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003'];
        $this->call('POST', 'replace', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('replacing_sessions', $data);
        $this->seeInDatabase('cases', $case);
    }
}
