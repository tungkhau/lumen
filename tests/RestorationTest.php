<?php

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\WithoutMiddleware;

class RestorationTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function testRegister()
    {
        $inputs = [
            'restored_items' => [
                [
                    'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003',
                    'restored_quantity' => 500
                ],
                [
                    'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003',
                    'restored_quantity' => 200
                ]
            ],
            'comment' => 'bla',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ];
        $this->call('POST', 'register_restoration', $inputs);
        $restoration_id = 'RN-' . (string)date('dmy') . '-A';
        $pk = app('db')->table('restorations')->where('id', $restoration_id)->value('pk');
        $restoration = ['id' => $restoration_id,
            'pk' => $pk,
            'comment' => 'bla',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];

        $this->seeJsonEquals(['success' => 'Đăng kí phiếu trả thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('restorations', $restoration);
        $restored_items = array();
        foreach ($inputs['restored_items'] as $input) {
            $restored_items[] = [
                'restored_quantity' => $input['restored_quantity'],
                'restoration_pk' => $pk,
                'accessory_pk' => $input['accessory_pk']
            ];
        }
        foreach ($restored_items as $restored_item) {
            $this->seeInDatabase('restored_items', $restored_item);
        }
    }

    public function testDelete()
    {
        $temp = app('db')->table('restored_items')->where('pk', '0756cd6e-71d6-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $restored_item_pks = array();
        foreach ($temp as $item) {
            $restored_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['restoration_pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003'];
        $data = ['pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_restoration', $inputs);
        $this->seeJsonEquals(['success' => 'Hủy phiếu trả thành công']);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('restorations', $data);
        foreach ($restored_item_pks as $restored_item_pk) {
            $this->notSeeInDatabase('restored_items', $restored_item_pk);
        }
    }

    public function testConfirm()
    {
        $inputs = [
            'restoration_pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ];
        $data = ['pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003',
            'is_confirmed' => True];
        $this->call('POST', 'confirm_restoration', $inputs);
        $this->seeJsonEquals(['success' => 'Xác nhận phiếu trả thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('restorations', $data);
    }

    public function testCancel()
    {
        $inputs = [
            'restoration_pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ];
        $data = ['pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'is_confirmed' => False];
        $temp = app('db')->table('restored_items')->where('restoration_pk', '0756c72e-71d6-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $restored_item_pks = array();
        foreach ($temp as $item) {
            $restored_item_pks[] = [
                'pk' => $item
            ];
        }
        $this->call('POST', 'cancel_restoration', $inputs);
        $this->seeJsonEquals(['success' => 'Hủy phiếu trả thành công']);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('restorations', $data);
        foreach ($restored_item_pks as $restored_item_pk) {
            $this->notSeeInDatabase('restored_items', $restored_item_pk);
        }
    }

    public function testReceive()
    {
        $inputs = [
            'restoration_pk' => '0756c72e-71d6-11ea-bc55-0242ac130003',
            'restored_groups' => [
                [
                    'restored_item_pk' => '0756cb02-71d6-11ea-bc55-0242ac130003',
                    'grouped_quantity' => '150',
                    'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003', //Case 1
                ],
                [
                    'restored_item_pk' => '0756cc10-71d6-11ea-bc55-0242ac130003',
                    'grouped_quantity' => '300',
                    'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003', //Case 1
                ],
                [
                    'restored_item_pk' => '0756cb02-71d6-11ea-bc55-0242ac130003',
                    'grouped_quantity' => '50',
                    'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003', //Case 2
                ],
                [
                    'restored_item_pk' => '0756cc10-71d6-11ea-bc55-0242ac130003',
                    'grouped_quantity' => '200',
                    'case_pk' => '5b4ca804-7388-11ea-bc55-0242ac130003', //Case 2
                ]
            ],
            'user_pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003'
        ];
        $this->call('POST', 'receive_restoration', $inputs);
        $receiving_session_pk = app('db')->table('receiving_sessions')->orderBy('executed_date', 'desc')->first()->pk;
        $receiving_session = [
            'kind' => 'restoring',
            'pk' => $receiving_session_pk,
            'user_pk' => $inputs['user_pk']
        ];
        $restoration = [
            'pk' => $inputs['restoration_pk'],
            'receiving_session_pk' => $receiving_session_pk
        ];
        $cases = array();
        $received_groups = array();
        foreach ($inputs['restored_groups'] as $restored_group) {
            $received_groups[] = [
                'received_item_pk' => $restored_group['restored_item_pk'],
                'grouped_quantity' => $restored_group['grouped_quantity'],
                'kind' => 'restored',
                'receiving_session_pk' => $receiving_session_pk,
                'case_pk' => $restored_group['case_pk']
            ];
            $cases[] = [
                'pk' => $restored_group['case_pk'],
                'shelf_pk' => Null
            ];
        }
        $this->seeJsonEquals(['success' => 'Ghi nhận phiếu trả thành công']);
        $this->seeStatusCode(200);
        foreach ($received_groups as $received_group) {
            $this->seeInDatabase('received_groups', $received_group);
        }
        foreach ($cases as $case) {
            $this->seeInDatabase('cases', $case);
        }
        $this->seeInDatabase('receiving_sessions', $receiving_session);
        $this->seeInDatabase('restorations', $restoration);
    }
}
