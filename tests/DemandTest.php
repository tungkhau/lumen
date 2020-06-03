<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;

class DemandTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function testCreate()
    {
        $inputs = ['workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003',
            'product_quantity' => 200,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'demanded_items' => [
                [
                    'accessory_pk' => '5c01055c-74b8-11ea-bc55-0242ac130003',
                    'demanded_quantity' => 400,
                    'comment' => ''
                ],
                [
                    'accessory_pk' => '5c01069c-74b8-11ea-bc55-0242ac130003',
                    'demanded_quantity' => 600,
                    'comment' => ''
                ]
            ]
        ];
        $this->call('POST', 'create_demand', $inputs);
        $conception_id = app('db')->table('conceptions')->where('pk', $inputs['conception_pk'])->value('id');
        $id = 'DN-' . $conception_id . '-A';
        $pk = app('db')->table('demands')->where('id', $id)->value('pk');
        $demand = ['id' => $id,
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003',
            'product_quantity' => 200,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'pk' => $pk];
        $this->seeJsonEquals(['success' => 'Tạo đơn cấp phát thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands', $demand);
        $demanded_items = array();
        foreach ($inputs['demanded_items'] as $input) {
            $demanded_items[] = [
                'demanded_quantity' => $input['demanded_quantity'],
                'demand_pk' => $pk,
                'accessory_pk' => $input['accessory_pk']
            ];
        }
        foreach ($demanded_items as $demanded_item) {
            $this->seeInDatabase('demanded_items', $demanded_item);
        }
    }

    public function testEdit()
    {
        $inputs = ['demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'demanded_item_pk' => '5c0102dc-74b8-11ea-bc55-0242ac130003',
            'demanded_quantity' => 300,
            'comment' => 'edit',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['demanded_quantity' => 300,
            'comment' => 'edit'];
        $this->call('POST', 'edit_demand', $inputs);
        $this->seeJsonEquals(['success' => 'Sửa đơn cấp phát thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demanded_items', $data);
    }

    public function testDelete()
    {
        $temp = app('db')->table('demanded_items')->where('pk', '5c010192-74b8-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $demanded_item_pks = array();
        foreach ($temp as $item) {
            $demanded_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '5c010192-74b8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_demand', $inputs);
        $this->seeJsonEquals(['success' => 'Xóa đơn cấp phát thành công']);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('demands', $data);
        foreach ($demanded_item_pks as $demanded_item_pk) {
            $this->notSeeInDatabase('demanded_items', $demanded_item_pk);
        }
    }

    public function testTurnOff()
    {
        $inputs = ['demand_pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3a882-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => 'b7e6cb50-7a6b-11ea-bc55-0242ac130003',
            'is_opened' => false];
        $this->call('POST', 'turn_off_demand', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands', $data);
    }

    public function testTurnOn()
    {
        $inputs = ['demand_pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'is_opened' => true];
        $this->call('POST', 'turn_on_demand', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands', $data);
    }

    public function testIssuing()
    {
        $inputs = ['demand_pk' => 'b7e6cfec-7a6b-11ea-bc55-0242ac130003',
            'issued_groups' => [
                [
                    'case_pk' => '102c9726-821f-11ea-bc55-0242ac130003',
                    'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 1
                ],
                [
                    'case_pk' => '102c9726-821f-11ea-bc55-0242ac130003',
                    'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 2
                ],
                [
                    'case_pk' => '3ce5a994-79b2-11ea-bc55-0242ac130003',
                    'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 1
                ],
                [
                    'case_pk' => '3ce5a994-79b2-11ea-bc55-0242ac130003',
                    'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 2
                ]
            ],
            'inCased_items' => [
                [
                    'case_pk' => '3ce5ab38-79b2-11ea-bc55-0242ac130003',
                    'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
                    'issued_quantity' => 2
                ],
                [
                    'case_pk' => '3ce5ac0a-79b2-11ea-bc55-0242ac130003',
                    'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
                    'issued_quantity' => 4
                ]
            ],
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $this->call('POST', 'issue', $inputs);
        $this->seeStatusCode(200);
        $pk = app('db')->table('issuing_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $issued_items_2_pk = app('db')->table('issued_items')->where('issued_quantity', 2)->value('pk');
        $issued_items_3_pk = app('db')->table('issued_items')->where('issued_quantity', 4)->value('pk');
        $issuing_session = ['pk' => $pk,
            'id' => 'DN-000023-B#01',
            'kind' => 'consuming',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'container_pk' => 'b7e6cfec-7a6b-11ea-bc55-0242ac130003'];
        $entry_2_out = ['kind' => 'restored',
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'entry_kind' => 'issuing',
            'quantity' => -2,
            'session_pk' => $pk,
            'case_pk' => '3ce5ab38-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad6ca-79b2-11ea-bc55-0242ac130003'];
        $entry_3_out = ['kind' => 'restored',
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'entry_kind' => 'issuing',
            'quantity' => -4,
            'session_pk' => $pk,
            'case_pk' => '3ce5ac0a-79b2-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad7ce-79b2-11ea-bc55-0242ac130003'];
        $case = ['pk' => '3ce5a994-79b2-11ea-bc55-0242ac130003',
            'shelf_pk' => null];
        $issued_items_2 = [
            'pk' => $issued_items_2_pk,
            'issued_quantity' => 2,
            'kind' => 'consumed',
            'end_item_pk' => '85e17a50-7a76-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk
        ];
        $issued_items_3 = [
            'pk' => $issued_items_3_pk,
            'issued_quantity' => 4,
            'kind' => 'consumed',
            'end_item_pk' => '85e17b18-7a76-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk
        ];
        $issued_group_2_5_1 = ['kind' => 'consumed',
            'grouped_quantity' => 1,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk,
            'issued_item_pk' => $issued_items_2_pk,
            'case_pk' => '3ce5a994-79b2-11ea-bc55-0242ac130003'];
        $issued_group_2_9_1 = ['kind' => 'consumed',
            'grouped_quantity' => 1,
            'received_item_pk' => '55296522-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk,
            'issued_item_pk' => $issued_items_2_pk,
            'case_pk' => '102c9726-821f-11ea-bc55-0242ac130003'];
        $issued_group_3_5_2 = ['kind' => 'consumed',
            'grouped_quantity' => 2,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk,
            'issued_item_pk' => $issued_items_3_pk,
            'case_pk' => '3ce5a994-79b2-11ea-bc55-0242ac130003'];
        $issued_group_3_9_2 = ['kind' => 'consumed',
            'grouped_quantity' => 2,
            'received_item_pk' => '552965f4-79b2-11ea-bc55-0242ac130003',
            'issuing_session_pk' => $pk,
            'issued_item_pk' => $issued_items_3_pk,
            'case_pk' => '102c9726-821f-11ea-bc55-0242ac130003'];
        $this->seeInDatabase('issuing_sessions', $issuing_session);
        $this->seeInDatabase('cases', $case);
        $this->seeInDatabase('issued_items', $issued_items_2);
        $this->seeInDatabase('issued_items', $issued_items_3);
        $this->seeInDatabase('entries', $entry_2_out);
        $this->seeInDatabase('entries', $entry_3_out);
        $this->seeInDatabase('issued_groups', $issued_group_2_5_1);
        $this->seeInDatabase('issued_groups', $issued_group_2_9_1);
        $this->seeInDatabase('issued_groups', $issued_group_3_5_2);
        $this->seeInDatabase('issued_groups', $issued_group_3_9_2);
    }

    public function testConfirmIssuing()
    {
        $inputs = ['consuming_session_pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'enabled_cases' => [
                [
                    'case_pk' => 'a561a838-8227-11ea-bc55-0242ac130003'
                ],
            ],
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',];
        $this->call('POST', 'confirm_issuing', $inputs);
        $this->seeJsonEquals(['success' => 'Nhận phụ liệu thành công']);
        $this->seeStatusCode(200);
        $pk = app('db')->table('progressing_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $progression_session = ['pk' => $pk,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'kind' => 'confirming'];
        $issuing_session = ['pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'progressing_session_pk' => $pk];
        $issuing_group_1 = ['pk' => 'a561af72-8227-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $issuing_group_2 = ['pk' => '82770cf0-8254-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $case_10 = ['pk' => 'a561a838-8227-11ea-bc55-0242ac130003',
            'is_active' => false];
        $this->seeInDatabase('progressing_sessions', $progression_session);
        $this->seeInDatabase('issuing_sessions', $issuing_session);
        $this->seeInDatabase('issued_groups', $issuing_group_1);
        $this->seeInDatabase('issued_groups', $issuing_group_2);
        $this->seeInDatabase('cases', $case_10);
    }

    public function testReturnIssuing()
    {
        $inputs = ['consuming_session_pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'pairs' => [
                [
                    'case_pk' => 'a561a838-8227-11ea-bc55-0242ac130003',
                    'shelf_pk' => '311edb4e-79b2-11ea-bc55-0242ac130003',
                ],
                [
                    'case_pk' => '82770a98-8254-11ea-bc55-0242ac130003',
                    'shelf_pk' => '311edd56-79b2-11ea-bc55-0242ac130003',
                ]
            ]];
        $this->call('POST', 'return_issuing', $inputs);
        $this->seeStatusCode(200);
        $this->seeJsonEquals(['success' => 'Hủy xuất phụ liệu thành công']);
        $pk = app('db')->table('returning_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $returning_session = ['pk' => $pk,
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'];
        $entry_1 = [
            'kind' => 'restored',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'entry_kind' => 'returning',
            'quantity' => 40,
            'session_pk' => $pk,
            'case_pk' => 'a561a838-8227-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ];
        $entry_2 = [
            'kind' => 'restored',
            'received_item_pk' => '55296414-79b2-11ea-bc55-0242ac130003',
            'entry_kind' => 'returning',
            'quantity' => 40,
            'session_pk' => $pk,
            'case_pk' => '82770a98-8254-11ea-bc55-0242ac130003',
            'accessory_pk' => '483ad4ae-79b2-11ea-bc55-0242ac130003'
        ];
        $case_1 = ['pk' => 'a561a838-8227-11ea-bc55-0242ac130003',
            'shelf_pk' => '311edb4e-79b2-11ea-bc55-0242ac130003'];
        $case_2 = ['pk' => '82770a98-8254-11ea-bc55-0242ac130003',
            'shelf_pk' => '311edd56-79b2-11ea-bc55-0242ac130003'];
        $issued_group_1 = ['pk' => 'a561af72-8227-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $issued_group_2 = ['pk' => '82770cf0-8254-11ea-bc55-0242ac130003',
            'case_pk' => null];
        $issued_item = ['pk' => 'a561ab8a-8227-11ea-bc55-0242ac130003',
            'is_returned' => true];
        $issuing_session = ['pk' => 'a561aa90-8227-11ea-bc55-0242ac130003',
            'returning_session_pk' => $pk];
        $this->seeInDatabase('returning_sessions', $returning_session);
        $this->seeInDatabase('entries', $entry_1);
        $this->seeInDatabase('entries', $entry_2);
        $this->seeInDatabase('cases', $case_1);
        $this->seeInDatabase('cases', $case_2);
        $this->seeInDatabase('issued_groups', $issued_group_1);
        $this->seeInDatabase('issued_groups', $issued_group_2);
        $this->seeInDatabase('issued_items', $issued_item);
        $this->seeInDatabase('issuing_sessions', $issuing_session);
    }
}
